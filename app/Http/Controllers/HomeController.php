<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;

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

//        $get_role = DB::table('roles')->pluck('id')->toArray();
//      dd($get_role);
        $customer = DB::table('role_user')->where('role_id',2)->count();
        $service_provider = DB::table('role_user')->where('role_id',3)->count();
        return view('home', compact('customer','service_provider'));
    }

}
