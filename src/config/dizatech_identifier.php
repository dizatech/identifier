<?php
return [
    // the title of site (this will be used in title and help titles)
    'site_title' => 'پیش فرض',

    // full url of terms page (this will be used in register page)
    'terms_url' => 'http://localhost:8000/',

    // where did you required the dizatech_identifier.scss file ?
    // this file must be included in webpack.js as well
    'css_public_path' => 'css/auth.css',

    // where did you required the dizatech_identifier.js file ?
    // this file must be included in webpack.js as well
    'js_public_path' => 'js/auth.js',

    // this config used to set witch mothod is availabe for users to login
    'login_methods' => ['username','email','mobile'],

    // User Model class path
    'user_model' => \App\Models\User::class
];
