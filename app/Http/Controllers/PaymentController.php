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
// $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'POST',
//            CURLOPT_HTTPHEADER => array(
//                'Content-Length: 0',
//                'Content-Type: application/vnd.ni-identity.v1+json',
//                'Authorization: Basic NmJjZDc3NzktMWYwMS00MDdhLWI4YzMtMjI5NmVhNDFjZTdmOjY5ZmE3MjI4LTE4NDEtNDdhZS05MDgzLWNmYzJlY2EyM2U5NQ=='
//            ),
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
////        echo $response;
//        
//        dd(json_decode($response));

    public function index() {
        $response = API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token', [], ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic NmJjZDc3NzktMWYwMS00MDdhLWI4YzMtMjI5NmVhNDFjZTdmOjY5ZmE3MjI4LTE4NDEtNDdhZS05MDgzLWNmYzJlY2EyM2U5NQ=='], true);
//        dd($response, $response->access_token);
        $outletReference = 'bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00';
        $data = [
            "firstName" => "Test",
            "lastName" => "Customer",
            "email" => "gaurav@netscapelabs.com",
            "transactionType" => "SALE",
            "emailSubject" => "Invoice from ACME Services LLC",
            "invoiceExpiryDate" => "2022-07-28",
            "items" => [
                [
                    "description" => "1 x large widget",
                    "totalPrice" => [
                        "currencyCode" => "AED",
                        "value" => 100
                    ],
                    "quantity" => 1
                ]
            ], "total" => ["currencyCode" => "AED", "value" => 100],
            "message" => "Thank you for shopping with ACME Services LLC. Please visit the link provided below to pay your bill. We will ship your order once we have confirmation of your payment."
        ];
        $response = API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com//invoices/outlets/' . $outletReference . '/invoice', $data, ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic ' . $response->access_token], true);

        dd($response);
//        return view('payment.index');
    }

}
