<?php
use Illuminate\Support\Facades\Route;
use Dizatech\Identifier\Http\Middlewares\IdentifierGuest;

Route::group([
    'namespace'  => 'Dizatech\Identifier\Http\Controllers',
    'prefix'     => 'auth',
    'middleware' => ['web', IdentifierGuest::class]
], function () {
    Route::get('/{page?}', 'LoginController@show')->name('identifier.login');
    Route::post('/send/code', 'LoginController@sendCode')->name('identifier.send.code');
    Route::post('/send/reg_code', 'LoginController@sendRegCode')->name('identifier.send.code');
    Route::post('/confirm/code', 'LoginController@confirmCode')->name('identifier.confirm.code');
    Route::post('/check/mobile', 'LoginController@checkMobile')->name('identifier.check.mobile');
    Route::post('/logout', 'LoginController@logout')->name('identifier.logout')->withoutMiddleware(IdentifierGuest::class);
    Route::post('/check/username', 'LoginController@checkUsername')->name('identifier.check.username');
    Route::post('/confirm/recovery', 'LoginController@confirmRecoveryCode')->name('identifier.confirm.recovery.code');
    Route::post('/change/password', 'LoginController@changePassword')->name('identifier.change.password');
    Route::post('/login/password', 'LoginController@loginWithPassword')->name('identifier.login.password');

    Route::post('/check/registered/user', 'LoginController@checkRegisteredUser')->name('identifier.check.registered.user');
    Route::post('/confirm/email/code', 'LoginController@confirmEmailCode')->name('identifier.confirm.email.code');

    Route::post('/set/cookie', 'LoginController@setCookie')->name('identifier.set.cookie');
    Route::post('/set/group/cookies', 'LoginController@setCookies')->name('identifier.set.group.cookies');
    Route::post('/get/cookie', 'LoginController@getCookie')->name('identifier.get.cookie');
    Route::post('/get/group/cookies', 'LoginController@getCookies')->name('identifier.get.group.cookies');
    Route::post('/forget/cookie', 'LoginController@forgetCookie')->name('identifier.forget.cookie');
});
