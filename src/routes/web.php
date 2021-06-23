<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'Dizatech\Identifier\Http\Controllers',
    'prefix'     => 'auth',
    'middleware' => ['web', 'guest']
], function () {
    Route::get('login', 'LoginController@show')->name('identifier.login');
});
