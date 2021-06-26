<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;
use Dizatech\Identifier\Facades\NotifierLoginFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show($page = 'default')
    {
        return view('vendor.dizatech-identifier.identifier', [
            'page' => $page
        ]);
    }

    public function sendCode(Request $request, $mobile)
    {
        if (is_null($request->mobile)){
            $request->mobile = $mobile;
        }
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

    public function confirmCode(Request $request, $mobile)
    {
        if (is_null($request->mobile)){
            $request->mobile = $mobile;
        }
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
            NotifierLoginFacade::attempLogin($result->user);
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
}
