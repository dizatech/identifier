<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function show($page = 'default')
    {
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
        \Notifier::driver('ghasedak')
            ->templateId(1)
            ->params(['param1' => 'passwdsd12ds'])
            ->options(['method' => 'otp','ghasedak_template_name' => 'registration',
                'hasPassword' => 'yes', 'receiver' => $request->mobile])
            ->send();
        return json_encode([
            'status' => 200,
            'message' => 'پیامک کد برایتان ارسال شد.'
        ]);
    }
}
