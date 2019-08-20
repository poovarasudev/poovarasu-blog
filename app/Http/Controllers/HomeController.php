<?php

namespace App\Http\Controllers;

use App\Exports\PostExport;
use App\Post;
use App\Role;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'noRolePage']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->hasAnyRole(Role::all())) {
            if (auth()->user()->roles->first()->name == 'admin') {
                return redirect('/dashboard');
            } else {
                return redirect('/post');
            }
        } else {
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

    public function getDashboardDatas()
    {
        $from = Carbon::createMidnightDate(Carbon::now()->year, 1, 1);
        $to = Carbon::createMidnightDate(Carbon::now()->year, 12, 1);
        $fromYear = Carbon::createMidnightDate(Carbon::now()->year - 8, 1, 1);
        $toYear = Carbon::createMidnightDate(Carbon::now()->year + 1, 1, 1);
        $monthPosts = Post::selectRaw('MONTH(created_at) AS month, COUNT(*) AS count')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month')
            ->get();
        $monthUsers = User::selectRaw('MONTH(created_at) AS month, COUNT(*) AS count')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('month')
            ->get();
        $i=0;
        foreach ($monthPosts as $value){
            $monthPostsArray[$i] = array($value->month, $value->count);
            $i++;
        }
        $i=0;
        foreach ($monthUsers as $value){
            $monthUsersArray[$i] = array($value->month, $value->count);
            $i++;
        }
        $yearPosts = Post::selectRaw('YEAR(created_at) AS year, COUNT(*) AS count')
            ->whereBetween('created_at', [$fromYear, $toYear])
            ->groupBy('year')
            ->get();
        $yearUsers = User::selectRaw('YEAR(created_at) AS year, COUNT(*) AS count')
            ->whereBetween('created_at', [$fromYear, $toYear])
            ->groupBy('year')
            ->get();
        $i=0;
        foreach ($yearPosts as $value){
            $yearPostsArray[$i] = array($value->year, $value->count);
            $i++;
        }
        $i=0;
        foreach ($yearUsers as $value){
            $yearUsersArray[$i] = array($value->year, $value->count);
            $i++;
        }
        $result=array(array($monthPostsArray, $monthUsersArray), array($yearPostsArray, $yearUsersArray));

        return $result;
    }
}
