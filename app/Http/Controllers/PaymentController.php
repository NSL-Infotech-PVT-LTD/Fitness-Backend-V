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

    public function updateByHook() {
        try {
            //$json = $_REQUEST;
            $json = file_get_contents("php://input");
            $order = json_decode($json);
            file_put_contents("webhook_response.txt", $json);
        } catch (PDOException $e) {

            file_put_contents("webhook_response_failure.txt", "No Response");
        }
        dd('process doone');
    }

    public function index() {
        $response = API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token', [], ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic NmJjZDc3NzktMWYwMS00MDdhLWI4YzMtMjI5NmVhNDFjZTdmOjY5ZmE3MjI4LTE4NDEtNDdhZS05MDgzLWNmYzJlY2EyM2U5NQ=='], true);
//        dd($response, $response->access_token);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-gateway.sandbox.ngenius-payments.com//invoices/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/invoice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
      "firstName":"Hari",
      "lastName":"Krishnan",
      "email":"gaurav1@netscapelabs.com",
      "transactionType":"SALE",
      "emailSubject": "Invoice from ACME Services LLC",
      "invoiceExpiryDate": "2021-01-10",
      "items":[
        {
          "description":"1 x large widget",
          "totalPrice":{
            "currencyCode":"AED",
            "value":100
          },
          "quantity": 1
        }
      ],
      "total":{
        "currencyCode":"AED",
        "value":100
      },
      "message":"Thank you for shopping with ACME Services LLC. Please visit the link provided below to pay your bill."
    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.ni-invoice.v1+json',
                'Authorization: Bearer ' . $response->access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        dd($response, $response->_links->payment->href);
//        return view('payment.index');
    }

}
