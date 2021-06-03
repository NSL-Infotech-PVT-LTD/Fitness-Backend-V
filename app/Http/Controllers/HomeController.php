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

    public function cal_days_in_year($year) {
	$days = 0;
	for ($month = 1; $month <= 12; $month++) {
	    $days = $days + cal_days_in_month(CAL_GREGORIAN, $month, $year);
	}
	return $days;
    }

    public function index() {
	$TotalCount = 0;
//	if ($request->ajax()) {
	$roleusers = \DB::table('role_user')->pluck('user_id')->toArray();
	$TotalCount = count($roleusers);
//	    if (!empty($request->status)) {
	$users = [];
//		if (strtolower($request->status) == "expired" || strtolower($request->status) == "active") {

	$data = \App\RoleUser::select('user_id', 'role_plan_id', 'created_at')->where('role_plan_id','<>',"null")->get();
	$plans = \App\RolePlans::pluck('fee_type', 'id');
//		    dd($plans);
	$data = collect($data->toArray())->flatten()->all();
//	dd($data);
	$today = date("Y-m-d");
//		    dd($today);
	$year = date("Y");
	$days = $this->cal_days_in_year($year);
//		    dd($days);
	for ($i = 0; $i < sizeof($data); $i += 3) {
	    $date = date_format(new \DateTime($data[$i + 2]), 'Y-m-d');
//dd($date);

	    if (strtolower($plans[$data[$i + 1]]) == 'yearly') {

		if ($days < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
		    array_push($users, $data[$i]);
		}
//			    dd($plans[$data[$i + 1]]);
	    } elseif (strtolower($plans[$data[$i + 1]]) == 'halfyearly') {
		dd('jhj');
		if (intval($days / 2) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
		    array_push($users, $data[$i]);
		}
		dd('jhj');
	    } elseif (strtolower($plans[$data[$i + 1]]) == 'quarterly') {
		if (intval($days / 4) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
		    array_push($users, $data[$i]);
		}
	    } elseif (strtolower($plans[$data[$i + 1]]) == 'mothly') {
		if (intval($days / 12) < intval(abs(strtotime($date) - strtotime($today)) / 86400)) {
		    array_push($users, $data[$i]);
		}
	    }
	}
// 
	    $roleusers = array_diff($roleusers, $users);
	    $TotalExpired=count($users);
	    $TotalActive = count($roleusers);
	

	return view('home',['TotalActive'=>$TotalActive,'TotalExpired'=>$TotalExpired]);
    }

}
