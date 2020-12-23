<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Role;
use App\User;
use App\Tournament;
use Carbon\Carbon;

class PaymentController extends Controller {

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
        API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token', ['grant_type' => 'client_credentials'], ['Content-Type: x-www-form-urlencoded', 'Authorization: Basic NGY4YzE1YzUtYTJhNS00OTZiLThkNmItZjU3NGM0MmRjZTAxOmY2MWExODUyLWRlZDAtNDQwZi1iZDZlLThhMmE5YmQ0NmRmZA=='], true);

        dd('ss');
//        return view('payment.index');
    }

}
