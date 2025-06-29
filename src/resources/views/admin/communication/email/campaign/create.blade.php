@push("style-include")
    <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush 
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="row gy-4">
                <div class="col-md-5">
                    <div class="page-header-left">
                        <h2>{{ $title }}</h2>
                        <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ translate("Create Campaign") }} </li>
                            </ol>
                        </nav>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="step-wrapper mb-0 justify-content-lg-start justify-content-md-end">
                      <ul class="progress-steps">
                        <li class="step-item activated active">
                          <span>{{ translate("01") }}</span> {{ translate("Setup") }}
                        </li>
                        <li class="step-item">
                          <span>{{ translate("02") }}</span> {{ translate("Schedule") }}
                        </li>
                        <li class="step-item">
                          <span>{{ translate("03") }}</span> {{ translate("Message") }}
                        </li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
      
        <form action="{{route('admin.communication.email.campaign.save', ['type' => 'email'])}}" class="step-content" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="step-content-item active">
            <div class="card">
                <div class="form-header">
                <div class="row g-3 align-items-center">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                    <h4 class="card-title">{{ translate("Campaign Create") }}</h4>
                    </div>
                </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate('From Group')}}</h5>
                            </div>
                                <div class="col-xxl-8 col-lg-9">
                                <div class="row gy-3 align-items-end">
                                    <div class="col-12">
                                    <div class="form-inner">
                                        <label for="contacts" class="form-label">{{ translate("Choose Group") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Only the contact groups with email contact are available") }}">
                                            <i class="ri-question-line"></i>
                                            </span>
                                        </label>
                                        <select class="form-select select2-search" id="contacts" data-placeholder="{{ translate("Choose groups") }}" aria-label="contacts" name="contacts[]" multiple>
                                        <option value=""></option>
                                            @foreach($groups as $group)
                                                
                                                 <option value="{{ $group->id }}"
                                                    @if(collect(old('contacts'))->contains($group->id)) selected @endif>
                                                    {{ $group->name }}
                                        </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <!--<div class="col-12 group-logic d-none">-->
                                    <!--    <div class="form-inner">-->
                                    <!--        <label class="form-label"> {{ translate("Add Logic") }} </label>-->
                                    <!--        <div class="form-inner-switch">-->
                                    <!--            <label class="pointer" for="group_logic" >{{translate('Add Logic to Groups to select specific contacts based on attrbitues')}}</label>-->
                                    <!--            <div class="switch-wrapper mb-1 checkbox-data">-->
                                    <!--                <input type="checkbox" value="true" name="group_logic" id="group_logic" class="switch-input">-->
                                    <!--                <label for="group_logic" class="toggle">-->
                                    <!--                <span></span>-->
                                    <!--                </label>-->
                                    <!--            </div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="form-item group-logic-items mt-3"></div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Campaign Name") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-xl-9">
                            <div class="form-inner">
                            <label for="name" class="form-label">{{ translate("Name") }}
                            </label>
                            <input type="text" id="name" value="{{ old('name') }}" name="name" class="form-control" placeholder="{{ translate("Enter your campaign name") }}" aria-label="name" />
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Choose Gateway") }}</h5>
                          </div>
                          <div class="col-xxl-8 col-lg-9">
                            <div class="row gy-3 align-items-end">
                              <div class="col-12">
                                <div class="form-inner">
                                  <label for="gateways" class="form-label">{{ translate("Choose From an available gateway") }}</label>
                                  <select class="form-select select2-search" id="gateway_id" data-placeholder="{{ translate("Select a gateway") }}" data-show="5" aria-label="gateway_id" name="gateway_id">
                                    <option value=""></option>
                                    <!--<option value="0">{{ translate("Random Rotation") }}</option>-->
                                    <!--<option value="-1">{{ translate("Automatic") }}</option>-->
                                    @foreach($gateways as $type => $gateway)
                                        <optgroup label="{{ ucfirst($type) }}">
                                            @foreach($gateway as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-xxl-10">
                    <div class="form-action justify-content-between">
                        <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                        <button type="button" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="step-content-item">
            <div class="card">
                <div class="form-header">
                <div class="row g-3 align-items-center">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                    <h4 class="card-title">{{ translate("Campaign Create") }}</h4>
                    </div>
                </div>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Schedule At") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-lg-9">
                            <div class="form-inner">
                            <label for="ChooseGateway" class="form-label">{{ translate("Choose Date & Time") }}
                            </label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control singleDateTimePicker" placeholder="{{ translate("Select schedule time") }}" value="{{ old('schedule_at') }}" name="schedule_at"/>
                                <span class="input-group-text calendar-icon" id="filterByDate">
                                <i class="ri-calendar-2-line"></i>
                                </span>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                        <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Delivery Logic") }}</h5>
                        </div>
                        <div class="col-xxl-8 col-xl-9">
                            <div class="form-inner">
                            <label for="ChooseGateway" class="form-label">{{ translate("Repetition") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Configure the campaign logic, if you dont want this campaign to repeat again then simply keep '0' in the input field") }}">
                                <i class="ri-question-line"></i>
                                </span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">{{ translate("Deliver campaigns in every ") }}</span>
                                <input type="number" value="{{ old('repeat_time') }}" name="repeat_time" id="repeat_time" aria-label="{{ translate("Repeat Times") }}" class="form-control" placeholder="{{ translate("Amount of times you want this campaign to repeat") }}" min="0" value="0"/>
                                
                                <select id="repeat_format" class="form-select" aria-label="{{ translate("Repeat Format") }}" name="repeat_format" disabled>
                                <option value="-1" disabled selected>{{ translate("Select a repeat format") }}</option>
                                @foreach(\App\Enums\CampaignRepeatEnum::toArray() as $key => $value)
                                    <option value="{{ $value }}">{{ ucfirst(transformToCamelCase($key)) }}</option>
                                @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-xxl-10">
                    <div class="form-action justify-content-between">
                        <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                        <button type="button" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="step-content-item">
            <div class="card">
                <div class="form-header">
                <h4 class="card-title">{{ translate("Write Campaign Message") }}</h4>
                </div>
                <div class="card-body pt-0">
                    <div class="form-element">
                        <div class="row gy-3">
                          <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Sender Information") }}</h5>
                          </div>
                          <div class="col-xxl-7 col-lg-9">
                           
                            <div class="form-inner mb-3">
                              <label for="email_from_name" class="form-label">{{ translate("Email From Name") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Provide a from name which will displayed as the nameof the source') }}">
                                  <i class="ri-question-line"></i>
                                  </span>
                              </label>
                              <input type="text" class="form-control" value="{{ old('email_from_name') }}"  name="email_from_name" id="email_from_name" placeholder="{{ translate("Enter email from name") }}" aria-label="email_from_name" autocomplete=""/>
                            </div>
                            
                            <div class="form-inner mb-3">
                              <label for="reply_to_address" class="form-label">{{ translate("Reply To Address") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Reply to email address helps recipient to communcate with you') }}">
                                  <i class="ri-question-line"></i>
                                  </span>
                              </label>
                              <input type="email" class="form-control"  value="{{ old('reply_to_address') }}" name="reply_to_address" id="reply_to_address" placeholder="{{ translate("Enter reply to email address") }}" aria-label="reply_to_address" autocomplete=""/>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form-element">
                        <div class="row gy-3">
                            <div class="col-xxl-2 col-xl-3">
                            <h5 class="form-element-title">{{ translate("Message body") }}</h5>
                            </div>
                            <div class="col-xxl-7 col-lg-9">
                            <div class="form-inner mb-3">
                                <label for="subject" class="form-label">{{ translate("Subject") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate('Provide a subject for the mail so it doesn\'t go into the spam') }}">
                                    <i class="ri-question-line"></i>
                                    </span>
                                </label>
                                <input type="text" class="form-control" value="{{ old('message.subject') }}" name="message[subject]" id="email_contact" placeholder="{{ translate("Enter email subject") }}" aria-label="subject" autocomplete=""/>
                            </div>
                            <div class="form-inner position-relative speech-to-text" id="messageBox">
                            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 mb-2">
                                <label for="message" class="form-label mb-0">{{ translate("Write message") }}</label>
                                <!--<label for="message" class="text-counter">{{ translate("If your message is over 160 characters. Longer messages will be sent as multiple SMS segments, which may impact your costs.") }}</label>-->
                       
                                <button class="i-btn btn--sm p-0 bg-transparent text-primary available-template" id="selectEmailTemplate" type="button">
                                <i class="ri-layout-fill fs-5"></i>{{ translate("Use Template") }}</button>
                            </div>
                            
                            <textarea class="form-control" name="message[message_body]" id="message" rows="5" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nIf Contact is being selected from a group then to mention First Name Use {{". 'first_name' ."}} \nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}"@endphp"></textarea>
                            
                            
                            <div class="voice-icon">
                                <button type="button" class="icon-btn btn-sm primary-soft circle hover" id="text-to-speech-icon">
                                <i class="ri-mic-fill"></i>
                                <span class="tooltiptext"> {{ translate("Voice") }} </span>
                                </button>
                            </div>
                            </div>
                            
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-action justify-content-between">
                        <button type="button" class="i-btn btn--dark outline btn--md step-back-btn"> {{ translate("Previous") }} </button>
                        <button type="submit" class="i-btn btn--primary btn--md step-next-btn"> {{ translate("Next") }} </button>
                        <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
                    </div>
                </div>
            </div>
            </div>
      </form>
    </div>
</main>

@endsection
@section('modal')
<div class="modal fade" id="availableTemplate" tabindex="-1" aria-labelledby="availableTemplate" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"> {{ translate("SMS Templates") }} </h5>
              <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                  <i class="ri-close-large-line"></i>
              </button>
          </div>
          <div class="modal-body modal-md-custom-height">
              <div class="row g-4">
                <div class="col-12">
                  <div class="form-inner">
                    <label for="chooseTemplate" class="form-label">{{ translate("Available Templates") }}</label>
                    <select class="form-select select2-search" id="chooseTemplate" data-placeholder="{{ translate("Choose an SMS template") }}" aria-label="chooseTemplate">
                      <option value=""></option>
                      @foreach($templates as $template)
                        <option value="{{ json_encode($template->template_data) }}">{{ $template->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
  
                <div class="template-data"></div>
              </div>
          </div>
          
          <div class="modal-footer">
              <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
              <button type="submit" id="saveTemplateButton" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
          </div>
        </div>
    </div>
</div>
  
<div class="modal fade" id="globalModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="modal-size" class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal-body">

            </div>
        </div>
    </div>
</div>
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
  <script src="{{asset('assets/theme/global/js/campaign/sms/stage-step.js')}}"></script>
  <script src="{{asset('assets/theme/global/js/template.js') }}"></script>
  <script src="{{asset('assets/theme/global/js/BeePlugin.js') }}"></script>
@endpush


@push('script-push')

<script>
	"use strict";
    select2_search($('.select2-search').data('placeholder'));
    ck_editor("#message");

    $(document).ready(function() {
        
        const modal = $('#globalModal');
        $(document).on('click','#use-template',function(e){
            var html  = $(this).attr('data-html')
            // const domElement = document.querySelector( '.ck-editor__editable' );
            // const emailEditorInstance = domElement.ckeditorInstance;
            // emailEditorInstance.setData( html );
            const insertHtmlButton = document.querySelector('[data-cke-tooltip-text="Insert HTML"]');
            if (insertHtmlButton) {
                insertHtmlButton.click(); // Step 1: Open the HTML Embed dialog
        
                // Step 2: Wait a bit for the dialog to render
                setTimeout(() => {
                    // Step 3: Get the textarea inside the embed dialog
                    const htmlTextarea = document.querySelector('.raw-html-embed__source');
        
                    if (htmlTextarea) {
                        // Step 4: Insert your raw HTML content
                        htmlTextarea.value = html;
        
                        // Step 5: Fire an input event to trigger CKEditor update
                        htmlTextarea.dispatchEvent(new Event('input', { bubbles: true }));
        
                        // Step 6: Find and click the Save button in the embed dialog
                        const saveButton = document.querySelector('.raw-html-embed__save-button');
                        if (saveButton) saveButton.click();
                        else console.warn('Save button not found.');
                    } else {
                        console.warn('HTML embed textarea not found.');
                    }
                }, 400); // wait for the modal to be fully visible
            } else {
                console.warn('Insert HTML button not found.');
            }
            modal.modal('hide');
        })
       

        $(document).on('click','#selectEmailTemplate',function(e){
            
            $("#selectEmailTemplate").html('{{translate("Template Loading...")}}');
            appendTemplate()
            e.preventDefault()
        })

        function  appendTemplate(){
			$.ajax({
				method:"GET",
				url:"{{ route('admin.template.email.fetch') }}",
				dataType:'json'
			}).then(response=>{
				$("#selectEmailTemplate").html('{{translate("Use Email Template")}}');
				appendModalData(response.view)
			})
        }

		   // append modal data method start
		function appendModalData(view){
			$('#modal-title').html(`{{translate('Pre Build Template')}}`)
			var html = `
				<div class="modal-body">
				   ${view}
				</div>
			`
			$('#modal-body').html(html)
			modal.modal('show');
		}
      
        function enableSelect() {
            $('#repeat_format').prop('disabled', false);
            $('#repeat_format').attr('name', 'repeat_format'); 
        }

        function disableSelect() {
            $('#repeat_format').prop('disabled', true);
            $('#repeat_format').removeAttr('name');
            $('#repeat_format').val('-1');
        }

        function validateInput() {
            let repeatTime = parseInt($('#repeat_time').val(), 10);
            if (repeatTime < 0 || isNaN(repeatTime)) {
                $('#repeat_time').val(0);
                repeatTime = 0;
            }
            if (repeatTime > 0) {
                enableSelect();
            } else {
                disableSelect();
            }
        }

        $('#repeat_time').on('input', function() {
            validateInput();
        });
        validateInput();

        function handleMergedAttributes(attributes) {

            var initialAttributeOptions = $(".group-logic-items").html();
            $('#group_id').on('select2:unselect', function (e) {
                $('.group-logic').addClass('d-none');
                resetAttributeOptions();

                function resetAttributeOptions() {

                    $(".group-logic-items").html(initialAttributeOptions);
                }
            });
            $(".group-logic").removeClass("d-none");
            $('#group_logic').change(function() {
                var selectedAttributeValue;
                if ($(this).is(':checked')) {

                    $(".group-logic-items").html(`
                        <div class="row">
                            <div class="col-md-6">
                                <label for="attribute_name" class="form-label">Attributes<sup class="text-danger">*</sup></label>
                                <select class="form-select repeat-scale" required name="attribute_name" id="attribute_name">
                                    <option selected disabled>-- Select an Attribute --</option>
                                    <option value="sms_contact">SMS Contact Number</option>
                                    <option value="whatsapp_contact">WhatsApp Contact Number</option>
                                    <option value="email_contact">Email Address</option>
                                    <option value="first_name">First Name</option>
                                    <option value="last_name">Last Name</option>
                                    ${getAttributesOptionsHTML(attributes)}
                                </select>
                            </div>
                            <div class="col-md-6" id="logic-input-container"></div>
                        </div>
                    `);

                    $('#attribute_name').change(function() {
                        selectedAttributeValue = $(this).val();

                        $('#logic-input-container').html(`
                            ${getLogicInputHTML(selectedAttributeValue)}
                        `);
                    });
                } else {
                    $(".group-logic-items").html('');
                }
            });
            function getAttributesOptionsHTML(attributes) {
                return Object.keys(attributes)
                    .map(attribute => `<option value="${attribute}::${attributes[attribute]}">${formatAttributeName(attribute)}</option>`)
                    .join('');
            }
            function formatAttributeName(attribute) {

                return attribute.replace(/_/g, ' ').replace(/\b\w/g, firstLetter => firstLetter.toUpperCase());
            }

            function getLogicInputHTML(attribute) {

            var value = attributes[attribute.split("::")[0]];

            if (value && value != undefined) {

                if (value == {{\App\Models\GeneralSetting::DATE}}) {

                    return `<div class="row"><div title="Only the contacts with this date in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Select a Date") }}<sup class="text-danger">*</sup></label>
                                <input type="datetime-local" class="date-picker form-control" name="logic" id="logic"></div>
                                <div title="Only the contacts within this range in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Select The Range") }}<sup class="text-danger">*</sup></label>
                                <input type="datetime-local" class="date-picker form-control" name="logic_range" id="logic_range"></div></div>`;

                } else if (value == {{\App\Models\GeneralSetting::BOOLEAN}}) {

                    return `<label for="attribute_name" class="form-label">{{ translate("Conditions") }}<sup class="text-danger">*</sup></label>
                            <select class="form-select repeat-scale" required name="logic" id="logic">
                                <option selected disabled>${('-- Select a Logic --')}</option>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>`;
                } else if (value == {{\App\Models\GeneralSetting::NUMBER}}) {

                    return `<div class="row"><div title="Only the contacts with this number in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" name="logic" id="logic" placeholder="Enter a number"></div
                            <div class="row"><div title="Only the contacts within this range in the '${attribute.split("::")[0]}' attribute will be selected for this Campaign" class="col-md-6"><label for="attribute_name" class="form-label">{{ translate("Range") }}<sup class="text-danger">*</sup></label>
                            <input type="number" class="form-control" name="logic_range" id="logic_range" placeholder="Enter a number"></div`;
                } else if (value == {{\App\Models\GeneralSetting::TEXT}}) {

                    return `<label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" name="logic" id="logic" placeholder="Enter text">`;
                }
            } else {

                return `<label for="attribute_name" class="form-label">{{ translate("Contains") }}<sup class="text-danger">*</sup></label>
                    <input type="text" class="form-control" name="logic" id="logic" placeholder="Enter text">`;
            }
            }
        }

        function handleEmptyContacts(message) {

            $('.group-logic').addClass("d-none");
            $('.group-logic-items').addClass("d-none");
            $('input[name=logic]').removeAttr('name');
            $('input[name=group_logic]').removeAttr('name');
            $('select[name=attribute_name]').removeAttr('name');
            $('select[name=attribute_name]').prop('disabled', true);
            notify('info', message);
        }

        $('#contacts').change(function() {

            var selectedValues = $(this).val();
            var channelValue = '{{ $type }}';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (selectedValues) {
                $.ajax({
                    url: '{{ route("admin.contact.group.fetch", ["type" => "meta_data"]) }}',
                    type: 'POST',
                    data: {
                        group_ids: selectedValues,
                        channel: "email",
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {

                        if (response.status == true) {

                            $('.group-logic').removeClass("d-none");
                            $('.group-logic-items').removeClass("d-none");
                            $('input[id=logic]').attr('name', 'logic');
                            $('input[id=group_logic]').attr('name', 'group_logic');
                            $('select[id=attribute_name]').attr('name', 'attribute_name');
                            $('select[name=attribute_name]').prop('disabled', false);
                            handleMergedAttributes(response.merged_attributes);
                        } else {

                            handleEmptyContacts(response.message);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
<script>
/**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/?redirect=portal#installation/NoRgLANARATAdAVjgBitEIDsAOMCvLLYIzYxgDMCAbCBQJz0XVhYb3HVeNcLbZooAUwB2aZBFAQJEkBDnIAutCG0AZgCMYIKIqA=
 */

const {
	ClassicEditor,
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	Autosave,
	Bold,
	CKBox,
	CKBoxImageEdit,
	CloudServices,
	Emoji,
	Essentials,
	FontBackgroundColor,
	FontColor,
	FontFamily,
	FontSize,
	GeneralHtmlSupport,
	Heading,
	ImageEditing,
	ImageInline,
	ImageInsert,
	ImageInsertViaUrl,
	ImageResize,
	ImageStyle,
	ImageTextAlternative,
	ImageToolbar,
	ImageUpload,
	ImageUtils,
	Indent,
	IndentBlock,
	Italic,
	Link,
	List,
	ListProperties,
	Mention,
	Paragraph,
	PasteFromOffice,
	PictureEditing,
	PlainTableOutput,
	RemoveFormat,
	Strikethrough,
	Style,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableLayout,
	TableProperties,
	TableToolbar,
	TextTransformation,
	Underline
} = window.CKEDITOR;
const {
	getEmailInlineStylesTransformations,
	AIAssistant,
	EmailConfigurationHelper,
	ExportInlineStyles,
	MergeFields,
	OpenAITextAdapter,
	PasteFromOfficeEnhanced,
	SourceEditingEnhanced,
	Template
} = window.CKEDITOR_PREMIUM_FEATURES;

const LICENSE_KEY =
	'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDc2MTI3OTksImp0aSI6IjdkMWUwZjkzLTg3ZDUtNDdkNy05M2I1LWY4OWEwYWVlNzY3ZSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6IjA1YmE1NjRjIn0.1p28A42wchePbf-AN-wl5IH4z7mZRlbekds1pj_sKgDKCMtFUqtSFKQtGkHaRdvfeV7gkFzZdoESXzON_UUoeg';

/**
 * USE THIS INTEGRATION METHOD ONLY FOR DEVELOPMENT PURPOSES.
 *
 * This sample is configured to use OpenAI API for handling AI Assistant queries.
 * See: https://ckeditor.com/docs/ckeditor5/latest/features/ai-assistant/ai-assistant-integration.html
 * for a full integration and customization guide.
 */
const AI_API_KEY = LICENSE_KEY;

const CLOUD_SERVICES_TOKEN_URL =
	'https://vpzbz06sapit.cke-cs.com/token/dev/7e8b97f8812712906b4abfed3dbc81a18509c97bcf631a936b21bc7f1c30?limit=10';

const DEFAULT_HEX_COLORS = [
	{ color: '#000000', label: 'Black' },
	{ color: '#4D4D4D', label: 'Dim grey' },
	{ color: '#999999', label: 'Grey' },
	{ color: '#E6E6E6', label: 'Light grey' },
	{ color: '#FFFFFF', label: 'White', hasBorder: true },
	{ color: '#E65C5C', label: 'Red' },
	{ color: '#E69C5C', label: 'Orange' },
	{ color: '#E6E65C', label: 'Yellow' },
	{ color: '#C2E65C', label: 'Light green' },
	{ color: '#5CE65C', label: 'Green' },
	{ color: '#5CE6A6', label: 'Aquamarine' },
	{ color: '#5CE6E6', label: 'Turquoise' },
	{ color: '#5CA6E6', label: 'Light blue' },
	{ color: '#5C5CE6', label: 'Blue' },
	{ color: '#A65CE6', label: 'Purple' }
];

const editorConfig = {
	toolbar: {
		items: [
			'insertMergeField',
			'previewMergeFields',
			'|',
			'aiCommands',
			'aiAssistant',
			'|',
			'sourceEditingEnhanced',
			'|',
			'heading',
			'style',
			'|',
			'fontSize',
			'fontFamily',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'bold',
			'italic',
			'underline',
			'|',
			'link',
			'insertImage',
			'insertTable',
			'insertTableLayout',
			'|',
			'alignment',
			'|',
			'bulletedList',
			'numberedList',
			'outdent',
			'indent'
		],
		shouldNotGroupWhenFull: false
	},
	plugins: [
		AIAssistant,
		Alignment,
		Autoformat,
		AutoImage,
		AutoLink,
		Autosave,
		Bold,
		CKBox,
		CKBoxImageEdit,
		CloudServices,
		EmailConfigurationHelper,
		Emoji,
		Essentials,
		ExportInlineStyles,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		GeneralHtmlSupport,
		Heading,
		ImageEditing,
		ImageInline,
		ImageInsert,
		ImageInsertViaUrl,
		ImageResize,
		ImageStyle,
		ImageTextAlternative,
		ImageToolbar,
		ImageUpload,
		ImageUtils,
		Indent,
		IndentBlock,
		Italic,
		Link,
		List,
		ListProperties,
		Mention,
		MergeFields,
		OpenAITextAdapter,
		Paragraph,
		PasteFromOffice,
		PasteFromOfficeEnhanced,
		PictureEditing,
		PlainTableOutput,
		RemoveFormat,
		SourceEditingEnhanced,
		Strikethrough,
		Style,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableLayout,
		TableProperties,
		TableToolbar,
		Template,
		TextTransformation,
		Underline
	],
	ai: {
		openAI: {
			requestHeaders: {
				Authorization: 'Bearer ' + AI_API_KEY
			}
		}
	},
	cloudServices: {
		tokenUrl: CLOUD_SERVICES_TOKEN_URL
	},
	exportInlineStyles: {
		stylesheets: [
			/* This path should point to the content stylesheets on your assets server. */
			/* See: https://ckeditor.com/docs/ckeditor5/latest/features/export-with-inline-styles.html */
			/*'./style.css', */
			/* Export inline styles needs access to stylesheets that style the content. */
			'https://cdn.ckeditor.com/ckeditor5/45.0.0/ckeditor5.css',
			'https://cdn.ckeditor.com/ckeditor5-premium-features/45.0.0/ckeditor5-premium-features.css'
		],
		transformations: getEmailInlineStylesTransformations()
	},
	fontBackgroundColor: {
		colorPicker: {
			format: 'hex'
		},
		colors: DEFAULT_HEX_COLORS
	},
	fontColor: {
		colorPicker: {
			format: 'hex'
		},
		colors: DEFAULT_HEX_COLORS
	},
	fontFamily: {
		supportAllValues: true
	},
	fontSize: {
		options: [10, 12, 14, 'default', 18, 20, 22],
		supportAllValues: true
	},
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
			{
				model: 'heading5',
				view: 'h5',
				title: 'Heading 5',
				class: 'ck-heading_heading5'
			},
			{
				model: 'heading6',
				view: 'h6',
				title: 'Heading 6',
				class: 'ck-heading_heading6'
			}
		]
	},
	htmlSupport: {
		allow: [
			{
				name: /^(div|table|tbody|tr|td|span|img|h1|h2|h3|p|a)$/,
				styles: true,
				attributes: true,
				classes: true
			}
		]
	},
	image: {
		toolbar: [
			'imageTextAlternative',
			'|',
			'imageStyle:inline',
			'imageStyle:alignLeft',
			'imageStyle:alignRight',
			'|',
			'resizeImage',
			'|',
			'ckboxImageEdit'
		],
		styles: {
			options: ['inline', 'alignLeft', 'alignRight']
		}
	},
	initialData:
	'',
	licenseKey: LICENSE_KEY,
	link: {
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	list: {
		properties: {
			styles: true,
			startIndex: true,
			reversed: false
		}
	},
	mention: {
		feeds: [
			{
				marker: '@',
				feed: [
					/* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
				]
			}
		]
	},
	menuBar: {
		isVisible: true
	},
	mergeFields: {
		/* Read more: https://ckeditor.com/docs/ckeditor5/latest/features/merge-fields.html#configuration */
	},
	placeholder: 'Type or paste your content here!',
	style: {
		definitions: [
			{
				name: 'Article category',
				element: 'h3',
				classes: ['category']
			},
			{
				name: 'Title',
				element: 'h2',
				classes: ['document-title']
			},
			{
				name: 'Subtitle',
				element: 'h3',
				classes: ['document-subtitle']
			},
			{
				name: 'Info box',
				element: 'p',
				classes: ['info-box']
			},
			{
				name: 'CTA Link Primary',
				element: 'a',
				classes: ['button', 'button--green']
			},
			{
				name: 'CTA Link Secondary',
				element: 'a',
				classes: ['button', 'button--black']
			},
			{
				name: 'Marker',
				element: 'span',
				classes: ['marker']
			},
			{
				name: 'Spoiler',
				element: 'span',
				classes: ['spoiler']
			}
		]
	},
	table: {
		contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'],
		tableProperties: {
			borderColors: DEFAULT_HEX_COLORS,
			backgroundColors: DEFAULT_HEX_COLORS
		},
		tableCellProperties: {
			borderColors: DEFAULT_HEX_COLORS,
			backgroundColors: DEFAULT_HEX_COLORS
		}
	},
	template: {
		definitions: [
			{
				title: 'Introduction',
				description: 'Simple introduction to an article',
				icon: '<svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">\n    <g id="icons/article-image-right">\n        <rect id="icon-bg" width="45" height="45" rx="2" fill="#A5E7EB"/>\n        <g id="page" filter="url(#filter0_d_1_507)">\n            <path d="M9 41H36V12L28 5H9V41Z" fill="white"/>\n            <path d="M35.25 12.3403V40.25H9.75V5.75H27.7182L35.25 12.3403Z" stroke="#333333" stroke-width="1.5"/>\n        </g>\n        <g id="image">\n            <path id="Rectangle 22" d="M21.5 23C21.5 22.1716 22.1716 21.5 23 21.5H31C31.8284 21.5 32.5 22.1716 32.5 23V29C32.5 29.8284 31.8284 30.5 31 30.5H23C22.1716 30.5 21.5 29.8284 21.5 29V23Z" fill="#B6E3FC" stroke="#333333"/>\n            <path id="Vector 1" d="M24.1184 27.8255C23.9404 27.7499 23.7347 27.7838 23.5904 27.9125L21.6673 29.6268C21.5124 29.7648 21.4589 29.9842 21.5328 30.178C21.6066 30.3719 21.7925 30.5 22 30.5H32C32.2761 30.5 32.5 30.2761 32.5 30V27.7143C32.5 27.5717 32.4391 27.4359 32.3327 27.3411L30.4096 25.6268C30.2125 25.451 29.9127 25.4589 29.7251 25.6448L26.5019 28.8372L24.1184 27.8255Z" fill="#44D500" stroke="#333333" stroke-linejoin="round"/>\n            <circle id="Ellipse 1" cx="26" cy="25" r="1.5" fill="#FFD12D" stroke="#333333"/>\n        </g>\n        <rect id="Rectangle 23" x="13" y="13" width="12" height="2" rx="1" fill="#B4B4B4"/>\n        <rect id="Rectangle 24" x="13" y="17" width="19" height="2" rx="1" fill="#B4B4B4"/>\n        <rect id="Rectangle 25" x="13" y="21" width="6" height="2" rx="1" fill="#B4B4B4"/>\n        <rect id="Rectangle 26" x="13" y="25" width="6" height="2" rx="1" fill="#B4B4B4"/>\n        <rect id="Rectangle 27" x="13" y="29" width="6" height="2" rx="1" fill="#B4B4B4"/>\n        <rect id="Rectangle 28" x="13" y="33" width="16" height="2" rx="1" fill="#B4B4B4"/>\n    </g>\n    <defs>\n        <filter id="filter0_d_1_507" x="9" y="5" width="28" height="37" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">\n            <feFlood flood-opacity="0" result="BackgroundImageFix"/>\n            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>\n            <feOffset dx="1" dy="1"/>\n            <feComposite in2="hardAlpha" operator="out"/>\n            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.29 0"/>\n            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1_507"/>\n            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1_507" result="shape"/>\n        </filter>\n    </defs>\n</svg>\n',
				data: "<h2>Introduction</h2><p>In today's fast-paced world, keeping up with the latest trends and insights is essential for both personal growth and professional development. This article aims to shed light on a topic that resonates with many, providing valuable information and actionable advice. Whether you're seeking to enhance your knowledge, improve your skills, or simply stay informed, our comprehensive analysis offers a deep dive into the subject matter, designed to empower and inspire our readers.</p>"
			}
		]
	}
};

configUpdateAlert(editorConfig);
let emailEditorInstance;
ClassicEditor
  .create(document.querySelector('#editor'), editorConfig)
  .then(editor => {
    emailEditorInstance = editor;

    // Optional: You can load initial HTML here
    // emailEditorInstance.setData('<p>Hello World</p>');
  })
  .catch(error => {
    console.error(error);
  });
/**
 * This function exists to remind you to update the config needed for premium features.
 * The function can be safely removed. Make sure to also remove call to this function when doing so.
 */
function configUpdateAlert(config) {
	if (configUpdateAlert.configUpdateAlertShown) {
		return;
	}

	const isModifiedByUser = (currentValue, forbiddenValue) => {
		if (currentValue === forbiddenValue) {
			return false;
		}

		if (currentValue === undefined) {
			return false;
		}

		return true;
	};

	const valuesToUpdate = [];

	configUpdateAlert.configUpdateAlertShown = true;

	if (!isModifiedByUser(config.licenseKey, LICENSE_KEY)) {
		valuesToUpdate.push('LICENSE_KEY');
	}

	if (!isModifiedByUser(config.ai?.openAI?.requestHeaders?.Authorization, 'Bearer ' + AI_API_KEY)) {
		valuesToUpdate.push('AI_API_KEY');
	}

	if (!isModifiedByUser(config.cloudServices?.tokenUrl, CLOUD_SERVICES_TOKEN_URL)) {
		valuesToUpdate.push('CLOUD_SERVICES_TOKEN_URL');
	}

// 	if (valuesToUpdate.length) {
// 		window.alert(
// 			[
// 				'Please update the following values in your editor config',
// 				'to receive full access to Premium Features:',
// 				'',
// 				...valuesToUpdate.map(value => ` - ${value}`)
// 			].join('\n')
// 		);
// 	}
}

</script>
@endpush
