<?php

namespace App\Service\Admin\Contact;

use App\Enums\ContactAttributeEnum;
use App\Models\Group;
use App\Models\Contact;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class ContactGroupService
{ 
    public function getAllGroups($user_id = null) {

        return Group::search(['name'])
                        ->where("user_id", $user_id)
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function getGroupWithChild($id, $user_id = null) {
        
        return Group::search(['name'])
                        ->where('id', $id)
                        ->where("user_id", $user_id)
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function statusUpdate($request) {
        
        try {
            $status  = true;
            $reload  = false;
            $message = translate('Group status updated successfully');
            $group = Group::where("id",$request->input('id'))->first();
            $column  = $request->input("column");
            
            if($request->value == StatusEnum::TRUE->status()) {
                
                $group->status = StatusEnum::FALSE->status();
                $group->update();
            } else {

                $group->status = StatusEnum::TRUE->status();
                $group->update();
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
    
    
    
    public function updateOrCreate($data, $user_id = null) {

        if($user_id) {

            $data['user_id'] = $user_id;
        }
        $file_name = Str::slug($data['name']);
        $data['file_name'] = $file_name;
        
        // dd($data);
        $group = Group::updateOrCreate([
                    
            "uid" => $data["uid"] ?? null

        ], $data);
        
        $baseUrl = url('/');
        $groupId = $group->id;
        
        $directoryPath = storage_path('app/public/groups');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }
        $filePath = $directoryPath . '/' . $file_name . '.html';
        
        if (!File::exists($filePath)) {
                $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
        /* Body styles */
        .body-style {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        /* Contact form container */
        .contact-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Form heading */
        .form-heading {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Input fields */
        .input-field {
            width: 100%;
            padding: 10px 3px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        /* Error message */
        .error-message {
            display: none;
            color: red;
            margin-top: 5px;
        }
        /* Submit button */
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        /* Success and error response messages */
        .alert-success {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-top: 15px;
        }
        .alert-error {
            padding: 10px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body class="body-style">

<div class="contact-form">
    <h2 class="form-heading">Contact Form</h2>
    <form id="contactForm">
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" required class="input-field">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" required class="input-field">
        </div>
        <div>
            <label for="phone">Phone</label>
            <input type="text" maxLength="14" placeholder="(000) 000-0000" id="phone" required class="input-field">
            <div id="phoneError" class="error-message"></div>
        </div>
        <button type="submit" id="submitButton" class="submit-button">Submit</button>
    </form>
    <div id="responseMessage"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#phone').on('input', function() {
            // Get the current value of the input
            var input = $(this).val();
    
            // Remove all non-digit characters
            var cleaned = input.replace(/\D/g, '');
    
            // Format the cleaned number
            var formatted = '';
    
            if (cleaned.length > 0) {
                formatted += '(' + cleaned.substring(0, 3);
            }
            if (cleaned.length >= 4) {
                formatted += ') ' + cleaned.substring(3, 6);
            }
            if (cleaned.length >= 7) {
                formatted += '-' + cleaned.substring(6, 10);
            }
    
            // Set the formatted value back to the input
            $(this).val(formatted);
        });    
        $('#contactForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            // Clear previous error messages
            $('#phoneError').hide().text('');
            
            // Validate phone number
            var phone = $('#phone').val();
            var phoneRegex = /^\(\d{3}\) \d{3}-\d{4}$/; // Use regular expression literal
            
            if (!phoneRegex.test(phone)) { // Use test() method on the regex
                $('#phoneError').text('Please enter phone number (123) 456-7890').show();
                return; // Stop the submission
            }

            // Change button to loading state
            var submitButton = $('#submitButton');
            submitButton.prop('disabled', true).text('Loading...');

            // Gather form data
            var formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                phone: phone
            };
            
            
            
            // Make AJAX request
            $.ajax({
                       
                url: `${baseUrl}/api/contacts-form/${groupId}`, // Replace with actual groupId
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function(response) {
                    $('#responseMessage').html('<div class="alert-success">' + response.message + '</div>');
                    console.log('Response:', response); // Log the response
                },
                error: function(xhr, status, error) {
                    $('#responseMessage').html('<div class="alert-error">' + xhr.responseJSON.message + '</div>');
                    console.error('Error:', error); // Log the error
                },
                complete: function() {
                    // Reset button to original state
                    submitButton.prop('disabled', false).text('Submit');
                }
            });
        });
    });
</script>
</body>
</html>


HTML;

                File::put($filePath, $htmlContent); 
            }
            
            
        
        
    }


    /**
     * 
     * @param string $uid
     *
     * @return Group $group
     */
    public function fetchWithUid(string $uid) {

       return Group::where("uid", $uid)->first();
    }

    /**
     * 
     * @param string $id
     *
     * @return Group $group
     */
    public function fetchWithId(string $uid) {

       return Group::where("uid", $uid)->first();
    }

    /**
     * 
     * @param string $uid
     *
     * @return array
     */
    public function deleteGroup(string $uid): array {

        $group = $this->fetchWithUid($uid);
        if($group) {
            $group->delete();
            Contact::whereNull('user_id')->where('group_id', $group->id)->delete();
            $status   = 'success';
            $message = translate("Group ").$group->name.translate(' has been deleted successfully from admin panel');
        } else {

            $status  = 'error';
            $message = translate("Group couldn't be found"); 
        }
        return [
            $status, 
            $message
        ];
    }

    public function retrieveContacts($type, $contact_groups, $group_logic = null, $meta_name = null, $logic = null, $logic_range = null, $user_id = null) {

        $meta_data = [];
        $contact = Contact::query();
        $contact->whereIn('group_id', $contact_groups);

        if ($group_logic) {
        
            if (strpos($meta_name, "::") !== false) {

                $attributeParts = explode("::", $meta_name);
                $attributeType  = $attributeParts[1];
                
                if ($attributeType == ContactAttributeEnum::DATE->value) {

                    $startDate = Carbon::parse($logic);
        
                    if ($logic_range) {

                        $endDate = Carbon::parse($logic_range);
                        $contact = $contact->get()->filter(function ($contact) use ($startDate, $endDate, $attributeParts) {

                            $attr = Carbon::parse($contact->attributes->{$attributeParts[0]}->value);
                            return $attr->between($startDate, $endDate);
                        });
                    } else {

                        $contact = $contact->get()->filter(function ($contact) use ($startDate, $attributeParts) {

                            $attr = Carbon::parse($contact->attributes->{$attributeParts[0]}->value);
                            return $attr->isSameDay($startDate);
                        });
                    }
                } elseif ($attributeType == ContactAttributeEnum::BOOLEAN->value) {

                    $logicValue = filter_var($logic, FILTER_VALIDATE_BOOLEAN);
                    $contact    = $contact->get()->filter(function ($contact) use ($attributeParts, $logicValue) {

                        $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_BOOLEAN);
                        return $attrValue === $logicValue;
                    });

                } elseif ($attributeType == ContactAttributeEnum::NUMBER->value) { 

                    $numericLogic = filter_var($logic, FILTER_VALIDATE_FLOAT);
                
                    if ($logic_range) {

                        $numericRange = filter_var($logic_range, FILTER_VALIDATE_FLOAT);
                        $contact      = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic, $numericRange) {

                            $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                            return $attrValue >= $numericLogic && $attrValue <= $numericRange;
                        });
                    } else {

                        $contact = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic) {

                            $attrValue = filter_var($contact->attributes->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                            return $attrValue == $numericLogic;
                        });
                    }
                } elseif ($attributeType == ContactAttributeEnum::TEXT->value) { 

                    $textLogic = $logic;
                    $contact   = $contact->get()->filter(function ($contact) use ($attributeParts, $textLogic) {

                        $attrValue = $contact->attributes->{$attributeParts[0]}->value;
                        return stripos($attrValue, $textLogic) !== false;
                    });
                }
            } else {
                $contact->where($meta_name, 'like', "%$logic%");
            }

            
        }
        
        if (!is_null($user_id)) {

            $contact->where('user_id', $user_id);
        } else {

            $contact->whereNull('user_id');
        }
        if ($type) {

            $allContactNumber[] = $contact->pluck("$type".'_contact')->toArray();
            $numberGroupName    = $contact->pluck('first_name', "$type".'_contact')->toArray();
            $contact_ids        = $contact->pluck('id', "$type".'_contact')->toArray();

          

            foreach ($allContactNumber[0] as $number) {
                
                $meta_data[] = [

                    'contact' => $number,
                    'first_name'  => $numberGroupName[$number] ?? null,
                    'id' => $contact_ids[$number] ?? null
                ];
            }

            
        }

        return $meta_data;
    }
}