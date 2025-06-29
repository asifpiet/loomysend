<?php

namespace App\Http\Controllers\Api;
use App\Enums\AndroidApiSimEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Models\User;
use App\Models\Contact;
use App\Models\CreditLog;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\CampaignContact;
use Illuminate\Validation\Rule;
use App\Models\AndroidApiSimInfo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Utility\Api\ApiJsonResponse;
use App\Models\CommunicationLog;
use App\Service\Admin\Dispatch\SmsService;
use Carbon\Carbon;

class AndroidApiSmsController extends Controller {

    /**
     * @return JsonResponse
     */
    public function init() {

        try {
            
            return ApiJsonResponse::success("Successfully initiated request.", site_settings("site_name"));
        
        } catch(\Exception $e) {
            
            return ApiJsonResponse::exception($e);
        }
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     * 
     */
    public function configureSim(Request $request) {

        try {

            $validator = Validator::make($request->all(),[
                
                'android_gateway_id' => 'exists:android_apis,id',
                'time_interval'      => 'integer',
                'send_sms'           => 'integer',
                'status'             => [Rule::in([AndroidApiSimEnum::ACTIVE->value, AndroidApiSimEnum::INACTIVE->value])]
            ]);
    
            if ($validator->fails()) {
               
                return ApiJsonResponse::validationError($validator->errors());
            }

            $information = [];

            foreach($request->toArray() as $key => $value) {

                $information[$key] = $value;
            }
    
            $simInfo = AndroidApiSimInfo::updateOrCreate([
                'id' => $request->input("id"),
            ], $information);
            
            $data = [
                'android_gateway_sim_id' => $simInfo->id
            ];

            return ApiJsonResponse::success($simInfo->wasRecentlyCreated ? "Successfully Added a new SIM" 
                                                                            : "SIM Successfully Updated", 
                                            $data, $simInfo->wasRecentlyCreated ? 201 : 200);

        } catch(\Exception $e) {

            return ApiJsonResponse::exception($e);
        }
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     * 
     */
    public function smsfind(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
            
                'android_gateway_sim_id' => 'required|exists:android_api_sim_infos,id',
            ], [
                'android_gateway_sim_id.exists' => "Selected sim for this sms is no longer available"
            ]);
    
            if ($validator->fails()) {
    
                return ApiJsonResponse::validationError($validator->errors());
            }
    
            $smslog = CommunicationLog::whereNull('gateway_id')
                                            ->where('type', ServiceType::SMS->value)
                                            ->where('android_gateway_sim_id', $request->android_gateway_sim_id)
                                            ->where('status', (string)CommunicationStatusEnum::PENDING->value)
                                            ->select('id', 'android_gateway_sim_id', "meta_data", 'created_at', 'message')
                                            ->take(1)
                                            ->first();
            if ($smslog) {
                
                $custom_data = [
                    'id' => $smslog->id,
                    'android_gateway_sim_id' => $smslog->android_gateway_sim_id,
                    'to' => array_key_exists('contact', $smslog->meta_data) ? (string)$smslog->meta_data['contact'] : null, 
                    'initiated_time' => Carbon::parse($smslog->created_at)->format('Y-m-d H:i:s'),
                    'message' => array_key_exists('message_body', $smslog->message) ? $smslog->message['message_body'] : null,
                ];
            }
            return ApiJsonResponse::success($smslog ? "Successfully Fetched SMS from logs" : '', $smslog ? $custom_data : null);
            
            

        } catch (\Exception $e) {

            return ApiJsonResponse::exception($e);
        }
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     * 
    */
    public function smsStatusUpdate(Request $request) {

        try {
            
            $validator = Validator::make($request->all(), [
                
                'id'     => ['required', 'exists:communication_logs,id'],
                'status' => ['required', Rule::in([CommunicationStatusEnum::FAIL->value, CommunicationStatusEnum::DELIVERED->value,  CommunicationStatusEnum::PROCESSING->value])],
            ], [
                'id.exists' => "SMS is not longer available"
            ]);
    
            if ($validator->fails()) {
    
                return ApiJsonResponse::validationError($validator->errors());
            }
    
            $smslog = CommunicationLog::where('id', $request->id)
                                        ->whereIn('status', [CommunicationStatusEnum::PENDING->value, CommunicationStatusEnum::PROCESSING->value])
                                        ->first(); 

           
                            
            if(!$smslog) { return ApiJsonResponse::notFound(); }
    
            if ($smslog) {
               
                if ($request->status == CommunicationStatusEnum::DELIVERED->value) {

                    $meta_data = $smslog->meta_data;
                    $meta_data['delivered_at'] = Carbon::now()->toDayDateTimeString();
                    $smslog->meta_data = $meta_data;
                    $smslog->status = CommunicationStatusEnum::DELIVERED->value;
                    $smslog->save();
                } elseif ($request->status == CommunicationStatusEnum::PROCESSING->value) {
    
                    $smslog->status = CommunicationStatusEnum::PROCESSING->value;
                    $smslog->save();
                } else {

                    SmsService::updateSMSLogAndCredit($smslog, CommunicationStatusEnum::FAIL->value, $request->response_gateway);
                }
            }
            return ApiJsonResponse::success("Successfully updated sms status.");

        } catch(\Exception $e) {

            SmsService::updateSMSLogAndCredit($smslog, CommunicationStatusEnum::FAIL->value, $e->getMessage());
            return ApiJsonResponse::exception($e);
        }
    }
    
    /* webhook functions twilio*/
    
    
    public function handleWebhook(Request $request)
    {
        $message = $request->input('Body');
        $from = $request->input('From');
        
        $number_without_plus = str_replace("+", "", $from);
        \Log::info("User opted From: { $from}");
        
        $optOutKeywords = ['stop', 'STOP', 'CANCEL', 'UNSUBSCRIBE', 'QUIT', 'END'];
        $resubscribeKeywords = ['START', 'start' ,'RESUBSCRIBE'];
        if (in_array(strtoupper($message), $optOutKeywords)) {
            $this->handleOptOut($number_without_plus);

            return response('Opt-out processed', 200);
        }
        
        if (in_array(strtoupper($message), $resubscribeKeywords)) {
            $this->handleResubscribe($number_without_plus);
            return response('Resubscribe processed', 200);
    }
        
        return response('No action taken', 200);
    }
    
    protected function handleResubscribe($number)
    {
        \Log::info("User Number: $number");
        $contact = Contact::where('sms_contact', $number)->first();
        
        if ($contact) {
            Contact::where('sms_contact', $number)
            ->update(['is_optout' => false]);
        } else {
            \Log::info("User not found for opt-out: $number");
        }
    }

    protected function isValidTwilioRequest(Request $request)
    {
        return true;
    }

    protected function handleOptOut($phoneNumber)
    {
        
            Contact::where('sms_contact', $phoneNumber)
            ->update(['is_optout' => true]);

        \Log::info("User opted out: {$phoneNumber}");
    }
}
