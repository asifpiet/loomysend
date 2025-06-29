@php
$commonFixedContent = array_values(array_filter($service_breadcrumb_content, function($item) {
    return $item->section_key === 'service_breadcrumb.common.fixed_content';
}))[0] ?? null;
$fixedContent = array_values(array_filter($service_breadcrumb_content, function($item) use($type) {
    return $item->section_key === "service_breadcrumb.$type.fixed_content";
}))[0] ?? null;
@endphp

<section class="breadcrumb-banner pb-130">
    <div class="container-fluid container-wrapper">
      <div class="banner-wrapper">
        <div class="breadcrumb-img">
          <img src="{{showImage(config("setting.file_path.frontend.service_breadcrumb_image.path").'/'.getArrayValue(@$fixedContent->section_value, 'service_breadcrumb_image'),config("setting.file_path.frontend.service_breadcrumb_image.size"))}}" alt="{{ getArrayValue(@$fixedContent->section_value, 'sms_service_breadcrumb_image') }}" />
        </div>
        <div class="breadcrumb-content">
          <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-9">
              <h2 class="breadcrumb-title"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'heading') }} </h2>
            </div>
          </div>
          <div class="breadcrumb-bottom">
            <div class="row gy-5 align-items-start">
              <div class="col-xxl-5 col-xl-6">
                <div class="breadcrumb-actions">
                  <a href="{{getArrayValue(@$commonFixedContent->section_value, 'transparent_btn_url') }}" class="i-btn btn--light outline btn--xl pill"> {{getTranslatedArrayValue(@$commonFixedContent->section_value, 'transparent_btn_name') }} </a>
                  <a href="{{getArrayValue(@$commonFixedContent->section_value, 'solid_btn_url') }}" class="i-btn btn--light btn--xl pill"> {{getTranslatedArrayValue(@$commonFixedContent->section_value, 'solid_btn_name') }} </a>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ translate("Home") }}</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">{{getTranslatedArrayValue(@$fixedContent->section_value, 'breadcrumb_title') }} </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <div class="col-xxl-6 col-xl-6 offset-xxl-1">
                <p class="breadcrumb-description"> {{getTranslatedArrayValue(@$fixedContent->section_value, 'sub_heading') }}  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </section>
  
  <!DOCTYPE html>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relay Network</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Relay Network</h1>

        <!-- Business Section -->
        <div class="mb-5">
            <h2>Business: Add a Service</h2>
            <form id="businessForm">
                <div class="mb-3">
                    <label for="yourName" class="form-label">Need Your Name</label>
                    <input type="text" id="yourName" class="form-control" placeholder="Enter name" required>
                </div>
                <div class="mb-3">
                    <label for="serviceName" class="form-label">Service Name</label>
                    <input type="text" id="serviceName" class="form-control" placeholder="Enter service name" required>
                </div>
                <div class="mb-3">
                    <label for="serviceDescription" class="form-label">Description</label>
                    <textarea id="serviceDescription" class="form-control" placeholder="Enter service description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="serviceMobileNumber" class="form-label">Mobile Number</label>
                    <input type="tel" id="serviceMobileNumber" class="form-control" placeholder="Enter Mobile Number" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Request</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#businessForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    yourName: $('#yourName').val(),
                    serviceName: $('#serviceName').val(),
                    serviceDescription: $('#serviceDescription').val(),
                    serviceMobileNumber: $('#serviceMobileNumber').val(),
                };

                $.ajax({
                    url: '/xsender/api/send-service-sms',  // Assuming this is your backend endpoint
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('SMS sent successfully!');
                        $('#businessForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to send SMS: ' + xhr.responseJSON.message);
                    }
                });
            });
            
            // const mobileInput = document.getElementById("serviceMobileNumber");

            // mobileInput.addEventListener("input", function(e) {
            //     let value = e.target.value.replace(/\D/g, '');  // Remove non-numeric characters
                
            //     // Format as (932) 1234-345
            //     if (value.length <= 3) {
            //         e.target.value = `(${value}`;
            //     } else if (value.length <= 7) {
            //         e.target.value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
            //     } else {
            //         e.target.value = `(${value.slice(0, 3)}) ${value.slice(3, 7)}-${value.slice(7, 10)}`;
            //     }
            // });
        });
    </script>
</body>
</html>


