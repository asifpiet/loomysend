<?php

namespace App\Http\Controllers\Admin\Contact;

use App\Models\Group;
use App\Models\Contact;
use App\Enums\StatusEnum;
use Illuminate\View\View;
use App\Traits\ModelAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Core\FileService;
use App\Service\Admin\Contact\ContactService;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    use ModelAction;
    public ContactService $contactService;
    public FileService $fileService;
    public function __construct(ContactService $contactService, FileService $fileService) { 

        $this->contactService = $contactService;
        $this->fileService    = $fileService;
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index($id = null): View {
        
        Session::put("menu_active", true);
        $title              = translate("Contact List");
        $contacts           = $id ? $this->contactService->getContactWithParent($id) : $this->contactService->getAllContacts(); 
        $filtered_meta_data = $this->contactService->filterMetaData(site_settings('contact_meta_data'), StatusEnum::TRUE->status());
        $groups             = Group::whereNull("user_id")->where("status", StatusEnum::TRUE->status())->pluck("name", "id")->toArray();
        return view('admin.contact.index', compact('title', 'contacts', 'filtered_meta_data', 'groups'));
    }
    
    public function unsubscribe($id = null): View {
        
        Session::put("menu_active", true);
        $title              = translate("Unsubscribe List");
        $contacts           = $id ? $this->contactService->getContactWithParent($id) : $this->contactService->getAllInactiveContacts(); 
        $filtered_meta_data = $this->contactService->filterMetaData(site_settings('contact_meta_data'), StatusEnum::TRUE->status());
        $groups             = Group::whereNull("user_id")->where("status", StatusEnum::TRUE->status())->pluck("name", "id")->toArray();
        return view('admin.contact.unsubscribe', compact('title', 'contacts', 'filtered_meta_data', 'groups'));
    }
    
    
    
    
    
    
     
    
    public function optOut($id = null): View {
        
        Session::put("menu_active", true);
        $title              = translate("Opt-Out");
        $contacts           = $id ? $this->contactService->getContactWithParent($id) : $this->contactService->getAllOptOutContacts(); 
        // dd($contacts);
        $filtered_meta_data = $this->contactService->filterMetaData(site_settings('contact_meta_data'), StatusEnum::TRUE->status());
        $groups             = Group::whereNull("user_id")->where("status", StatusEnum::TRUE->status())->pluck("name", "id")->toArray();
        return view('admin.contact.optOut', compact('title', 'contacts', 'filtered_meta_data', 'groups'));
    }
    
    
    
   

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function create():View {

        Session::put("menu_active", true);
        $title              = translate("Add Contacts");
        $filtered_meta_data = $this->contactService->filterMetaData(site_settings('contact_meta_data'), StatusEnum::TRUE->status());
        $groups = Group::whereNull("user_id")->where("status", StatusEnum::TRUE->status())->pluck("name", "id")->toArray();
        return view('admin.contact.create', compact('title', 'filtered_meta_data', 'groups'));
    }

    /**
     *
     * @param ContactRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
     
     public function saveSingleContact($user_id, $data) {
        $status = 'success';
        $message = translate('Single contact saved successfully');
        
        try {
            // Check if the email already exists for the given user_id
            
            // $existingContact = $this->getContactByEmailAndUserId($data['email_contact'], $user_id);
    
            // if ($existingContact) {
            //     // Return error if the email already exists for the user
            //     throw new \Exception(translate('Error: Email already exists.'));
            // }
    
            $data["user_id"]   = $user_id;
            $data["meta_data"] = $this->contactMetaData($data);
            unset($data["single_contact"]);
    
            // Insert or update the contact data
            $this->updateOrInsert($data);
            $this->updateGroupMetaData($data);
    
        } catch (\Exception $e) {
            $status = 'error';
            $message = translate('System Error: ') . $e->getMessage();
        }
    
        return [
            $status,
            $message
        ];
    }

    // public function getContactByEmailAndUserId($email, $user_id == null) {
    // // Example query (adjust according to your actual database/ORM):
    // return 
    //          Contact::where('user_id', $user_id)
    //          ->where('email_contact', $email)
    //          ->first();
    // }

    public function save(ContactRequest $request) {
        
        $data = $request->all();
        unset($data["_token"]);
        $data = $this->contactService->contactSave($data);
        return back()->withNotify($data);
    }
    
    public function resubscribe($id) {
        
            if($id){
                    $data = $this->contactService->undo($id);
                    return back()->withNotify(['Contact Active successfully']);
            }
            
            

        
        
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

            $notify = $this->contactService->statusUpdate($request);
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
        
        $status  = 'error';
        $message = 'Something went wrong';

        try {

            list($status, $message) = $this->contactService->deleteContact($request->input('uid'));

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
    public function bulk(Request $request) :RedirectResponse {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {

            list($status, $message) = $this->bulkAction($request, null, [
                "model" => new Contact(),
            ]);
    
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ").$exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }

    /**
     *
     * @param Request $request
     * 
     * @return string $type
     * 
     */
    public function demoFile(string $type = null) {

        $status  = 'error';
        $message = "Something went wrong";
        try {

            return response()->download($this->fileService->generateContactDemo($type, [], true));
        } catch (\Exception $e) {
            
            $status  = 'error';
            $message = translate("Server Error");
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
    public function uploadFile(Request $request) {

        try {

            list($fileName, $filePath) = $this->fileService->UploadContactFile($request->file('file'));
            return response()->json([

                "status" => true, 
                "file_name" => $fileName, 
                "file_path" => $filePath
            ]);
        } catch (\Exception $e) {

            return response()->json([

                "status"  => false, 
                "error" => translate("Server Error")
            ]);
        }
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception $e
     * 
     */
    public function deleteFile(Request $request) {

        $status  = false;
        $message = "Something went wrong";  
        try {

            list($status, $message) = $this->fileService->deleteContactFile($request->input('file_name'));

        } catch (\Exception $e) {

            $message = translate("Server Error");
        }
        return response()->json([

            'status'  => $status, 
            'message' => $message
        ]);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws Exception $e
     * 
     */
    public function parseFile(Request $request) {

        try {
            
            $parsed_data = $this->fileService->parseContactFile($request->input('filePath'));
            return response()->json([
                "data" => $parsed_data
            ]);

        } catch (\Exception $e) {

            return response()->json([

                'error' => translate("Server Error")
            ]);
        }
    }
}
