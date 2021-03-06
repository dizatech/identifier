<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ورود به {{ config('dizatech_identifier.site_title') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix(config('dizatech_identifier.css_public_path')) }}" rel="stylesheet">

</head>

<body>
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 mx-auto main-container">
                <div class="loading_overlay justify-content-center align-items-center d-none">
                    <div class="text-center loading_content">
                        <div class="spinner-border text-danger" role="status">
                            <span class="sr-only"></span>
                        </div>
                        <div class="mt-2 text-danger">
                            لطفا منتظر بمانید...
                        </div>
                    </div>
                </div>
                <a href="#" class="back-btn">بازگشت</a>
                <div class="card default" style="display:@if($page == 'default') block @else none @endif">
                    <x-login-component page="default"></x-login-component>
                </div>
                <div class="card register" style="display:@if($page == 'register') block @else none @endif">
                    <x-login-component page="register"></x-login-component>
                </div>
                <div class="card code" style="display:@if($page == 'code') block @else none @endif">
                    <x-login-component page="code"></x-login-component>
                </div>
                <div class="card login" style="display:@if($page == 'login') block @else none @endif">
                    <x-login-component page="login"></x-login-component>
                </div>
                <div class="card not_registered" style="display:@if($page == 'not_registered') block @else none @endif">
                    <x-login-component page="not_registered"></x-login-component>
                </div>
                <div class="card recovery" style="display:@if($page == 'recovery') block @else none @endif">
                    <x-login-component page="recovery"></x-login-component>
                </div>
                <div class="card password" style="display:@if($page == 'password') block @else none @endif">
                    <x-login-component page="password"></x-login-component>
                </div>
                <div class="card recovery_code" style="display:@if($page == 'recovery_code') block @else none @endif">
                    <x-login-component page="recovery_code"></x-login-component>
                </div>
                <div class="card change_password" style="display:@if($page == 'change_password') block @else none @endif">
                    <x-login-component page="change_password"></x-login-component>
                </div>
                <div class="card email_code" style="display:@if($page == 'email_code') block @else none @endif">
                    <x-login-component page="email_code"></x-login-component>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>let baseUrl = "{{ URL::to('/') }}";</script>
<script src="{{ mix(config('dizatech_identifier.js_public_path')) }}"></script>
@yield('script')


</body>
</html>
