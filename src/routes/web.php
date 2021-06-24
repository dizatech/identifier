<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'Dizatech\Identifier\Http\Controllers',
    'prefix'     => 'auth',
    'middleware' => ['web', 'guest']
], function () {
    Route::get('/{page?}', 'LoginController@show')->name('identifier.login');
});
