<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'Dizatech\Identifier\Http\Controllers',
    'prefix'     => 'auth',
    'middleware' => ['web']
], function () {
    Route::get('/{page?}/{mobile?}', 'LoginController@show')->name('identifier.login')->middleware('guest');
    Route::post('/send/code/{mobile}', 'LoginController@sendCode')->name('identifier.send.code')->middleware('guest');
    Route::post('/confirm/code/{mobile}', 'LoginController@confirmCode')->name('identifier.confirm.code')->middleware('guest');
    Route::post('/check/mobile', 'LoginController@checkMobile')->name('identifier.check.mobile')->middleware('guest');
    Route::post('/logout', 'LoginController@logout')->name('identifier.logout');
});
