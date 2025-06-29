@extends('user.layouts.auth')

@section('content')
<section class="auth" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="container-fluid px-0">
        <div class="auth-wrapper">
            <div class="row g-0">
                <div class="col-lg-6 mx-auto">
                    <div class="auth-right text-center">
                        <a href="{{ url('/') }}">
                            <img src="{{ showImage(config('setting.file_path.site_logo.path') . '/' . site_settings('site_logo'), config('setting.file_path.site_logo.size')) }}" class="logo-lg mb-4" alt="">
                        </a>
                        <div class="auth-form-wrapper">
                            <h3 class="text-success">{{ translate("Unsubscribed Successfully") }}</h3>
                            <p class="mb-4">{{ translate("You have successfully unsubscribed from our mailing list. We're sad to see you go, but you can always come back.") }}</p>
                            <a href="{{ route('home') }}" class="i-btn btn--primary bg--gradient btn--xl rounded-3 w-100 mt-2">
                                {{ translate("Return to Home") }}
                                <i class="ri-arrow-right-line fs-18"></i>
                            </a>
                            <div class="mt-3">
                                <p class="fw-semibold">
                                    {{ translate("Need help or have questions?") }}
                                    <a href="{{ route('contact') }}" class="text-primary text-decoration-underline">
                                        {{ translate("Contact Us") }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
