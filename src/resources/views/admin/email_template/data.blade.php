<ul class="pre-built-temp-nav nav nav-tabs nav-tabs-bordered d-flex gap-3 mb-4" id="borderedTabJustified" role="tablist">
    @if(site_settings('plugin') == \App\Enums\StatusEnum::TRUE->status() && array_key_exists('beefree', json_decode(site_settings("available_plugins"), true)) && array_key_exists('status', json_decode(site_settings("available_plugins"), true)['beefree']) && json_decode(site_settings("available_plugins"), true)['beefree']['status'] == \App\Enums\StatusEnum::TRUE->status())
    <li class="nav-item flex-fill" role="presentation">
        <button class="nav-link w-100 active" id="bee-pro-template" data-bs-toggle="tab"
            data-bs-target="#bee-pro-template-content" type="button" role="tab" aria-controls="beepro"
            aria-selected="true">
            {{translate('Bee Pro Template')}}
        </button>
    </li>
    @endif

    <!--<li class="nav-item flex-fill" role="presentation">-->
    <!--    <button class="nav-link w-100" id="edior-template" data-bs-toggle="tab"-->
    <!--        data-bs-target="#editor-template-content" type="button" role="tab" aria-controls="editor"-->
    <!--        aria-selected="false">-->
    <!--        {{translate('CK Text Editor')}}-->
    <!--    </button>-->
    <!--</li>-->
</ul>

<div class="tab-content template-tab pt-4" id="borderedTabJustifiedContent">
    @if(site_settings('plugin') == \App\Enums\StatusEnum::TRUE->status() && array_key_exists('beefree', json_decode(site_settings("available_plugins"), true)) && array_key_exists('status', json_decode(site_settings("available_plugins"), true)['beefree']) && json_decode(site_settings("available_plugins"), true)['beefree']['status'] == \App\Enums\StatusEnum::TRUE->status())
    <div class="tab-pane fade show active" id="bee-pro-template-content" role="tabpanel"
        aria-labelledby="bee-pro-template">
        @if(count($templates) ==  0 || $templates->where('provider', \App\Enums\TemplateProvider::BEE_FREE->value)->count() == 0)
        <div class="text-center">
            {{translate('No Template Found')}}
        </div>
        @else
        <div class="template-list row g-3">
            @foreach($templates->where('provider', \App\Enums\TemplateProvider::BEE_FREE->value) as $bTemplate)
            <div class="col-md-3 col-sm-6">
                <div class="template-card shadow-sm bg-body rounded">
                    <iframe srcdoc="{{$bTemplate->template_data['mail_body']}}" 
                            class="template-preview" 
                            frameborder="0"></iframe>
                    <div class="template-title text-center mt-2">
                        <strong>{{$bTemplate->name}}</strong>
                    </div>
                    <div class="template-card-footer d-flex justify-content-center mt-2">
                        <a id="use-template" href="javascript:void(0)" 
                           class="btn btn-primary btn-sm"
                           data-html="{{$bTemplate->template_data['mail_body']}}"
                           data-id="{{ $bTemplate->id }}">
                            {{translate('Use This')}}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
</div>

        @endif
    </div>
    @endif

    <div class="tab-pane fade" id="editor-template-content" role="tabpanel" aria-labelledby="edior-template">
        @if(count($templates) ==  0 || $templates->where('provider',\App\Enums\TemplateProvider::CK_EDITOR->value)->count() == 0)
        <div class="text-center">
            {{translate('No Template Found')}}
        </div>
        @else
        <div class="template-list">
            @foreach($templates->where('provider',\App\Enums\TemplateProvider::CK_EDITOR->value) as $bTemplate)
            <div class="template shadow-sm bg-body rounded">
                <div class="d-flex-sms">
                    <iframe srcdoc="{{ $bTemplate->template_data['mail_body'] }}" class="w-100 scrollable-auto"
                        frameborder="0"></iframe>
                    <div class="label">{{$bTemplate->name}}</div>
                </div>
                <div class="d-flex overlay-caption justify-content-center align-items-end">
                    <a id="use-template" href="javascript:void(0)" class="btn btn-primary btn-md mb-3"
                        data-html="{{$bTemplate->template_data['mail_body']}}"
                        data-id="{{ $bTemplate->id }}">{{translate('Use This')}}</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
