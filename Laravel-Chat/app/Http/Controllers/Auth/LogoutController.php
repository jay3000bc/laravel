<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LogoutController extends Controller
{
    //

    public function perform(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->active = 0;
        $user->save();
        Session::flush();
        
        Auth::logout();

        return redirect('/');
    }

}
