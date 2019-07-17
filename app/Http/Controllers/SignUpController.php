<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{

    public function index(){
        return view('signup');
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function signup(Request $request)
    {

        session()->put('screen_name', $request->screen_name);
        session()->put('email', $request->email);
        session()->put('password', Hash::make($request->password));

        return redirect('oauth');
        
    }
}
