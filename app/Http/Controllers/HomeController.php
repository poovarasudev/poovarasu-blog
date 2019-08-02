<?php

namespace App\Http\Controllers;

use App\Post;
use App\Role;
use App\Tag;
use Illuminate\Http\Request;

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
        if (auth()->user()->hasAnyRole(Role::all())){
            if (auth()->user()->roles->first()->name == 'admin'){
                return redirect('/dashboard');
            } else{
                return redirect('/post');
            }
        } else{
            return redirect('/norolepage');
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function noRolePage()
    {
        return view('errors.no_role_page');
    }
}
