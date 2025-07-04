<?php

namespace App\Http\Controllers\Admin\Template;

use App\Models\Template;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Enums\TemplateProvider;
use Illuminate\Http\Request;
use App\Service\Admin\Template\TemplateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
	public $templateService;
    public function __construct() {

        $this->templateService = new TemplateService();
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
	public function index($id = null) {
		
		Session::put("menu_active", true);

		$templates = [];
		$title 	   = translate("Template List");

		$user_templates    = $this->templateService->getUserTemplate();
		$admin_templates   = $id ? $this->templateService->getParentTemplate($id) : $this->templateService->getAdminTemplate();
        $default_templates = $this->templateService->getDefaultTemplate();
        $global_template   = $this->templateService->getGlobalTemplate();

		return view('admin.template.index', compact('title', 'admin_templates', 'user_templates', 'default_templates', 'global_template'));
	}

    /**
     * @return \Illuminate\View\View
     * 
     */
	public function createEmailTemplate() {
		
		Session::put("menu_active", true);
		$title = translate("Create Email Template");
		$plugin_templates = $this->templateService->getPluginTemplates();
		return view('admin.template.email.create', compact('title', 'plugin_templates'));
	}

    /**
     * @return \Illuminate\View\View
     * 
     */
	public function editEmailTemplate($id = null) {
		
        $template = Template::find($id);
        if(!$template) {

            $notify = ['error', translate("Template not found")];
            return back()->withNotify($notify);
        } else {

            Session::put("menu_active", true);
            $title = translate("Update Email Template");
            $plugin_templates  = $this->templateService->getPluginTemplates();
            return view('admin.template.email.edit', compact('title', 'plugin_templates', 'template'));
        }
	}

	/**
     *
     * @param TemplateRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function save(TemplateRequest $request) {
        
        
    
        $status  = 'error';
        $message = translate("Something went wrong");
        try {
			
			$data = $request->all();
// 			dd($data );
			unset($data['_token']);
            $template = $this->templateService->save($this->templateService->prepTemplateData($request->input('type'), $data), $request);
            $status   = 'success';
            $message  = ucfirst(ServiceType::getValue($template->type)). ' template has been saved.';
            
        } catch (\Exception $e) {

            $message = translate("Server Error: ") . $e->getMessage();
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

	/**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws ValidationException If the validation fails.
     * 
     */
    public function statusUpdate(Request $request) {
        
        try {

            $this->validate($request,[

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]); 

            $notify = $this->templateService->statusUpdate($request);
            return $notify;

        } catch (ValidationException $validation) {

            return json_encode([
                
                'status'  => false,
                'message' => $$validation->errors()
            ]);
        } 
    }
	/**
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request) {
       
        $status  = 'success';
        $message = translate('Template has been successfully deleted');
        try {
            
            $template = Template::find($request->id);
            $template->delete();
            
        } catch(\Exception $e) {

            $status  = 'success';
            $message = $e->getMessage();
        }
      
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception
     * 
     */
	public function refresh(Request $request) {
		
		$status  = true;
		$reload  = true;
		$message = translate("Templates are added");
		try {

			$template_data = $this->templateService->whatsappCloudApiData($request);
			
			if(array_key_exists("error", $template_data)) {
				
				$message = $template_data['error']['message'];
				$status = false;
			} elseif(array_key_exists("data", $template_data)) {

				$this->templateService->saveWhatsappTemplates($template_data, $request);
				
			} else {
				$status = false;
				$message = translate("Something went wrong");
			}
			return json_encode([

				'reload'  => $status,
				'status'  => $reload,
				'message' => $message
			]);

		} catch (\Exception $e) {
			
			return json_encode([
				'reload'  => true,
				'status'  => false,
				'message' => $e->getMessage()
			]);
		}
	}

    /**
     * @param $id
     * 
     * @return json $data
     * 
     */
    public function templateJson($id){
        
        $data = Template::find($id);
        $data = $data->template_data;
        return $data;
    }

    /**
     * @param $id
     * 
     * @return json $data
     * 
     */
    public function editTemplateJson($id){

        $data = Template::find($id);
        $template = (object)$data->template_data;
        $data = $template->template_json;
        return $data;
    }

    public function emailTemplates(Request $request) {

        if($request->ajax()) {
            return response()->json([
                'view' => view('admin.email_template.data',[
                    'templates' => Template::where('type', ServiceType::EMAIL->value)
                                            ->whereNull('user_id')
                                            ->where('global', StatusEnum::FALSE->status())
                                            ->where('default', StatusEnum::FALSE->status())
                                            ->where('plugin', StatusEnum::FALSE->status())
                                            ->where('status', StatusEnum::TRUE->status())
                                            ->get()
                    ]
                )->render()
            ],'200' );
        }
    }

    public function fetch(Request $request, $type = null) {
		
		$templates = Template::whereNull("user_id")
                                ->where('type', constant(ServiceType::class . '::' . strtoupper($type))->value)
                                ->where("cloud_id", $request->input("cloud_id"))
                                ->where("status", StatusEnum::TRUE->status())
                                ->get();
		return response()->json(['templates' => $templates]);
	
	}
}
