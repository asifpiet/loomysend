@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
  <style>
    .text-counter {
    font-size: 0.875rem; /* Adjust as needed */
    color: #555; /* Adjust as needed */
    float :right;
    }
    .track-link-container {
        display: flex;
        align-items: center;
        gap: 8px; /* Optional spacing between label and checkbox */
    }
</style>
@endpush 
@extends('admin.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="page-header-left">
          <h2>{{ translate("Send "). $title }}</h2>
          <div class="breadcrumb-wrapper">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> {{ translate("Send "). $title }} </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="form-header">
          <div class="row gy-4 align-items-center">
            <div class="col-xxl-2 col-xl-3">
              <h4 class="card-title">{{ translate("Choose audience") }}</h4>
            </div>
            <div class="col-xxl-10 col-xl-9">
              <div class="form-tab">
                <ul class="nav" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#singleAudience" role="tab" aria-selected="true">
                      <i class="bi bi-person-fill"></i>{{ translate("Single audience") }} </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#groupAudience" role="tab" aria-selected="false" tabindex="-1">
                      <i class="bi bi-people-fill"></i> {{ translate("Group audience") }} </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#importFile" role="tab" aria-selected="false" tabindex="-1">
                      <i class="bi bi-file-earmark-plus-fill"></i> {{ translate("Import file") }} </a>
                  </li> 
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body pt-0">
          <form action="{{route('admin.communication.store', ['type' => 'sms'])}}" method="POST" enctype="multipart/form-data" id="sms_send">
            @csrf
            <div class="tab-content">
              <div class="tab-pane fade show active" id="singleAudience" role="tabpanel">
                  <div class="form-element">
                      <div class="row gy-3">
                      <div class="col-xxl-2 col-xl-3">
                          <h5 class="form-element-title">{{ translate("Recipient Number") }}</h5>
                      </div>
                      <div class="col-xxl-7 col-lg-9">
                          <div class="form-inner">
                              <label for="sms_contact" class="form-label">{{ translate("Phone number") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Provide the sms contact number along with country code") }}">
                                  <i class="ri-question-line"></i>
                                  </span>
                              </label>
                              <input type="number" class="form-control" value="{{ old('contacts') }}" name="contacts" id="sms_contact" placeholder="{{ translate("Enter recipient contact number") }}" aria-label="sms_contact" autocomplete=""/>
                          </div>
                      </div>
                      </div>
                  </div>
              </div>
              
              <div class="tab-pane fade" id="groupAudience" role="tabpanel">
                <div class="form-element">
                  <div class="row gy-3">
                    <div class="col-xxl-2 col-xl-3">
                      <h5 class="form-element-title">{{ translate('From Group')}}</h5>
                      </div>
                        <div class="col-xxl-7 col-lg-9">
                          <div class="row gy-3 align-items-end">
                            <div class="col-12">
                              <div class="form-inner">
                                <label for="contacts" class="form-label">{{ translate("Choose Group") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("Only the contact groups with sms contact are available") }}">
                                    <i class="ri-question-line"></i>
                                    </span>
                                </label>
                                <select class="form-select select2-search" id="contacts" data-placeholder="{{ translate("Choose groups") }}" aria-label="contacts" name="contacts[]" multiple>
                                  <option value=""></option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="col-12 group-logic">
                              <div class="form-inner d-none">
                                <label class="form-label"> {{ translate("Add Logic") }} </label>
                                <div class="form-inner-switch">
                                  <label class="pointer" for="group_logic" >{{translate('Add Logic to Groups to select specific contacts based on attrbitues')}}</label for="group_logic" >
                                  <div class="switch-wrapper mb-1 checkbox-data">
                                    <input type="checkbox" value="true" name="group_logic" id="group_logic" class="switch-input">
                                    <label for="group_logic" class="toggle">
                                      <span></span>
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-item group-logic-items mt-3"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              <div class="tab-pane fade" id="importFile" role="tabpanel">
                <div class="form-element">
                  <div class="row gy-3">
                    <div class="col-xxl-2 col-xl-3">
                      <h5 class="form-element-title">{{ translate("Import file") }}</h5>
                    </div>
                    <div class="col-xxl-7 col-lg-9">
                      <div class="form-inner">
                        <label for="file" class="form-label"> {{ translate("Import File") }}
                        </label>
                        <input type="file" name="contacts" id="file" class="form-control" aria-label="file" />
                        <p class="form-element-note">{{ translate("Download a demo csv file from this link: ") }} <a href="{{route('demo.file.download', ['extension' => 'csv' , 'type' => $type])}}">{{ translate("demo.csv") }}</a></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-element d-none">
                <div class="row gy-3">
                  <div class="col-xxl-2 col-xl-3">
                    <h5 class="form-element-title">{{ translate("Sending Method") }}</h5>
                  </div>
                  <div class="col-xxl-7 col-lg-9">
                    <div class="row gy-3 align-items-end">
                      <div class="col-12">
                        <div class="form-inner">
                          <label for="chooseMethod" class="form-label">{{ translate("Choose Method") }}</label>
                          <select class="form-select select2-search" id="chooseMethod" data-placeholder="{{ translate("Choose a sending method") }}" aria-label="chooseMethod" name="method">
                            <option value=""></option>
                            <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Android Gateway") }}</option>
                            <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}" selected>{{ translate("API Gateway") }}</option>
                          </select>
                        </div>
                      </div>
                      
                    </div>
                    <div id="selectAndroidGateway" class="row mt-3"></div>
                    <div id="selectApiGateway" class="row mt-3"></div>
                  </div>
                </div>
            </div>
            <div class="form-element">
              <div class="row gy-3">
                <div class="col-xxl-2 col-xl-3">
                  <h5 class="form-element-title">{{ translate("Schedule At") }}</h5>
                </div>
                <div class="col-xxl-7 col-lg-9">
                  <div class="form-inner">
                    <label for="ChooseGateway" class="form-label">{{ translate("Choose Date & Time") }}<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ translate("You can select date and time for schedule operation. Or leave it empty to send normally") }}">
                      <i class="ri-question-line"></i>
                      </span>
                    </label>
                    <div class="input-group">
                      <input type="datetime-local" class="form-control singleDateTimePicker" placeholder="{{ translate("Select schedule time") }}" name="schedule_at"/>
                      <span class="input-group-text calendar-icon" id="filterByDate">
                        <i class="ri-calendar-2-line"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-element d-none">
              <div class="row gy-3">
                <div class="col-xxl-2 col-xl-3">
                  <h5 class="form-element-title">{{ translate("SMS type") }}</h5>
                </div>
                <div class="col-xxl-7 col-lg-9">
                  <div class="radio-buttons-container message-type">
                    <div class="radio-button">
                      <input class="radio-button-input" type="radio" name="sms_type" id="smsTypeText" value="plain" checked="" />
                      <label for="smsTypeText" class="radio-button-label">
                        <span class="radio-button-custom"></span> {{ translate("Plain") }} </label>
                    </div>
                    <div class="radio-button">
                      <input class="radio-button-input" type="radio" name="sms_type" id="smsTypeUnicode" value="unicode" />
                      <label for="smsTypeUnicode" class="radio-button-label">
                        <span class="radio-button-custom"></span> {{ translate("Unicode") }} </label>
                    </div>
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
                  <div class="form-inner position-relative speech-to-text" id="messageBox">
                    <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2 mb-2">
                      <label for="message" class="form-label mb-0">{{ translate("Write message") }}</label>
                      <label for="message" class="text-counter">{{ translate("If your message is over 160 characters. Longer messages will be sent as multiple SMS segments, which may impact your costs.") }}</label>
                       
                      <button class="i-btn btn--sm p-0 bg-transparent text-primary available-template" type="button">
                        <i class="ri-layout-fill fs-5"></i>{{ translate("Use Template") }}</button>
                    </div>
                     <input type="hidden" name="original_string" id="original_string" value="">
                    <input type="hidden" name="encoded_string" id="encoded_string" value="">
                    <textarea class="form-control" name="message[message_body]" id="message"  rows="5" placeholder="{{translate('Enter SMS Content')}}  @php echo "\nIf Contact is being selected from a group then to mention First Name Use {{". 'first_name' ."}} \nTo initiate text spinner type {Hello|Hi|Hola} to you, {Mr.|Mrs.|Ms.} {Lucia|Jimmy|Arnold}"@endphp"> </textarea>
                    <div class="voice-icon">
                      <button type="button" class="icon-btn btn-sm primary-soft circle hover" id="text-to-speech-icon">
                        <i class="ri-mic-fill"></i>
                        <span class="tooltiptext"> {{ translate("Voice") }} </span>
                      </button>
                    </div>
                    <div id="charCounter" class="text-counter text-right">0 characters</div>
                    
                  </div>
                    <div class="mt-3">
                        <div class="track-link-container">
                            <div class="switch-wrapper checkbox-data">
                                <input type="checkbox" class="switch-input statusUpdate" id="trackLinkCheckbox" name="is_default">
                                <label for="trackLinkCheckbox" class="toggle">
                                    <span></span>
                                </label>
                            </div>
                            <label for="trackLinkCheckbox">Track Link</label>
                        </div>
                        <small>Links will be replaced with unique tracking URLs</small>
                    </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-xxl-9">
                <div class="form-action justify-content-end">
                  <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Send") }} </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
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
@endsection

@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>  
@endpush
@push('script-push')
<script>
	"use strict";
    select2_search($('.select2-search').data('placeholder'));

    $(document).ready(function() {
      $('#chooseMethod').trigger('change');
      $('.available-template').on('click', function() {

        const modal = $('#availableTemplate');
        modal.modal('show');

        $('#chooseTemplate').change(function() {

            var selectedTemplate = $(this).val();
            
            if (selectedTemplate) {

                var templateData = JSON.parse(selectedTemplate);
                var templateMessage = templateData.message;
                $('.template-data').empty();
                var templateTextArea = $('<textarea>', {

                    rows: '5',
                    readonly: true,
                    class: 'form-control',
                    id: 'sms_template_message',
                    required: ''
                }).val(templateMessage);

                $('.template-data').append('<div class="col-lg-12"><div class="form-inner"><label for="sms_template_add_message" class="form-label">{{translate('Template Body')}}<span class="text-danger">*</span></label></div></div>').find('.form-inner').append(templateTextArea);
            } else {

                $('.template-data').empty();
            }
        });
      });
      
      var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      var speechElements = document.querySelectorAll(".speech-to-text");
      var selectedTemplateMessage = '';
      $('#chooseTemplate').change(function() {

          var selectedTemplate = $(this).val();
          if (selectedTemplate) {

              var templateData = JSON.parse(selectedTemplate);
              $('#sms_template_message').val(templateData.message);
              selectedTemplateMessage = templateData.message;
          } else {

              $('#sms_template_message').val('');
              selectedTemplateMessage = '';
          }
      });
      $('#saveTemplateButton').click(function() {

          var mainTextArea = $('#message');
          insertAtCursor(mainTextArea[0], selectedTemplateMessage);
          $('#availableTemplate').modal('hide');
      });
      function insertAtCursor(textArea, text) {

          if (document.selection) {
          
              textArea.focus();
              var sel = document.selection.createRange();
              sel.text = text;
          } else if (textArea.selectionStart || textArea.selectionStart === 0) {
          
              var startPos = textArea.selectionStart;
              var endPos = textArea.selectionEnd;
              var scrollTop = textArea.scrollTop;
              textArea.value = textArea.value.substring(0, startPos) + text + textArea.value.substring(endPos, textArea.value.length);
              textArea.focus();
              textArea.selectionStart = startPos + text.length;
              textArea.selectionEnd = startPos + text.length;
              textArea.scrollTop = scrollTop;
          } else {

              textArea.value += text;
              textArea.focus();
          }
      }
      if (SpeechRecognition && speechElements.length > 0) {

          var recognition = new SpeechRecognition();
          var isListening = false;

          $('#text-to-speech-icon').on('click', function() {

              var messageBox = document.getElementById('messageBox');
              var textArea = messageBox.querySelector(".form-control");
              textArea.focus();

              recognition.onspeechstart = function() {

                  isListening = true;
              };

              if (!isListening) {

                  recognition.start();
              }

              recognition.onerror = function() {

                  isListening = false;
              };

              recognition.onresult = function(event) {

                  var currentText = textArea.value;
                  var newText = event.results[0][0].transcript;
                  textArea.value = currentText + " " + newText;
              };

              recognition.onspeechend = function() {
                  isListening = false;
                  recognition.stop();
              };
          });
      }
      var androidGateways = @json($android_gateways);

      function createSelectField(options, placeholder, name) {

          var select = $('<select>', {

              class: 'form-select select2-search',
              name: name,
              'data-placeholder': placeholder
          });
          $.each(options, function(index, option) {

              select.append($('<option>', {
                  value: option.value,
                  text: option.text,
                  'data-sims': option.sims || ''
              }));
          });
          return select;
      }

      function appendField(container, label, field, id, colClass = 'col-6') {

          var fieldContainer = $('<div>', { class: colClass, id: id }).append(
              $('<div>', { class: 'form-inner' }).append(
                  $('<label>', { class: 'form-label', text: label }),
                  field
              )
          );
          container.append(fieldContainer);
          fieldContainer.hide().fadeIn();
      }

      function removeField(id) {

          $('#' + id).remove();
      }
           
      $('#chooseMethod').change(function() {
        var apiGateways = @json($api_gateways);
        var method = $(this).val();
        var selectAndroidGateway = $('#selectAndroidGateway');
        var selectApiGateway = $('#selectApiGateway');
        selectAndroidGateway.empty();
        selectApiGateway.empty();
          if (method == "{{ \App\Enums\StatusEnum::FALSE->status() }}") {
              var androidGatewayOptions = [
                  { value: '-1', text: 'Automatic' }
              ];

              $.each(androidGateways, function(index, gateway) {
                  androidGatewayOptions.push({
                      value: gateway.id,
                      text: gateway.name,
                      sims: JSON.stringify(gateway.sim_info.map(function(sim) {
                          return {
                              id: sim.id,
                              sim_number: sim.sim_number
                          };
                      }))
                  });
              });

              var androidGatewaySelect = createSelectField(androidGatewayOptions, '{{ translate("Choose an Android Gateway") }}', 'androidGatewaySelect');
              appendField(selectAndroidGateway, '{{ translate("Choose Android Gateway") }}', androidGatewaySelect, 'androidGatewaySelectField', 'col-12');

              androidGatewaySelect.change(function() {
                  var selectedValue = $(this).val();

                  removeField('simSelectField');

                  if (selectedValue != '-1') {
                      $('#androidGatewaySelectField').removeClass('col-12').addClass('col-6');
                      var sims = JSON.parse($(this).find('option:selected').attr('data-sims') || '[]');
                      var simOptions = sims.map(function(sim) {
                          return {
                              value: sim.id,
                              text: sim.sim_number
                          };
                      });
                      var simSelect = createSelectField(simOptions, '{{ translate("Choose a SIM") }}', 'android_gateway_sim_id');
                      appendField(selectAndroidGateway, '{{ translate("Choose SIM") }}', simSelect, 'simSelectField', 'col-6');
                  } else {
                      $('#androidGatewaySelectField').removeClass('col-6').addClass('col-12');
                  }
              });
          } else if (method == "{{ \App\Enums\StatusEnum::TRUE->status() }}") {
              var apiGatewayTypeOptions = [
                  { value: '-1', text: 'Automatic', selected: 'selected' }
              ];

              $.each(apiGateways, function(type, gateways) {
                  apiGatewayTypeOptions.push({
                      value: type,
                      text: textFormat(['_'], type.replace(/^\d+/, ''), ' '),
                      selected: type.toLowerCase().includes("twilio")
                  });
              });

              var apiGatewayTypeSelect = createSelectField(apiGatewayTypeOptions, '{{ translate("Select API Gateway Type") }}', 'apiGatewayTypeSelect');
              appendField(selectApiGateway, '{{ translate("Select API Gateway Type") }}', apiGatewayTypeSelect, 'apiGatewayTypeSelectField', 'col-12');

              apiGatewayTypeSelect.change(function() {
                  var selectedType = $(this).val();

                  if (selectedType == '-1') {
                    
                      removeField('apiGatewaySelectField');
                      $('#apiGatewayTypeSelectField').removeClass('col-6').addClass('col-12');
                  } else {
                      removeField('apiGatewaySelectField');
                      $('#apiGatewayTypeSelectField').removeClass('col-12').addClass('col-6');
                      var apiGatewayOptions = Object.entries(apiGateways[selectedType]).map(function([id, name]) {
                          return {
                              value: id,
                              text: name
                          };
                      });
                      var apiGatewaySelect = createSelectField(apiGatewayOptions, '{{ translate("Select API Gateway") }}', 'apiGatewaySelect');
                      appendField(selectApiGateway, '{{ translate("Select API Gateway") }}', apiGatewaySelect, 'apiGatewaySelectField', 'col-6');
                  }
              });
              apiGatewayTypeSelect.val("102TWILIO").trigger('change');
          }
      });
       $('#chooseMethod').trigger('change');
      $('form').submit(function() {

          if($('#chooseMethod').val() == {{ \App\Enums\StatusEnum::FALSE->status() }}) {

            var androidGatewaySelect  = $('#androidGatewaySelect').val();
            var android_gateway_sim_id  = $('#android_gateway_sim_id').val();
            $('#gateway_id_manual').remove(); 
            $('#gateway_id_automatic').remove(); 

            if (androidGatewaySelect != '-1') { 

                $('<input>').attr({
                    type: 'hidden',
                    name: 'gateway_id',
                    id: 'gateway_id_manual',
                    value: android_gateway_sim_id
                }).appendTo('form');
            } else { 

                $('<input>').attr({
                    type: 'hidden',
                    name: 'gateway_id',
                    id: 'gateway_id_automatic',
                    value: '-1'
                }).appendTo('form');
            }
          } else {

            var apiGatewayTypeSelectField  = $('#apiGatewayTypeSelectField select').val();
            var selectApiGateway  = $('#apiGatewaySelectField select').val();
            $('#gateway_id_manual').remove(); 
            $('#gateway_id_automatic').remove(); 

            if (apiGatewayTypeSelectField  != '-1') { 
              
                $('<input>').attr({
                    type: 'hidden',
                    id: 'gateway_id_manual',
                    name: 'gateway_id',
                    value: selectApiGateway
                }).appendTo('form');
            } else { 

                $('<input>').attr({
                    type: 'hidden',
                    id: 'gateway_id_automatic',
                    name: 'gateway_id',
                    value: '-1'
                }).appendTo('form');
            }

          }
         
         
      });
      function createSelectField(options, placeholder, id) {
          var select = $('<select></select>').addClass('form-select').attr('id', id);
          $.each(options, function(index, option) {
              var opt = $('<option></option>').attr({
                  value: option.value,
                  selected: option.selected,
                  disabled: option.disabled
              }).text(option.text);
              if (option.sims) {
                  opt.attr('data-sims', option.sims);
              }
              select.append(opt);
          });
          return select;
      }
      function appendField(container, labelText, field, fieldId, colClass) {
          var fieldContainer = $('<div></div>').addClass(colClass).attr('id', fieldId);
          var label = $('<label></label>').addClass('form-label').text(labelText);
          fieldContainer.append(label).append(field);
          container.append(fieldContainer);
      }
      function removeField(fieldId) {
          $('#' + fieldId).fadeOut(function() {
              $(this).remove();
          });
      }

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
                      channel: "sms",
                  },
                  headers: {
                      'X-CSRF-TOKEN': csrfToken,
                  },
                  success: function(response) {

                      if (response.status == true) {

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


      $('#sms_send').on('submit', function(event) {

          var activeTabId = $('.tab-content .tab-pane.active').attr('id');

          // Clear data from inactive tabs
          if (activeTabId !== 'singleAudience') {
              $('#singleAudience input').val('');
          }
          if (activeTabId !== 'groupAudience') {
              $('#groupAudience input').val('');
          }
          if (activeTabId !== 'importFile') {
              $('#importFile input').val('');
          }
      });
    });
</script>

   <script>
    const messageTextarea = document.getElementById('message');
    const charCounter = document.getElementById('charCounter');
    const originalStringInput = document.getElementById('original_string');
    const encodedStringInput = document.getElementById('encoded_string');
    localStorage.setItem('isCharCount', 'no');
    let originalLinksMap = {}; // Object to map placeholders to original links
        messageTextarea.addEventListener('input', function () {
        const charCount = messageTextarea.value.length;
        charCounter.textContent = `${charCount} characters`;
        if(charCount < 160){
            localStorage.setItem('isCharCount', 'no');
        }
        if (charCount > 160 && localStorage.getItem('isCharCount') == 'no' ) {
                const userResponse = confirm(
                    "You have exceeded the 1 segment limit of 160 characters. Do you accept the additional cost for an additional segment?"
                );
                if(userResponse == true){
                        localStorage.setItem('isCharCount', 'yes');
                }
                // console.log("userResponse",userResponse);
                
            if (!userResponse) {
                // If the user clicks "No", truncate the text to 160 characters
                messageTextarea.value = messageTextarea.value.slice(0, 160);
                charCounter.textContent = `160 characters`; // Update counter
            }
        }
    });
    document.getElementById('trackLinkCheckbox').addEventListener('change', function() {
         updateTextareaContent();
    });
    function updateTextareaContent() {
        if (trackLinkCheckbox.checked) {
            const modifiedText = replaceLinkWithPlaceholder(messageTextarea.value);
            messageTextarea.value = modifiedText;
        } else {
            const restoredText = replacePlaceholderWithOriginal(messageTextarea.value);
            messageTextarea.value = restoredText;
            // Clear the hidden input values since the links are restored
            originalStringInput.value = '';
            encodedStringInput.value = '';
        }
        // Update SMS preview
        // smsPreviewContent.textContent = messageTextarea.value; // Update the preview with current textarea value
    }
    function replacePlaceholderWithOriginal(text) {
        const baseUrl = `${window.location.origin}/xsender`;
        const escapedBaseUrl = baseUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const placeholderRegex = new RegExp(`${escapedBaseUrl}\\/v1\\/([a-zA-Z0-9]+)`, 'g');

        return text.replace(placeholderRegex, (match, placeholder) => {
            return originalLinksMap[placeholder] || match; // Return original link or the match itself
        });
    }
    
    function replaceLinkWithPlaceholder(text) {
        originalLinksMap = {}; // Reset the map to avoid conflicts
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        const modifiedText = text.replace(urlRegex, (match) => {
            const placeholder = generateRandomString(10);
            originalLinksMap[placeholder] = match; // Map placeholder to original URL
            const baseUrl = `${window.location.origin}/xsender`;
            return `${baseUrl}/v1/${placeholder}`;
        });
        
        // Store all original and encoded links
        originalStringInput.value = JSON.stringify(Object.values(originalLinksMap));
        encodedStringInput.value = JSON.stringify(Object.keys(originalLinksMap));

        return modifiedText;
    }
    
    

    
    // Function to generate a random string as a placeholder
    function generateRandomString(length) {
        const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return result;
    }
    messageTextarea.addEventListener('paste', function(event) {
        event.preventDefault();
        const pastedText = (event.clipboardData || window.clipboardData).getData('text');
        
        document.execCommand('insertText', false, pastedText);
        const charCount = messageTextarea.value.length;
        charCounter.textContent = `${charCount} characters`;
        if(charCount < 160){
            localStorage.setItem('isCharCount', 'no');
        }
        
        if (charCount > 160 && localStorage.getItem('isCharCount') == 'no' ) {
                const userResponse = confirm(
                    "You have exceeded the 1 segment limit of 160 characters. Do you accept the additional cost for an additional segment?"
                );
                if(userResponse == true){
                        localStorage.setItem('isCharCount', 'yes');
                }
                // console.log("userResponse",userResponse);
                
            if (!userResponse) {
                // If the user clicks "No", truncate the text to 160 characters
                messageTextarea.value = messageTextarea.value.slice(0, 160);
                charCounter.textContent = `160 characters`; // Update counter
            }
        }
        updateTextareaContent();
    });
   </script>
@endpush
