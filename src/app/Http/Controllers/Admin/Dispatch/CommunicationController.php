<?php

namespace App\Http\Controllers\Admin\Dispatch;


use App\Enums\StatusEnum;
use Illuminate\View\View;
use App\Enums\ServiceType;
use Illuminate\Http\Request;
use App\Models\CommunicationLog;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Enums\CommunicationStatusEnum;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Dispatch\SmsService;
use App\Service\Admin\Core\CustomerService;
use App\Service\Admin\Dispatch\EmailService;
use App\Service\Admin\Dispatch\WhatsAppService;
use App\Http\Requests\Admin\CommunicationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CommunicationController extends Controller
{
    public $smsService;
    public $whatsappService;
    public $emailService;
    public $customerService;
    public function __construct() {

        $this->customerService = new CustomerService();
        $this->smsService      = new SmsService($this->customerService);
        $this->whatsappService = new WhatsAppService($this->customerService);
        $this->emailService    = new EmailService($this->customerService);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function smsList(Request $request): View {
        
       
        $user_id = null;
        $campaign_id = null;
        if($request->query('id')){
            $campaign_id = $request->query('id');    
        }
        
        Session::put("menu_active", true);
        $title            = translate("SMS Log");
        $logs             = $this->smsService->logs($user_id, $campaign_id);
        
        
        $api_gateways     = $this->smsService->smsGateway();
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status());
        
        
        if($campaign_id){
                
            $sent = CommunicationLog::where('type', 1)->where('campaign_id', $campaign_id)->count();
            $pending = CommunicationLog::where('type', 1)->where('status', 3)->where('campaign_id', $campaign_id)->count();
            $failed = CommunicationLog::where('type', 1)->where('status', 4)->where('campaign_id', $campaign_id)->count();
            $delivered = CommunicationLog::where('type', 1)->where('status', 6)->where('campaign_id', $campaign_id)->count();
            // $clicked = CommunicationLog::where('type', 1)->whereNotNull('clicked_at')->where('campaign_id', $user_id)->count();
            $clicked = CommunicationLog::where('type', 1)
                        ->where('campaign_id', $campaign_id)
                        ->where(function ($query) {
                            $query->whereNotNull('clicked_at')
                               ->where('clicked_at', '!=', ' ');
                        })
                     ->count();

            
        }else{
            $sent = CommunicationLog::where('type', 1)->count();
            $pending = CommunicationLog::where('type', 1)->where('status', 2)->count();
            $failed = CommunicationLog::where('type', 1)->where('status', 4)->count();
            $delivered = CommunicationLog::where('type', 1)->where('status', 5)->count();
            // $clicked = CommunicationLog::where('type', 1)->whereNotNull('clicked_at')->count();
            $clicked = CommunicationLog::where('type', 1)
                     ->where(function ($query) {
                         $query->whereNotNull('clicked_at')
                               ->where('clicked_at', '!=', ' ');
                     })
                     ->count();
        }
        $trackingId = Str::uuid()->toString();
        
        $user_id = $campaign_id;
        return view('admin.communication.sms.index', compact( 'trackingId', 'user_id', 'sent', 'failed', 'delivered', 'clicked', 'title', 'logs', 'pending', 'android_gateways', 'api_gateways'));
    }
    
    
    
    public function exportEmailLogsToCsv(Request $request , $user_id=null)
    {
        
        if($user_id){
                $logs   = CommunicationLog::where('type', 3)->where('campaign_id', $user_id)->get();
        }else{
            $logs       = CommunicationLog::where('type', 3)->get();
            }
        $headers = [
            'SL No.',
            'User',
            'Open',
            'Date & Time',
            'Status',
        ];
        
        // dd($logs);
        $callback = function () use ($logs, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
    
            foreach ($logs as $key => $log) {
                
                $contact_id= CommunicationLog::where('id', $log->id)->select('contact_id')->first();
                if($contact_id->contact_id > 0) {
                        $contact_num = Contact::where('id', $contact_id->contact_id)->select('email_contact')->first();
                        $user = $contact_num->email_contact;
                }else{
                    $user = '';
                }
                
                switch ($log->status) {
                case 1:
                    $status = 'Cancel';
                    break;
                case 2:
                    $status = 'Pending';
                    break;
                case 3:
                    $status = 'Scheduled';
                    break;
                case 4:
                    $status = 'Delivered';
                    break;
                case 5:
                    $status = 'Delivered';
                    break;
            }
                $row = [
                    $key + 1,
                    '"' .$user,
                    $log->unique_clicks,
                    $log->created_at ? $log->created_at->toDateTimeString() : 'N/A',
                    $status, 
                ];
                fputcsv($file, $row);
            }
    
            fclose($file);
        };
    
        
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=email_logs.csv",
        ];
    
        return response()->stream($callback, 200, $headers);
    }
   
    public function exportSMSLogsToCsv(Request $request , $user_id=null)
    {
        
        if($user_id){
                $logs   = CommunicationLog::where('type', 1)->where('campaign_id', $user_id)->get();
        }else{
            $logs       = CommunicationLog::where('type', 1)->get();
            }
        $headers = [
            'SL No.',
            'User',
            'Clicked',
            'Date & Time',
            'Status',
        ];
    
        $callback = function () use ($logs, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
    
            foreach ($logs as $key => $log) {
                $contact_id= CommunicationLog::where('id', $log->id)->select('contact_id')->first();
                $contact_num = Contact::where('id', $contact_id->contact_id)->select('sms_contact')->first();
                switch ($log->status) {
                case 1:
                    $status = 'Cancel';
                    break;
                case 2:
                    $status = 'Pending';
                    break;
                case 3:
                    $status = 'Scheduled';
                    break;
                case 4:
                    $status = 'Delivered';
                    break;
                case 5:
                    $status = 'Delivered';
                    break;
            }
                $row = [
                    $key + 1,
                    '"' .$contact_num->sms_contact, 
                    $log->clicked_at,
                    $log->created_at ? $log->created_at->toDateTimeString() : 'N/A',
                    $status, 
                ];
                fputcsv($file, $row);
            }
    
            fclose($file);
        };
    
        
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=sms_logs.csv",
        ];
    
        return response()->stream($callback, 200, $headers);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function whatsappList(): View {

        Session::put("menu_active", true);
        $title     = translate("WhatsApp Log");
        $logs      = $this->whatsappService->logs();
        $cloud_api = $this->whatsappService->gateways(StatusEnum::TRUE->status()); 
        $devices   = $this->whatsappService->gateways(StatusEnum::FALSE->status());
        return view('admin.communication.whatsapp.index', compact('title', 'logs', 'devices', 'cloud_api'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function emailList(Request $request): View {
        
        
        $user_id = null;
        $campaign_id = null;
        if($request->query('id')){
            $campaign_id = $request->query('id');    
        }
        
        Session::put("menu_active", true);
        $title    = translate("Email Log");
        $logs     = $this->emailService->logs($user_id, $campaign_id);
        
        $gateways = $this->emailService->gateways(StatusEnum::TRUE->status()); 
        
        if($campaign_id){
                // dd($campaign_id);
            $sent = CommunicationLog::where('type', 3)->where('campaign_id', $campaign_id)->count();
            $pending = CommunicationLog::where('type', 3)->where('status', 3)->where('campaign_id', $campaign_id)->count();
            $failed = CommunicationLog::where('type', 3)->where('status', 4)->where('campaign_id', $campaign_id)->count();
            $delivered = CommunicationLog::where('type', 3)->where('status', 5)->where('campaign_id', $campaign_id)->count();
            $clicked = CommunicationLog::where('type', 3)->whereNotNull('clicked_at')->where('campaign_id', $campaign_id)->count();
        
            //  dd($delivered);
        }else{
            // dd('fgf');
            $sent = CommunicationLog::where('type', 3)->count();
            $pending = CommunicationLog::where('type', 3)->where('status', 2)->count();
            $failed = CommunicationLog::where('type', 3)->where('status', 4)->count();
            $delivered = CommunicationLog::where('type', 3)->where('status', 5)->count();
            $clicked = CommunicationLog::where('type', 3)->whereNotNull('clicked_at')->count();
        }
        
        // dd($logs);
        return view('admin.communication.email.index', compact('sent', 'user_id', 'failed', 'pending', 'delivered', 'clicked', 'title', 'logs', 'gateways'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createSms(): View {

        Session::put("menu_active", true);
        $type             = "sms";
        $title            = ucfirst($type);
        $column_name      = $type . '_contact';
        $groups           = $this->smsService->getGroupWhereColumn($column_name);
        $templates        = $this->smsService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $api_gateways     = $this->smsService->smsGateway(); 
        $android_gateways = $this->smsService->androidGateways(StatusEnum::TRUE->status());
        return view("admin.communication.$type.create", compact('title', 'templates', 'api_gateways', 'groups', 'android_gateways', 'type'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createWhatsapp(): View {

        Session::put("menu_active", true);
        $type        = "whatsapp";
        $title       = ucfirst($type);
        $column_name = $type . '_contact';
        $groups      = $this->whatsappService->getGroupWhereColumn($column_name);
        $templates   = $this->whatsappService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $cloud_api_accounts = $this->whatsappService->gateways(StatusEnum::TRUE->status()); 
        $devices     = $this->whatsappService->gateways(StatusEnum::FALSE->status());
        return view("admin.communication.$type.create", compact('title', 'templates', 'cloud_api_accounts', 'groups', 'devices', 'type'));
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function createEmail(): View {
        
        // dd('email');
        Session::put("menu_active", true);
        $type        = "email";
        $title       = ucfirst($type);
        $column_name = $type . '_contact';
        $groups      = $this->emailService->getGroupWhereColumn($column_name);
        $templates   = $this->emailService->getTemplateWithStatusType(StatusEnum::TRUE->status(), constant(ServiceType::class . '::' . strtoupper($type))->value);
        $gateways    = $this->emailService->gateways(StatusEnum::TRUE->status()); 
        return view("admin.communication.$type.create", compact('title', 'templates', 'gateways', 'groups', 'type'));
    }

    public function viewEmailBody($id) {

        $title     = translate("Details View");
        $emailLogs = CommunicationLog::where('id',$id)->orderBy('id', 'DESC')->limit(1)->first();
        return view('partials.email_view', compact('title', 'emailLogs'));
    }

    /**
     * @param CommunicationRequest $request
     * 
     * @param string $type
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function store(CommunicationRequest $request, string $type): RedirectResponse {
        //  dd($request->all());
        // dd('StoreEmail');
        $status  = 'error';
        $message = "Something went wrong";
        
        try {
            $data = $request->all();
            // dd($request->all());
            // if (isset($data['message']) && is_array($data['message'])) {
            //     $data['message']['message_body'] = $data['htmlresponse'];
            
            // }
            
            unset($data['_token']);
            if($type == 'sms') {
                
                list($status, $message) = $this->smsService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'));
            } elseif($type == 'whatsapp') {

                list($status, $message) = $this->whatsappService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'));
            } else {
                
                list($status, $message) = $this->emailService->store($type, $data, $request->hasFile('contacts') ? 'file' : (is_array($request->input('contacts')) ? 'array' : 'text'));
            }
        } catch (\Exception $e) {
            
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function statusUpdate(Request $request): RedirectResponse {

        $status  = "error";
        $message = "Something went wrong";

        try {
            $data = $request->all();
            unset($data['_token']);
            $log = $this->getLogById($data['id']);
            $data['message'] = $log->message;
            if($log->campaign_id) {
                
                $notify[] = ["error", translate("You can not update campaign status")];
                return back()->withNotify($notify);
            }
            if($log->type == ServiceType::SMS->value) {
                
                $data['sms_type'] = $log->meta_data['sms_type'];
                list($status, $message) = $this->smsService->statusUpdate($data, $log);
            } elseif($log->type == ServiceType::WHATSAPP->value) {

                list($status, $message) = $this->whatsappService->statusUpdate($data, $log);
            } elseif($log->type == ServiceType::EMAIL->value) {
                
                list($status, $message) = $this->emailService->statusUpdate($data, $log);
            }
            
            
        } catch (\Exception $e) {
            dd($e);
            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function delete(Request $request): RedirectResponse {

        $status  = "error";
        $message = "Something went wrong";

        try {

            $communication_log = CommunicationLog::where('id', $request->input('id'))->first();
            if($communication_log) {

                $communication_log->delete();
                $status  = "success";
                $message = translate("SMS Log deleted successfully");
            } else {

                $status  = "success";
                $message = translate("Log couldnt be found");
            }

        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

     /**
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function bulk(Request $request, $type = null) :RedirectResponse {
        
        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {
            if($type == ServiceType::SMS->value) {

                list($status, $message) = $this->smsService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            } elseif($type == ServiceType::WHATSAPP->value) {

                list($status, $message) = $this->whatsappService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            } elseif($type == ServiceType::EMAIL->value) {

                list($status, $message) = $this->emailService->bulkAction($request, $type, [
                    "model" => new CommunicationLog(),
                ]);
            }
           
    
        } catch (\Exception $exception) {
            
            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function api(Request $request) {
        
        Session::put("menu_active", false);
        $title   = translate("API Document");
        $api_key = Auth::guard('admin')->user()->api_key;

        if($request->ajax()) {

            $status = 'error';
            $message = translate("Something went wrong");
            try {
                $admin = Auth::guard('admin')->user();
                $admin->api_key = $request->has('api_key') ? $request->input('api_key') : $admin->api_key;
                $admin->save();
                $status = 'success';
                $message = translate("Admin API Key has been saved successfully");
            } catch(\Exception $e) {

                $message = translate("Server Error");
            }
            return response()->json([
               'status'  => $status, 
               'message' => $message
            ],'200');
        }
        return view('admin.communication.api', compact('title', 'api_key'));
    }

    public function getLogById($id) {

        return CommunicationLog::where('id', $id)->first();
    }
}
