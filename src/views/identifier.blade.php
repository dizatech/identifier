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
            <div class="col-md-6 mx-auto main-container">
                <a href="{{ route(config('dizatech_identifier.site_home_route')) }}" class="back-btn">بازگشت</a>
                <div class="card default_page" style="display:@if($page == 'default') block @else none @endif">
                    <x-login-component page="default"></x-login-component>
                </div>
                <div class="card register_page" style="display:@if($page == 'register') block @else none @endif">
                    <x-login-component page="register"></x-login-component>
                </div>
                <div class="card login_page" style="display:@if($page == 'login') block @else none @endif">
                    <x-login-component page="login"></x-login-component>
                </div>
                <div class="card forgot_page" style="display:@if($page == 'forgot') block @else none @endif">
                    <x-login-component page="forgot"></x-login-component>
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
