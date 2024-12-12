<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        dd($request->all());
        $validate = Validator::make([
            'name' => 'required',
            'email' => 'required|email',
            'password' => '<PASSWORD>',

        ]);
    }
}
