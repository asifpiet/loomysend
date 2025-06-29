@extends("user.layouts.app")
@section("panel")
  
  <main class="main-body">
    <div class="container-fluid px-0 main-content">
        <div class="page-header">
            <div class="page-header-left">
              <h2>{{ $title }} </h2>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-xl-6 col-md-6">
              <div class="card credit-card">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                  <div class="credit-card-left">
                    <div class="credit-count">
                      <span class="fs-3 text-info">
                        <i class="ri-message-2-line"></i>
                      </span>
                      <h6>{{auth()->user()->sms_credit == -1 ? translate("Unlimited") : formatNumber(auth()->user()->sms_credit) ?? translate("N\A")}}</h6>
                    </div>
                    <p>{{ translate("SMS Credit") }}</p>
                  </div>
                  <div>
                    <a href="{{route('user.plan.create')}}" class="i-btn btn--primary btn--md"> {{ translate("Buy Credit") }} </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-md-6">
              <div class="card credit-card">
                <div class="card-body d-flex align-items-center justify-content-between flex-wrap">
                  <div class="credit-card-left">
                    <div class="credit-count">
                      <span class="fs-3 text-danger">
                        <i class="ri-mail-line"></i>
                      </span>
                      <h6>{{auth()->user()->email_credit == -1 ? translate("Unlimited") : formatNumber(auth()->user()->email_credit) ?? translate("N\A")}}</h6>
                    </div>
                    <p>{{ translate("Email Credit") }}</p>
                  </div>
                  <div>
                    <a href="{{route('user.plan.create')}}" class="i-btn btn--primary btn--md"> {{ translate("Buy Credit") }} </a>
                  </div>
                </div>
              </div>
            </div>
            <!--<div class="col-xl-4 col-md-6">-->
            <!--  <div class="card credit-card">-->
            <!--    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-4">-->
            <!--      <div class="credit-card-left">-->
            <!--        <div class="credit-count">-->
            <!--          <span class="fs-3 text-success">-->
            <!--            <i class="ri-whatsapp-line"></i>-->
            <!--          </span>-->
            <!--          <h6>{{auth()->user()->whatsapp_credit == -1 ? translate("Unlimited") : formatNumber(auth()->user()->whatsapp_credit) ?? translate("N\A")}}</h6>-->
            <!--        </div>-->
            <!--        <p>{{ translate("Whatsapp Credit") }}</p>-->
            <!--      </div>-->
            <!--      <div>-->
            <!--        <a href="{{route('user.plan.create')}}" class="i-btn btn--primary btn--md"> {{ translate("Buy Credit") }} </a>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
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
                        <!--    <p class="feature-status-count">{{ formatNumber($logs['sms']['pending']) }}</p>-->
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
                        <!--    <p class="feature-status-count">{{ formatNumber($logs["email"]["pending"]) }}</p>-->
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
                <!--            <p class="feature-status-count">{{ formatNumber($logs["whats_app"]["pending"]) }}</p>-->
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
            <!--    <a href="#" class="membership-btn">{{ translate("Try now") }}</a>-->
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
                                            <?php echo $logs["email"]["lasttwoMonthTotalSentmail"]; ?>,
                                             <?php echo $logs["email"]["lastMonthTotalSentmail"]; ?>,
                                             <?php echo $logs["email"]["currentMonthTotalSentmail"]; ?>,
                                            
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
                                        <?php echo $logs["sms"]["lasttwoMonthTotalSentSMS"]; ?>,
                                             <?php echo $logs["sms"]["lastMonthTotalSentSMS"]; ?>,
                                             <?php echo $logs["sms"]["currentMonthTotalSentSMS"]; ?>,
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
        <script src="https://igohosts.com/emaildoll/public/assets/js/apexcharts.js"></script>

   <script>
       var options = {
           series: [{

               data: [
                   <?php echo $logs["email"]["recipients"]; ?>,
                   <?php echo $logs["email"]["success"]; ?>,
                   <?php echo $logs["email"]["clicks"]; ?>,
                   0,
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
       
        <div class="col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="card-header-left">
                <h4 class="card-title">{{ translate("Latest Credit Log") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                        <tr>
                            <th>{{ translate('Date')}}</th>
                            <th>{{ translate('Trx Number')}}</th>
                            <th>{{ translate('Channel')}}</th>
                            <th>{{ translate('Previous Credit')}}</th>
                            <th>{{ translate('Credit')}}</th>
                        </tr>
                    </thead>
                    @forelse($credits as $credit_data)
                        <tr class="@if($loop->even)@endif">
                            <td data-label="{{ translate('Date')}}">
                                <span>{{diffForHumans($credit_data->created_at)}}</span><br>
                                {{getDateTime($credit_data->created_at)}}
                            </td>

                            <td data-label="{{ translate('Trx Number')}}">
                                {{$credit_data->trx_number}}
                            </td>

                            <td data-label="{{ translate('Channel')}}">
                                <span class="i-badge {{ $credit_data->type == \App\Enums\ServiceType::SMS->value ? 'info-soft' : ($credit_data->type == \App\Enums\ServiceType::WHATSAPP->value ? 'success-soft' : 'warning-soft')}}">{{ucfirst(strtolower(\App\Enums\ServiceType::getValue($credit_data->type)))}}
                                </span>
                            </td>
                            <td data-label="{{ translate('Previous Credit')}}">
                                {{$credit_data->post_credit}} {{ translate('Credit')}}
                            </td>
                            <td data-label="{{ translate('Credit')}}">
                                <span class="i-badge {{ $credit_data->credit_type == \App\Enums\StatusEnum::TRUE->status() ? 'success-soft' : 'danger-soft'}}">{{ $credit_data->credit_type == \App\Enums\StatusEnum::TRUE->status() ? '+' : '-' }} {{ $credit_data->credit }}
                                </span>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                        </tr>
                    @endforelse
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
                <h4 class="card-title">{{ translate("Latest Transactions Log") }}</h4>
              </div>
            </div>
            <div class="card-body px-0 pt-0">
              <div class="table-container">
                <div class="default_table">
                  <table>
                    <thead>
                        <tr>
                            <th>{{ translate('Date')}}</th>
                            <th>{{ translate('Trx Number')}}</th>
                            <th>{{ translate('Amount')}}</th>
                            <th>{{ translate('Detail')}}</th>
                        </tr>
                    </thead>
                    @forelse($transactions as $transaction)
                        <tr class="@if($loop->even)@endif">
                            <td data-label="{{ translate('Date')}}">
                                <span>{{diffForHumans($transaction->created_at)}}</span><br>
                                {{getDateTime($transaction->created_at)}}
                            </td>

                            <td data-label="{{ translate('Trx Number')}}">
                                {{$transaction->transaction_number}}
                            </td>

                            <td data-label="{{ translate('Amount')}}">
                                <span class="i-badge @if($transaction->transaction_type == '+')success-soft @else danger-soft @endif">{{ $transaction->transaction_type }} {{shortAmount($transaction->amount)}} 
                                </span>
                            </td>

                            <td data-label="{{ translate('Details')}}">
                                {{$transaction->details}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ translate('No Data Found')}}</td>
                        </tr>
                    @endforelse
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
