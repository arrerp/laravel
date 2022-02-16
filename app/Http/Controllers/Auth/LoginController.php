<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Util\Util;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($request->only('email', 'password'))){

            Auth::user()->url_img = Util::ImageToBase64(Auth::user()->url_img);

            return response()->json(Auth::user(), 200);
        }

        throw ValidationException::withMessages([
            'login' => ['Informações de logins incorretas']
        ]);
    }
}
