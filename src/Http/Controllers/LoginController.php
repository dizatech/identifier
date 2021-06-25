<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;
use Dizatech\Identifier\Facades\NotifierLoginFacade;
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
        $result = NotifierLoginFacade::sendSMS($request->mobile);
        return json_encode([
            'status' => $result['status'],
            'message' => $result['message']
        ]);
    }
}
