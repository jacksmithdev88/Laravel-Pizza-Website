<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function login(Request $request)
    {
        $password = $request->input('password');
        $username = $request->input('username');

        $users = DB::select("select * from users where username='" . $username . "'");
        foreach ($users as $user) {
            if (Hash::check($password,$user->password)) {
                session(['username' => $username]);
                return redirect('')->withInput($request->input());
            }
        }
        return redirect('')->with('loginError', 'Invalid login.')->withInput($request->input());
    }

    public function logout()
    {
        session()->forget('username');
        return redirect('');
    }
}
