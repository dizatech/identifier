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
    Route::post('/check/mobile', 'LoginController@checkMobile')->name('identifier.check.mobile')->middleware('guest');
    Route::post('/logout', 'LoginController@logout')->name('identifier.logout');
    Route::post('/check/username', 'LoginController@checkUsername')->name('identifier.check.username')->middleware('guest');
    Route::post('/confirm/recovery', 'LoginController@confirmRecoveryCode')->name('identifier.confirm.recovery.code')->middleware('guest');
    Route::post('/change/password', 'LoginController@changePassword')->name('identifier.change.password')->middleware('guest');
    Route::post('/login/password', 'LoginController@loginWithPassword')->name('identifier.login.password')->middleware('guest');

    Route::post('/set/cookie', 'LoginController@setCookie')->name('identifier.set.cookie')->middleware('guest');
    Route::post('/set/group/cookies', 'LoginController@setCookies')->name('identifier.set.group.cookies')->middleware('guest');
    Route::post('/get/cookie', 'LoginController@getCookie')->name('identifier.get.cookie')->middleware('guest');
    Route::post('/get/group/cookies', 'LoginController@getCookies')->name('identifier.get.group.cookies')->middleware('guest');
    Route::post('/forget/cookie', 'LoginController@forgetCookie')->name('identifier.forget.cookie')->middleware('guest');
});
