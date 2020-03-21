<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;
use App\Tournament;
Use ConsoleTVs\Charts;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {

        $users = [];
        foreach (\App\Role::all() as $role):
            if ($role->name == 'super admin')
                continue;
            $users[$role->name]['role_id'] = $role->id;
            $users[$role->name]['count'] = User::wherein('id', DB::table('role_user')->where('role_id', $role->id)->pluck('user_id'))->get()->count();
        endforeach;


        $roleusersDe = \DB::table('role_user')->where('role_id', \App\Role::where('name', 'Customer')->first()->id)->pluck('user_id');
//        dd($roleusersDe);
        $customer = \App\User::where('id', $roleusersDe)->first()->id;

//        dd($customer);
//        dd($dealer);
        $tournament = Tournament::all();
        return view('home', compact('users', 'customer', 'tournament'));
    }

    public function makeChart($type) {
       
       
                $users = User::where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
                        ->get();
                $chart = Charts::database($users, 'bar', 'highcharts')
                        ->title("Monthly new Register Users")
                        ->elementLabel("Total Users")
                        ->dimensions(1000, 500)
                        ->responsive(true)
                        ->groupByMonth(date('Y'), true);
              
                
        
        return view('home', compact('chart'));
    }

}
