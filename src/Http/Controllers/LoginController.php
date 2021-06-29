<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;
use Dizatech\Identifier\Facades\NotifierLoginFacade;
use Dizatech\Identifier\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show($page = 'default')
    {
        if ($page != 'default' &&
            $page != 'register' &&
            $page != 'recovery' &&
            is_null(\request()->cookie('notifier_username'))){
            return redirect(route('identifier.login'));
        }
        // check cookie , in change_password page
        if ($page == 'change_password'){
            if (\request()->cookie('identifier_verified_recovery') != 'user_verified')
            {
                return redirect(route('identifier.login'));
            }
        }
        return view('vendor.dizatech-identifier.identifier', [
            'page' => $page
        ]);
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'mobile']
        ],[
            'mobile.required' => 'فیلد موبایل الزامی است.'
        ]);
        $result = NotifierLoginFacade::sendSMS($request->mobile);
        return json_encode([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }

    public function confirmCode(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'mobile'],
            'code' => ['required']
        ],[
            'mobile.required' => 'فیلد موبایل الزامی است.',
            'code.required' => 'فیلد کد تایید الزامی است.'
        ]);
        $url = '';
        $result = NotifierLoginFacade::confirmSMS($request->mobile, $request->code);
        if ($result->status == 200){
            $attempLogin = NotifierLoginFacade::attempLogin($result->user);
            if ($attempLogin->status == 200){
                if ($result->user->is_admin == 1){
                    $url = route(config('dizatech_identifier.admin_login_redirect'));
                }else{
                    $url = route(config('dizatech_identifier.user_login_redirect'));
                }
                return json_encode([
                    'status' => $result->status,
                    'message' => $result->message,
                    'url' => $url
                ]);
            }
        }else{
            return json_encode([
                'status' => $result->status,
                'message' => $result->message,
            ]);
        }
    }

    public function checkMobile(Request $request)
    {
        $request->validate([
            'mobile' => ['required', 'mobile']
        ],[
            'mobile.required' => 'فیلد موبایل الزامی است.'
        ]);
        $result = NotifierLoginFacade::checkMobileExist($request->mobile);
        return json_encode([
            'type' => $result,
        ]);
    }

    public function checkUsername(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string']
        ],[
            'username.required' => 'فیلد موبایل یا ایمیل الزامی است.'
        ]);
        $type = 'undefined';
        $message = '';
        if (NotifierLoginFacade::isMobile($request->username)){
            $type = 'mobile';
            $result = NotifierLoginFacade::sendSMS($request->username, 'recovery_mode');
        }
        if (NotifierLoginFacade::isEmail($request->username)){
            $type = 'email';
            $result = NotifierLoginFacade::sendConfirmEmail($request->username);
        }
        if ($type == 'undefined'){
            $result = array();
            $result['status'] = 404;
            $result['message'] = 'تنها ایمیل یا موبایل قابل قبول است.';
        }
        return json_encode([
            'status' => $result['status'],
            'type' => $type,
            'message' => $result['message']
        ]);
    }

    public function confirmRecoveryCode(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'code' => ['required'],
            'type' => ['required', 'in:email,mobile']
        ],[
            'username.required' => 'فیلد نام کاربری الزامی است.',
            'code.required' => 'فیلد کد تایید الزامی است.',
            'type.required' => 'فیلد نوع بازیابی الزامی است.',
        ]);
        $result = (object) array();
        if ($request->type == 'mobile'){
            $result = NotifierLoginFacade::confirmSMS($request->username, $request->code, 'recovery_mode');
        }
        if ($request->type == 'email'){
            $result = NotifierLoginFacade::confirmEmail($request->username, $request->code);
        }
        if (empty($result)){
            $result->status = 400;
            $result->message = 'کاربر پیدا نشد.';
        }
        return json_encode([
            'status' => $result->status,
            'message' => $result->message,
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        if (\request()->cookie('identifier_verified_recovery') != 'user_verified'
            && is_null(\request()->cookie('notifier_username'))
            && is_null(\request()->cookie('notifier_recovery_type')))
        {
            return redirect(route('identifier.login'));
        }
        $type = \request()->cookie('notifier_recovery_type');
        $username = \request()->cookie('notifier_username');
        $url = '';
        $result = (object) array();
        if ($type == 'mobile'){
            $result = NotifierLoginFacade::changePasswordViaMobile($username, $request->new_password);
        }
        if ($type == 'email'){
            $result = NotifierLoginFacade::changePasswordViaEmail($username, $request->new_password);
        }
        if (empty($result) || is_null($result)){
            $result->status = 400;
            $result->message = 'کاربر پیدا نشد.';
        }else{
            if ($result->user->is_admin == 1){
                $url = route(config('dizatech_identifier.admin_login_redirect'));
            }else{
                $url = route(config('dizatech_identifier.user_login_redirect'));
            }
        }
        return json_encode([
            'status' => $result->status,
            'message' => $result->message,
            'url' => $url
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function setCookie(Request $request){
        $request->validate([
            'cookie_name' => ['required','string'],
            'cookie_value' => ['required','string'],
        ]);
        return response()
            ->json(['success' => true], 200)
            ->withCookie(cookie($request->cookie_name, $request->cookie_value, 120));
    }

    public function setCookies(Request $request){
        $request->validate([
            're_cookies' => ['required','array']
        ]);
        $cookies = array();
        foreach ($request->re_cookies as $key => $value){
            \Cookie::queue($key, $value, 30);
        }
        return json_encode([
            'status' => 200
        ]);
    }

    public function getCookie(Request $request){
        $request->validate([
            'cookie_name' => ['required','string']
        ]);
        $value = $request->cookie($request->cookie_name);
        return json_encode([
            'cookie' => $value
        ]);
    }

    public function getCookies(Request $request){
        $request->validate([
            'cookie_names' => ['required','array']
        ]);
        $cookies = [];
        foreach ($request->cookie_names as $cookie_name){
            $cookies[$cookie_name] = $request->cookie($cookie_name);
        }
        return json_encode([
            'cookies' => $cookies
        ]);
    }

    public function forgetCookie()
    {
        $request->validate([
            'cookie_name' => ['required','string']
        ]);
        \Cookie::forget($request->cookie_name);
        return json_encode([
            'status' => 200
        ]);
    }
}
