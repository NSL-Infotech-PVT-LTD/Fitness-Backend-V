<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;
use App\Tournament;
use App\Charts;
use App\Charts\UserLineChart;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

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
//    public function chartLineAjax(Request $request) {
//        $year = $request->has('year') ? $request->year : date('Y');
//        $users = User::select(\DB::raw("COUNT(*) as count"))
//                ->whereYear('created_at', $year)
//                ->groupBy(\DB::raw("Month(created_at)"))
//               ->pluck('count');
////        dd($users);
//
//        $chart = new UserLineChart;
//
//        $chart->dataset('New User Register Chart', 'line', $users)->options([
//            'fill' => 'true',
//            'borderColor' => '#51C1C0'
//        ]);
//
//        return $chart->api();
//    }

    public function indexold() {

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



        $api = url('/chart-line-ajax');
//dd($api);
        $chart = new UserLineChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])->load($api);
//        dd($chart);



        return view('home', compact('users', 'customer', 'tournament', 'chart'));
    }

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



       
       $revenueData = User::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');
//       dd($revenueData);

        return view('home', compact('users', 'customer', 'tournament', 'revenueData'));
    }

}
