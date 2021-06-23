<?php

namespace Dizatech\Identifier\Http\Controllers;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function show()
    {
        return view('vendor.dizatech-identifier.identifier');
    }
}
