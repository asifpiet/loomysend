<?php

namespace App\Service\Admin\Dispatch;

use App\Enums\CampaignStatusEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\CommunicationLog;
use App\Service\Admin\Contact\ContactGroupService;
use App\Service\Admin\Dispatch\EmailService;
use App\Service\Admin\Dispatch\SmsService;
use App\Service\Admin\Dispatch\WhatsAppService;
use App\Service\Admin\Gateway\EmailGatewayService;
use App\Service\Admin\Gateway\SmsGatewayService;
use App\Service\Admin\Gateway\WhatsappGatewayService;

class CampaignService
{
    public function __construct(
        protected SmsService $smsService,
        protected EmailService $emailService,
        protected WhatsAppService $whatsAppService,
        protected ContactGroupService $contactGroupService,
        protected SmsGatewayService $smsGatewayService,
        protected WhatsappGatewayService $whatsappGatewayService,
        protected EmailGatewayService $emailGatewayService,
    ){}

    /**
     * 
     * @return CommunicationLog
     */
    public function logs($user_id = null) {
        
        // dd($user_id);
        return Campaign::search(['name'])
            ->where("user_id", $user_id)
            ->latest()
            ->date()
            ->filter(['status'])
            ->routefilter()
            ->paginate(paginateNumber(site_settings("paginate_number")))
            ->appends(request()->all());
    }


    public function save($type, $data, $user_id = null) {
       
        if($data && isset($data['id'])){
            $upcomingContacts = [];    
            foreach($data['contacts'] as $contact){
                $upcomingContacts = array_merge($upcomingContacts, Contact::where('group_id', $contact)->pluck('id')->toArray());
            }
            $previousContacts = CommunicationLog::where('campaign_id',$data['id'])->pluck('contact_id')->toArray();
            $contactsToDelete = array_diff($previousContacts, $upcomingContacts);
            // dd($previousContacts, $upcomingContacts,$contactsToDelete);
            if (!empty($contactsToDelete)) {
                CommunicationLog::whereIn('contact_id', $contactsToDelete)
                    ->where('campaign_id', $data['id'])
                    ->delete();
            }
        }
       
        $campaign = $this->storeCmapaignData($this->prepCampaignData(constant(ServiceType::class . '::' . strtoupper($type))->value, $data, $user_id));
        // dd($data);
        if(array_key_exists('id', $data) && $data['id']) {
            
            CommunicationLog::where('campaign_id', $data["id"])
                                ->where('status',CommunicationStatusEnum::PENDING->value)
                                ->delete();
            // unset($data['id']);
        }
        // dd($data,$data['id']);
        $contacts    = $data['contacts'];
        $meta_name   = array_key_exists('attribute_name', $data) ? $data['attribute_name'] : null;
        $logic       = array_key_exists('logic', $data) ? $data['logic'] : null;
        $logic_range = array_key_exists('logic_range', $data) ? $data['logic_range'] : null;
        $group_logic = array_key_exists('group_logic', $data) ? $data['group_logic'] : null;
        $meta_data   = $this->contactGroupService->retrieveContacts($type, $contacts, $group_logic, $meta_name, $logic, $logic_range, $user_id);
        // dd($meta_data);
        $data = $this->prepForLog($this->campaignRelatedField(), $data);
        $data['campaign_id'] = $campaign->id;
        
        if($type == 'sms') {
            
            list($status, $message, $meta_data, $gateway) = $this->smsGatewayService->assignGateway($data['method'], $data['gateway_id'], $meta_data, $data['sms_type'], $campaign->name, $user_id);
            
            if($gateway) {

                if($data['method'] == StatusEnum::FALSE->status()) {

                    $data['android_gateway_sim_id'] = $gateway ? $gateway->id : null;
                    $data['gateway_id'] = null;
                    $gateway = $data['android_gateway_sim_id'] ? null : $gateway;
                } else {
    
                    $data['gateway_id'] = $gateway->id;
                    $data['android_gateway_sim_id'] = null;
                }
                $this->smsService->prepData($data, $meta_data, $gateway, $user_id);
            }

        } elseif($type == 'whatsapp') {

            list($status, $message, $meta_data, $gateway) = $this->whatsappGatewayService->assignGateway($data['method'], $data['gateway_id'], $meta_data, $user_id);

            if($gateway) {
                $this->whatsAppService->prepData($data, $meta_data, $gateway, $user_id);
            }
        } elseif($type == 'email') {

            list($status, $message, $meta_data, $gateway) = $this->emailGatewayService->assignGateway($data['gateway_id'], $meta_data, $user_id);

            if($gateway) { 
                unset($data['gateway_id']);
                $this->emailService->prepData(ServiceType::EMAIL->value, $data, $meta_data, $gateway, $user_id);
            }
        }
        
        return [
            $status,
            $message
        ];   
    }

    public function prepForLog($fields, $data) {

        foreach($fields as $field) {

            if(array_key_exists($field, $data)) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    public function campaignRelatedField() {

        return [
            'group_logic',
            'attribute_name',
            'logic',
            'logic_range',
            'name',
            'repeat_format',
            'repeat_time'
        ];
    }

    public function storeCmapaignData($campaign_data) {
        
        $id = $campaign_data['id'];
        unset($campaign_data['id']);
        return Campaign::updateOrCreate([
            'id' => $id
        ], $campaign_data);
    }
    public function prepCampaignData($type, $data, $user_id = null) {

        $meta_data = array_merge($this->prepContactMetaData($data), $this->prepMesssageData($data, $type), $this->prepGatewayData($data, $type));
        
        $campaign_data = [
            'id'   => array_key_exists('id', $data) ? $data['id'] : null,
            'type' => $type,
            'user_id' => $user_id,
            'name' => $data['name'],
            'repeat_time' => $data['repeat_time'],
            'repeat_format' => array_key_exists('repeat_format', $data) ? $data['repeat_format'] : null,
            'meta_data' => $meta_data,
            'schedule_at' => $data['schedule_at']
        ];
        return $campaign_data;
    } 

    public function prepContactMetaData($data) {

        $contact_meta_data['contact'] = [

            'group_ids'      => array_key_exists('contacts', $data) ? $data['contacts'] : null,
            'group_logic'    => array_key_exists('group_logic', $data) ? $data['group_logic'] : null,
            'attribute_name' => array_key_exists('attribute_name', $data) ? $data['attribute_name'] : null,
            'logic'          => array_key_exists('logic', $data) ? $data['logic'] : null,
            'logic_range'    => array_key_exists('logic_range', $data) ? $data['logic_range'] : null,
        ];
        return $contact_meta_data;
    }

    public function prepMesssageData($data, $type) {

        $message_mete_data = [];
        if($type == ServiceType::SMS->value) {

            $message_mete_data['message'] = [
                'sms_type'     => array_key_exists('sms_type', $data) ? $data['sms_type'] : null,
                'message_body' => array_key_exists('message_body', $data['message']) ? $data['message']['message_body'] : null,
            ];
            
        } elseif($type == ServiceType::WHATSAPP->value) {

            $message_mete_data['message'] = [
                'message_body' => array_key_exists('message_body', $data['message']) ? $data['message']['message_body'] : null,
            ];
        } elseif($type == ServiceType::EMAIL->value) {

            
            $message_mete_data['message'] = [
                
                'email_from_name' => array_key_exists('email_from_name', $data) ? $data['email_from_name'] : null,
                'reply_to_address' => array_key_exists('reply_to_address', $data) ? $data['reply_to_address'] : null,
                'subject' => array_key_exists('subject', $data['message']) ? $data['message']['subject'] : null,
                'message_body' => array_key_exists('message_body', $data['message']) ? $data['message']['message_body'] : null,
            ];
        }
        return $message_mete_data;
    }

    public function prepGatewayData($data, $type) {

        $gateway_mete_data = [];
        if($type == ServiceType::SMS->value) {

            $gateway_mete_data['gateway'] = [

                'gateway_id' => array_key_exists('gateway_id', $data) ? $data['gateway_id'] : null,
                'method'     => array_key_exists('method', $data) ? $data['method'] : null,
            ];
        } elseif($type == ServiceType::WHATSAPP->value) {

            $gateway_mete_data['gateway'] = [

                'gateway_id' => array_key_exists('gateway_id', $data) ? $data['gateway_id'] : null,
                'method'     => array_key_exists('method', $data) ? $data['method'] : null,
            ];
        } elseif($type == ServiceType::EMAIL->value) {

            $gateway_mete_data['gateway'] = [

                'gateway_id' => array_key_exists('gateway_id', $data) ? $data['gateway_id'] : null
            ];
        }
        return $gateway_mete_data;
    }

    public function statusUpdate($request, $user_id = null) {
        
        try {
            $status   = true;
            $reload   = false;
            $message  = translate('Gateway status updated successfully');
            $column   = $request->input("column");
            $campaign  = Campaign::where("id",$request->input('id'))->first();
            if($request->value == CampaignStatusEnum::DEACTIVE->value) {
                $reload   = true;
                $campaign->status = CampaignStatusEnum::ACTIVE->value;
                $campaign->update();

            } else {

                $status = false;
                $message = translate("Something went wrong while updating this gateway");
            }

        } catch (\Exception $error) {

            $status  = false;
            $message = $error->getMessage();
        }

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }
}
