<?php
return [
    // the title of site (this will be used in title and help titles)
    'site_title' => env('APP_NAME', 'پیش فرض'),

    // full url of terms page (this will be used in register page)
    'terms_url' => 'http://localhost:8000/',

    // admin login redirect route name
    'admin_login_redirect' => 'panel',

    // user login redirect route name
    'user_login_redirect' => 'home',

    // where did you required the dizatech_identifier.scss file ?
    // this file must be included in webpack.js as well
    'css_public_path' => 'css/auth.css',

    // where did you required the dizatech_identifier.js file ?
    // this file must be included in webpack.js as well
    'js_public_path' => 'js/auth.js',

    // this config used to set witch mothod is availabe for users to login
    'login_methods' => ['username','email','mobile'],

    // OTP codes digit, by default it's 6-digit (secure and standard)
    'otp_digit' => 6,

    // User Model class path
    'user_model' => \App\Models\User::class
];
