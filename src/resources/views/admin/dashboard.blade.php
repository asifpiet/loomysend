@extends("admin.layouts.app")
@section("panel")
  
  <main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <!--{{ $title }}-->
        <h2> Dashboard</h2>
      </div>
      <div class="row g-4">
        <div class="col-12">
          <div class="row g-4">
            <div class="col-xxl-10 order-lg-1 order-xxl-0">
              <div class="row g-4">
                <div class="col-xxl-6 col-xl-6">
                  <div class="card feature-card">
                    <div class="card-header pb-0">
                      <div class="card-header-left">
                        <h4 class="card-title">{{ translate("SMS Statistics") }}</h4>
                      </div>
                      <div class="card-header-right">
                        <span class="fs-3 text-info">
                          <i class="ri-message-2-line"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-primary">
                                <i class="ri-message-2-line"></i>
                              </span>
                              <small>{{ translate("Send SMS") }}</small>
                            </div>
                            <p class="feature-status-count">{{formatNumber($logs['sms']['all'])}}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-success">
                                <i class="ri-mail-check-line"></i>
                              </span>
                              <!--<small>{{ translate("Success") }}</small>-->
                              <small>{{ translate("SMS Campaign") }}</small>
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs['sms']['allsmsCamapgin']) }}</p>
                          </div>
                        </div>
                        <!--<div class="col-6">-->
                        <!--  <div class="feature-status">-->
                        <!--    <div class="feature-status-left">-->
                        <!--      <span class="feature-icon text-warning">-->
                        <!--        <i class="ri-hourglass-fill"></i>-->
                        <!--      </span>-->
                        <!--      <small>{{ translate("Pending") }}</small>-->
                        <!--    </div>-->
                        <!--    <p class="feature-status-count">{{ formatNumber($logs['sms']['pending'],0) }}</p>-->
                        <!--  </div>-->
                        <!--</div>-->
                        <!--<div class="col-6">-->
                        <!--  <div class="feature-status">-->
                        <!--    <div class="feature-status-left">-->
                        <!--      <span class="feature-icon text-danger">-->
                        <!--        <i class="ri-mail-close-line"></i>-->
                        <!--      </span>-->
                        <!--      <small>{{ translate("Failed") }}</small>-->
                        <!--    </div>-->
                        <!--    <p class="feature-status-count">{{ formatNumber($logs["sms"]["failed"]) }}</p>-->
                        <!--  </div>-->
                        <!--</div>-->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xxl-6 col-xl-6">
                  <div class="card feature-card">
                    <div class="card-header pb-0">
                      <div class="card-header-left">
                        <h4 class="card-title">{{ translate("Email Statistics") }}</h4>
                      </div>
                      <div class="card-header-right">
                        <span class="fs-3 text-danger">
                          <i class="ri-mail-line"></i>
                        </span>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row g-3">
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-primary">
                                <i class="ri-mail-line"></i>
                              </span>
                              <small>{{ translate("Send Email") }}</small>
                            </div>
                            
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["all"]) }}</p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="feature-status">
                            <div class="feature-status-left">
                              <span class="feature-icon text-success">
                                <i class="ri-mail-check-line"></i>
                              </span>
                              <small>{{ translate("Email Campaign") }}</small>
                              <!--<small>{{ translate("Success") }}</small>-->
                            </div>
                            <p class="feature-status-count">{{ formatNumber($logs["email"]["allemailCampaign"]) }}</p>
                          </div>
                        </div>
                        <!--<div class="col-6">-->
                        <!--  <div class="feature-status">-->
                        <!--    <div class="feature-status-left">-->
                        <!--      <span class="feature-icon text-warning">-->
                        <!--        <i class="ri-hourglass-fill"></i>-->
                        <!--      </span>-->
                        <!--      <small>{{ translate("Pending") }}</small>-->
                        <!--    </div>-->
                        <!--    <p class="feature-status-count">{{ formatNumber($logs["email"]["pending"],0) }}</p>-->
                        <!--  </div>-->
                        <!--</div>-->
                        <!--<div class="col-6">-->
                        <!--  <div class="feature-status">-->
                        <!--    <div class="feature-status-left">-->
                        <!--      <span class="feature-icon text-danger">-->
                        <!--        <i class="ri-mail-close-line"></i>-->
                        <!--      </span>-->
                        <!--      <small>{{ translate("Failed") }}</small>-->
                        <!--    </div>-->
                        <!--    <p class="feature-status-count">{{ formatNumber($logs["email"]["failed"]) }}</p>-->
                        <!--  </div>-->
                        <!--</div>-->
                      </div>
                    </div>
                  </div>
                </div>
                <!--<div class="col-xxl-4 col-xl-4">-->
                <!--  <div class="card feature-card">-->
                <!--    <div class="card-header pb-0">-->
                <!--      <div class="card-header-left">-->
                <!--        <h4 class="card-title">{{ translate("Whatsapp Statistics") }}</h4>-->
                <!--      </div>-->
                <!--      <div class="card-header-right">-->
                <!--        <span class="fs-3 text-success">-->
                <!--          <i class="ri-whatsapp-line"></i>-->
                <!--        </span>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--    <div class="card-body">-->
                <!--      <div class="row g-3">-->
                <!--        <div class="col-6">-->
                <!--          <div class="feature-status">-->
                <!--            <div class="feature-status-left">-->
                <!--              <span class="feature-icon text-primary">-->
                <!--                <i class="ri-whatsapp-line"></i>-->
                <!--              </span>-->
                <!--              <small>{{ translate("Total") }}</small>-->
                <!--            </div>-->
                <!--            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["all"]) }}</p>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--        <div class="col-6">-->
                <!--          <div class="feature-status">-->
                <!--            <div class="feature-status-left">-->
                <!--              <span class="feature-icon text-success">-->
                <!--                <i class="ri-mail-check-line"></i>-->
                <!--              </span>-->
                <!--              <small>{{ translate("Success") }}</small>-->
                <!--            </div>-->
                <!--            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["success"]) }}</p>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--        <div class="col-6">-->
                <!--          <div class="feature-status">-->
                <!--            <div class="feature-status-left">-->
                <!--              <span class="feature-icon text-warning">-->
                <!--                <i class="ri-hourglass-fill"></i>-->
                <!--              </span>-->
                <!--              <small>{{ translate("Pending") }}</small>-->
                <!--            </div>-->
                <!--            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["pending"],0) }}</p>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--        <div class="col-6">-->
                <!--          <div class="feature-status">-->
                <!--            <div class="feature-status-left">-->
                <!--              <span class="feature-icon text-danger">-->
                <!--                <i class="ri-mail-close-line"></i>-->
                <!--              </span>-->
                <!--              <small>{{ translate("Failed") }}</small>-->
                <!--            </div>-->
                <!--            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["failed"]) }}</p>-->
                <!--          </div>-->
                <!--        </div>-->
                <!--      </div>-->
                <!--    </div>-->
                <!--  </div>-->
                <!--</div>-->
              </div>
            </div>
            <!--<div class="col-xxl-2">-->
            <!--  <div class="membership-card card-height-100">-->
            <!--    <div class="membership-content">-->
            <!--      <h5>{{ translate("WhatsApp Cloud API") }}</h5>-->
            <!--      <p> {{ translate("As an alternative solution to the WhatsApp Node Modules, you can try out our cloud api system") }} </p>-->
            <!--    </div>-->
            <!--    <a href="{{ route("admin.gateway.whatsapp.cloud.api") }}" class="membership-btn">{{ translate("Try now") }}</a>-->
            <!--    <span class="membership-shape">-->
            <!--      <svg xmlns="http://www.w3.org/2000/svg" width="101" height="110" viewBox="0 0 101 110" fill="none">-->
            <!--        <circle cx="99.6525" cy="127.091" r="42.5448" transform="rotate(-64.4926 99.6525 127.091)" fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />-->
            <!--        <circle cx="92.0618" cy="89.32" r="67" transform="rotate(-64.4926 92.0618 89.32)" fill="{{ site_settings('trinary_color') }}" fill-opacity="0.4" />-->
            <!--      </svg>-->
            <!--    </span>-->
            <!--  </div>-->
            <!--</div>-->
          </div>
        </div>
        <div class="col-12">
          <div class="row g-4">
            <div class="col-xxl-10 order-lg-1 order-xxl-0">
              <div class="row g-4">
                <div class="col-xxl-6 col-xl-6">
                  <div class="">
                    <div class="">
                      <div class="row g-3">
                        <div class="col-12">
                            <div class="card card-height-100">
                                <div class="card-header pb-0">
                                  <h4 class="card-title pb-0">{{ translate("Sent Mail Report") }}</h4>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                                       <div class="flex flex-col xl:flex-row xl:items-center justify-center text-center">
                                            <div class="d-flex items-center justify-center">
                                                <div>
                                                    <div class="text-theme-20 dark:text-gray-300 text-lg xl:text-xl font-bold text-center">{{ formatNumber($logs["email"]["currentMonthTotalSentmail"]) }}</div>
                                                    <div class="text-gray-600 dark:text-gray-600">This Month</div>
                                                </div>
                                                <div class="w-px h-12 border border-r border-dashed border-gray-300 dark:border-dark-5 mx-4 xl:mx-6"></div>
                                                <div>
                                                    <div class="text-gray-600 dark:text-gray-600 text-lg xl:text-xl font-medium text-center">{{ formatNumber($logs["email"]["lastMonthTotalSentmail"]) }} </div>
                                                    <div class="text-gray-600 dark:text-gray-600">Last Month</div>
                                                </div>
                                            </div>
                                         
                                        </div>
                    
                                        <div>
                                            <div id="my-chart-1"></div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                                <script src="https://igohosts.com/emaildoll/public/assets/js/apexcharts.js"></script>
                                <script>
                
                                // This is dynmic script, all the datas are coming from laravel query
                
                                "use strict"
                                var options = {
                          series: [{
                            name: "Sent Mails",
                            data: [
                                            <?php echo $logs["email"]["currentMonthTotalSentmail"]; ?>,
                                             <?php echo $logs["email"]["lastMonthTotalSentmail"]; ?>,
                                             <?php echo $logs["email"]["lasttwoMonthTotalSentmail"]; ?>,
                                            
                                            ]
                        }],
                          chart: {
                          height: 350,
                          type: 'area',
                          zoom: {
                            enabled: true
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth'
                        },
                        title: {
                          text: 'Sent Mails By Month',
                          align: 'left'
                        },
                        grid: {
                          row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                          },
                        },
                        xaxis: {
                          categories: [
                              
                                        <?php echo "'$twoMonthsAgoName'"; ?>,
                                        <?php echo "'$lastMonthName'"; ?>,
                                        <?php echo "'$currentMonthName'"; ?>,
                                        ],
                        }
                        };
                
                        var chart = new ApexCharts(document.querySelector("#my-chart-1"), options);
                        chart.render();
                
                        
                
                    
                            </script>            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xxl-6 col-xl-6">
                        <div class="card card-height-100">
                            <div class="card-header pb-0">
                              <h4 class="card-title pb-0">{{ translate("Sent SMS Report") }}</h4>
                            </div>
                            <div class="card-body pt-0">
                               <div class="intro-y box p-5 mt-12 sm:mt-5">
                                 <div class="flex flex-col xl:flex-row xl:items-center justify-center text-center">
                                    <div class="d-flex items-center justify-center">
                                        <div>
                                            <div class="text-theme-20 dark:text-gray-300 text-lg xl:text-xl font-bold">{{ formatNumber($logs["sms"]["currentMonthTotalSentSMS"]) }}</div>
                                            <div class="text-gray-600 dark:text-gray-600">This Month</div>
                                        </div>
                                        <div class="w-px h-12 border border-r border-dashed border-gray-300 dark:border-dark-5 mx-4 xl:mx-6"></div>
                                        <div>
                                            <div class="text-gray-600 dark:text-gray-600 text-lg xl:text-xl font-medium">{{ formatNumber($logs["sms"]["lastMonthTotalSentSMS"]) }}</div>
                                            <div class="text-gray-600 dark:text-gray-600">Last Month</div>
                                        </div>
                                    </div>
                                </div>
            
                                <div>
                                    <div id="sent_sms_chart"></div>
                                </div>
                            </div>
                            </div>
                          </div>
                        <script src="https://igohosts.com/emaildoll/public/assets/js/apexcharts.js"></script>
                        <script>
            
                            // This is dynmic script, all the datas are coming from laravel query
            
            
                            "use strict"
                            
                           var options = {
                      series: [{
                      name: 'Twilio',
                      data: [
                                        <?php echo $logs["sms"]["currentMonthTotalSentSMS"]; ?>,
                                             <?php echo $logs["sms"]["lastMonthTotalSentSMS"]; ?>,
                                             <?php echo $logs["sms"]["lasttwoMonthTotalSentSMS"]; ?>,
                                    ]
                    }, {
                      name: 'Nexmo',
                      data: [
                                    ]
                    }, {
                      name: 'Plivo',
                      data: [
                                    ]
                    }],
                      chart: {
                      type: 'area',
                      height: 350
                    },
                    plotOptions: {
                      bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                      },
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      show: true,
                      width: 2,
                      colors: ['transparent']
                    },
                    xaxis: {
                      categories: [
                                     
                                      <?php echo "'$twoMonthsAgoName'"; ?>,
                                        <?php echo "'$lastMonthName'"; ?>,
                                        <?php echo "'$currentMonthName'"; ?>,
                                  ],
                    },
                    yaxis: {
                      title: {
                        text: 'Total SMS Sent'
                      }
                    },
                    fill: {
                      opacity: 1
                    },
                    tooltip: {
                      y: {
                        formatter: function (val) {
                          return val
                        }
                      }
                    }
                    };
            
                    var chart = new ApexCharts(document.querySelector("#sent_sms_chart"), options);
                    chart.render();
                
                        </script>  
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
        <!--Campaign Report-->
        <div class="col-xxl-6">
          <div class="card card-height-100">
            <div class="card-header pb-0">
              <h4 class="card-title pb-0">{{ translate("Campaign Report") }}</h4>
            </div>
            <div class="card-body pt-0">
                <div class="xxl:pl-12 grid grid-cols-12 gap-6 mt-0">
                   <div class="col-span-12 md:col-span-12 xl:col-span-12 xxl:col-span-12 xxl:mt-12">
                       <div id="statistics"></div>
                   </div>
               </div>
            </div>
          </div>
        </div>
       <!-- <div class="intro-y block sm:flex items-center h-5">-->
       <!--    <h4 class="text-lg font-medium truncate ml-5">Campaign Report</h4>-->
       <!--</div>-->
        
        <script src="https://igohosts.com/emaildoll/public/assets/js/apexcharts.js"></script>

   <script>
       var options = {
           series: [{

               data: [
                   <?php echo $logs["email"]["recipients"]; ?>,
                   <?php echo $logs["email"]["success"]; ?>,
                   <?php echo $logs["email"]["clicks"]; ?>,
                   1,
                   <?php echo $logs["email"]["failed"]; ?>,
                   <?php echo $logs["email"]["opens"]; ?>
                   
                   
               ]
           }],
           chart: {
               type: 'bar',
               height: 350
           },
           plotOptions: {
               bar: {
                   borderRadius: 4,
                   horizontal: true,
               }
           },
           dataLabels: {
               enabled: true
           },
           xaxis: {
               categories: [
                   'Recipients',
                   'Delivered',
                   'Click',
                   'Unique Click',
                   'Failed',
                   'Open'
                   
               ],
           },
           colors: ['#F25D6D']
       };

       var chart = new ApexCharts(document.querySelector("#statistics"), options);
       chart.render();
   </script>
        
        <!--<div class="col-xxl-4 col-xl-5">-->
        <!--  <div class="card card-height-100">-->
        <!--    <div class="card-header">-->
        <!--      <h4 class="card-title">{{ translate("Application Usage") }}</h4>-->
        <!--    </div>-->
        <!--    <div class="card-body">-->
        <!--      <div id="application_usage" -->
        <!--           class="apex-charts" -->
        <!--           data-name='["application_usage"]'-->
        <!--           data-sms-heading="SMS"-->
        <!--           {{-- data-sms-color="#0D7FD1" --}}-->
        <!--           data-sms-color="{{ site_settings("primary_color") }}"-->
        <!--           data-sms="{{ $logs["sms"]["all"] }}"-->
        <!--           data-whatsapp-heading="WhatsApp"-->
        <!--           {{-- data-whatsapp-color="#195458" --}}-->
        <!--           data-whatsapp-color="{{ site_settings("secondary_color") }}"-->
        <!--           data-whatsapp="{{ $logs["whats_app"]["all"] }}"-->
        <!--           data-email-heading="Email"-->
        <!--           {{-- data-email-color="#32B586" --}}-->
        <!--           data-email-color="{{ site_settings("trinary_color") }}"-->
        <!--           data-email="{{ $logs["email"]["all"] }}">-->
        <!--      </div>-->
        <!--    </div>-->
        <!--  </div>-->
        <!--</div>-->
        <!--<div class="col-xxl-8 col-xl-7">-->
        <!--  <div class="card card-height-100">-->
        <!--    <div class="card-header pb-0">-->
        <!--      <div class="card-header-left">-->
        <!--        <h4 class="card-title">{{ translate("Subscribptions") }}</h4>-->
        <!--      </div>-->
        <!--    </div>-->
        <!--    <div class="card-body">-->
             
        <!--      <div id="subscription-chart" -->
        <!--           class="apex-charts"-->
        <!--           data-chartData="{{ json_encode($totalUser) }}"-->
        <!--           data-tool-tip-theme="{{ site_settings("theme_mode") == \App\Enums\StatusEnum::TRUE->status() ? 'light' : 'dark' }}"-->
        <!--           data-legend-theme="{{ site_settings("theme_mode") == \App\Enums\StatusEnum::TRUE->status() ? '#000000a2' : '#ffffffa9' }}">-->
        <!--      </div>-->
        <!--    </div>-->
        <!--  </div>-->
        <!--</div>-->
        

       
        <div class="col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("New Users") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                      <tr>
                        <th scope="col">{{ translate("Name") }}</th>
                        <th scope="col">{{ translate("Email/Phone") }}</th>
                        <th scope="col">{{ translate("Status") }}</th>
                        <th scope="col">{{ translate("Joined At") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($customers as $customer) 
                        <tr>
                          <td>
                            <p class="text-dark fw-medium">{{ $customer?->name }}</p>
                          </td>
                          <td>
                            <a href="mailto:{{ $customer?->email }}" class="text-dark">{{ $customer?->email }}</a>
                          </td>
                          <td>
                            <span class="i-badge dot {{ $customer->status == \App\Enums\StatusEnum::TRUE->status() ? 'success' : 'danger' }}-soft pill">{{ $customer->status == \App\Enums\StatusEnum::TRUE->status() ? translate("Active") : translate("Banned") }}</span>
                          </td>
                          <td>
                            <span>{{ $customer?->created_at->diffForHumans() }}</span>
                            <p>{{ $customer?->created_at->toDayDateTimeString() }}</p>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("Latest Payment Log") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                      <tr>
                        <th scope="col">{{ translate("Customer") }}</th>
                        <th scope="col">{{ translate("Payment Gateway") }}</th>
                        <th scope="col">{{ translate("Amount") }}</th>
                        <th scope="col">{{ translate("TrxID") }}</th>
                        <th scope="col">{{ translate("Date/Time") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($paymentLogs as $paymentLog)
                        <tr>
                          <td>
                            <p class="text-dark fw-medium">{{ $paymentLog?->user?->name }}</p>
                          </td>
                          <td>
                            <span>{{ $paymentLog->paymentGateway ? $paymentLog->paymentGateway->name : translate("N\A") }}</span>
                          </td>
                          <td>
                            <span class="text-dark fw-semibold">{{shortAmount(@$paymentLog->amount)}} {{ $paymentLog->paymentGateway ? $paymentLog->paymentGateway->currency_code : translate("N\A") }}</span>
                          </td>
                          <td>
                            <p>{{$paymentLog->trx_number}}</p>
                            @php echo payment_status($paymentLog->status)  @endphp
                          </td>
                          <td>
                            <span>{{ $paymentLog?->created_at->diffForHumans() }}</span>
                            <p> {{ $customer?->created_at->toDayDateTimeString() }}</p>
                          </p>
                          </td>
                        </tr>
                      @empty
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

@endsection
