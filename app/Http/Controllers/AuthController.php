<?php

namespace App\Http\Controllers;

use Auth;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        $username = $request->username;
        $password = $request->password;

        if($validation->fails()) {
            return redirect()->to('/login')->withErrors($validation)->withInput($request->except(['password']));
        }

        if(Auth::attempt(['email' => $username, 'password' => $password])) {
            return redirect()->to('/cms/dashboard');
        }
        
        return redirect()->to('/login')->withErrors('Username/Password tidak sesuai');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
