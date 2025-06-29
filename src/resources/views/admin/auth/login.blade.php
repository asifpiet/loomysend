@extends('admin.layouts.auth')

@section('content')
    <main class="auth">
        <div class="container-fluid px-0">
            <div class="row gx-0 gy-md-4 gy-0">
                <div class="col-lg-12">
                    <div class="auth-left">
                    <a href="{{ url("/") }}">
                        <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="" height="70" width="250">
                    </a>
                    <div class="auth-form-wrapper">
                        <form action="{{route('admin.authenticate')}}" method="POST" class="auth-form">
                        @csrf
                        <h3 class="mb-4 text-center">{{ translate("Sign in") }}</h3>
                        <div class="form-inner mb-4">
                            <label for="username" class="form-label">{{ translate("Username") }}</label>
                            <div class="auth-input-wrapper">
                            <span class="auth-input-icon">
                                <i class="ri-mail-line"></i>
                            </span>
                            <input type="text" id="username" value="{{ env("APP_MODE") == 'demo' ? env("APP_ADMIN_USERNAME") : '' }}" name="username" class="form-control" placeholder="Enter your username" aria-label="username" />
                            </div>
                        </div>
                        <div class="form-inner mb-4">
                            <label for="password" class="form-label">{{ translate("Password") }} </label>
                            <div class="auth-input-wrapper">
                            <span class="auth-input-icon">
                                <i class="ri-lock-line"></i>
                            </span>
                            <input type="password" id="password" value="{{ env("APP_MODE") == 'demo' ? env("APP_ADMIN_PASSWORD") : '' }}" name="password" class="form-control" placeholder="Enter password" aria-label="password" />
                            <span class="auth-eye-icon reveal-password">
                                <i class="ri-eye-line"></i>
                            </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <a href="{{route('admin.password.request')}}" class="fs-15 text-info text-decoration-underline">{{ translate("Forgot Password") }}</a>
                        </div>
                        <button type="submit" class="i-btn btn--primary btn--lg w-100"> {{ translate("Sign In") }} <i class="ri-arrow-right-line fs-18"></i>
                        </button>
                        </form>
                    </div>
                    <!--<div class="auth-footer">-->
                    <!--    <div class="footer-content">-->
                    <!--    <p class="copy-write">-->
                    <!--        <a href="https://igensolutionsltd.com/" class="text--primary">{{ site_settings("copyright") }}-->
                    <!--    </p>-->
                    <!--    <div class="footer-right">-->
                    <!--        <ul>-->
                    <!--        <li>-->
                    <!--            <a href="https://support.igensolutionsltd.com">{{ translate("Support") }}</a>-->
                    <!--        </li>-->
                    <!--        </ul>-->
                    <!--        <span class="i-badge info-solid">{{ translate("Version: ").site_settings("app_version") }} </span>-->
                    <!--    </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push("script-push")
<script>
    $(document).ready(function() {
        $('.reveal-password').on('click', function() {
            let input = $(this).closest('span').siblings('input[type="password"], input[type="text"]');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    });
</script>
@endpush