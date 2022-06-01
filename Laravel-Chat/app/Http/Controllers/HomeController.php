<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $auth_user = User::find(Auth::user()->id);
        $auth_user->active = 1;
        $auth_user->save();

        $user = User::all();    
        return view('home',compact('user'));
    }

    public function check_active($id)
    {
        $user = User::find($id);
        $active = $user->active;

        return response($active);
    }
}
