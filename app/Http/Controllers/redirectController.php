<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class redirectController extends Controller
{
    public function redirect(Request $request) {
        return back()->with('redirectError', 'You have been returned to the home page.')->withInput($request->input());
    }
}
