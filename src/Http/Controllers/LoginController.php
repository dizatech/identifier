<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function show($page = 'default')
    {
        return view('vendor.dizatech-identifier.identifier', [
            'page' => $page
        ]);
    }
}
