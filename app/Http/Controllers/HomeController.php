<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;
use App\Tournament;
use Carbon\Carbon;


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
        
        
        
        
        //graph code starts//
        $now = Carbon::now('Asia/Kolkata');
        $present_month = $now->month;
        $present_year = $now->year;


//        $ending_date = $now;
        $monthlyAmountArray = [];
        $amt = 0;
        for ($i = 1; $i <= $present_month; $i++) {
            $starting_date = $present_year . '-' . $i . '-01 00:00:01';
            $ending_date = $present_year . '-' . $i . '-31 23:59:59';

            $transactionsArray = \App\EnrollTournaments::whereBetween('created_at', [$starting_date, $ending_date]);
//dd($transactionsArray);
            foreach ($transactionsArray as $array) {
                $amt = $amt + $array['amount'];
            }
            array_push($monthlyAmountArray, $amt);
            $amt = 0;
        }

        $revenueData = implode(',', $monthlyAmountArray);
//        dd($revenueData);
       //graph code ends//
       
        
        
        
      $data=[21,121,12,232,121,121,334,542,434,234,24,34];

        return view('home', compact('users', 'customer', 'tournament', 'revenueData','data'));
    }
    
  
}
