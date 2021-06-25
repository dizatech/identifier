<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'Dizatech\Identifier\Http\Controllers',
    'prefix'     => 'auth',
    'middleware' => ['web']
], function () {
    Route::get('/{page?}', 'LoginController@show')->name('identifier.login')->middleware('guest');
    Route::post('/send/code', 'LoginController@sendCode')->name('identifier.send.code')->middleware('guest');
    Route::post('/confirm/code', 'LoginController@confirmCode')->name('identifier.confirm.code')->middleware('guest');
    Route::post('/logout', 'LoginController@logout')->name('identifier.logout');
});
