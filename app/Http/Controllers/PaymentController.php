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
//            $json = '{"eventId":"0827cb99-994e-4afe-9150-19288d3ba491","eventName":"CAPTURED","order":{"_id":"urn:order:cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9","_links":{"self":{"href":"http://transaction-service/transactions/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/orders/cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9"},"tenant-brand":{"href":"http://config-service/config/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/configs/tenant-brand"},"merchant-brand":{"href":"http://config-service/config/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/configs/merchant-brand"}},"type":"SINGLE","merchantDefinedData":{},"action":"SALE","amount":{"currencyCode":"AED","value":2000},"language":"en","merchantAttributes":{},"emailAddress":"gaurav@netscapelabs.com","reference":"cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9","outletId":"bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00","createDateTime":"2021-02-15T06:54:09.115Z","paymentMethods":{"card":["MASTERCARD","VISA"]},"orderSummary":{"items":[{"description":"663","totalPrice":{"currencyCode":"AED","value":200000},"quantity":1}],"total":{"currencyCode":"AED","value":2000}},"billingAddress":{"firstName":"Yyy","lastName":"Yyyyy"},"referrer":"urn:invoice:1acb8f57-c522-4aae-b66f-7c33f91001f2","formattedAmount":"Ø¯.Ø¥.â€ 20","formattedOrderSummary":{"total":"Ø¯.Ø¥.â€ 20","items":["663 x 1              Ø¯.Ø¥.â€ 2,000"]},"_embedded":{"payment":[{"_id":"urn:payment:a3ebe51f-3fe6-4f9b-b24b-b5f0f3e20846","_links":{"self":{"href":"http://transaction-service/transactions/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/orders/cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9/payments/a3ebe51f-3fe6-4f9b-b24b-b5f0f3e20846"},"curies":[{"name":"cnp","href":"http://transaction-service/docs/rels/{rel}","templated":true}]},"paymentMethod":{"expiry":"2021-02","cardholderName":"ram lal","name":"VISA","pan":"401200******1112"},"state":"CAPTURED","amount":{"currencyCode":"AED","value":2000},"updateDateTime":"2021-02-15T07:00:58.213Z","outletId":"bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00","orderReference":"cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9","authResponse":{"authorizationCode":"AB0012","success":true,"resultCode":"00","resultMessage":"Successful approval/completion or that VIP PIN verification is valid","mid":"200200000564","rrn":"104507830452"},"3ds":{"status":"SUCCESS","eci":"06","eciDescription":"Not enrolled","summaryText":"Authentication was attempted but was not or could not be completed; possible reasons being either the card or its Issuing Bank has yet to participate in 3DS."},"_embedded":{"cnp:capture":[{"_links":{"self":{"href":"http://transaction-service/transactions/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/orders/cf374a9c-5d8b-44d1-90cb-fbe1a0f3c1b9/payments/a3ebe51f-3fe6-4f9b-b24b-b5f0f3e20846/captures/46ed6cde-cd99-43e2-a9ed-39f8626c0952"}},"amount":{"currencyCode":"AED","value":2000},"createdTime":"2021-02-15T07:00:58.213Z","state":"SUCCESS"}]}}]}}}';
            $order = json_decode($json);
//            dd();
            if (!isset($order->order->orderSummary->items[0]->description))
                dd('no booking id found', file_put_contents("webhook_response_failure.txt", "No booking id found"));
            $bookingId = $order->order->orderSummary->items[0]->description;
            $booking = \App\Booking::where('id', $bookingId);
            if ($booking->count() > 0):
                $booking = $booking->first();
                if ($booking->model_type == 'users'):
                    $user = User::where('id', $booking->model_id);
                    if ($user->count() > 0):
                        $user->update(['payment_status' => $order->eventName, 'payment_params' => json_encode($order)]);
//                    dd($user->first()->id, $order->eventName, $order);
                    endif;
                endif;
                $bookingUpdate = \App\Booking::where('id', $bookingId);
                if ($bookingUpdate->count() > 0):
                    $bookingUpdateData = \App\Booking::where('id', $bookingId)->first();
                    if (in_array($order->eventName, \App\Booking::$_BookingApprovedStatus)):
                        if (in_array($bookingUpdateData->model_type, ['sessions', 'trainer_users'])):
                            $userGet = User::whereId($bookingUpdateData->created_by)->first();
//                            if ($userGet->remember_token == 1):
                            if ($bookingUpdateData->status == '0'):
                                if ($bookingUpdateData->model_type == 'sessions'):
                                    $user = \App\User::findOrFail($bookingUpdateData->created_by);
                                    $user->my_sessions = $userGet->my_sessions + $bookingUpdateData->session;
                                    $user->remember_token = $userGet->remember_token + 1;
                                    $user->save();
                                    $titleNotification = 'We have received payment of your Group classes';
                                    $bodyNotification = 'Now You Can Book Your Classes.';
                                endif;
                                if ($bookingUpdateData->model_type == 'trainer_users'):
                                    $user = \App\User::findOrFail($bookingUpdateData->created_by);
                                    $user->trainer_slot = (int) $userGet->trainer_slot + $bookingUpdateData->hours;
                                    $user->trainer_id = $bookingUpdateData->model_id;
                                    $user->save();
                                    $titleNotification = 'We have received payment of your PT';
                                    $bodyNotification = 'Now Your Training Session Can Go Ahead';
                                endif;
                                \App\Http\Controllers\API\ApiController::pushNotifications(['title' => $titleNotification, 'body' => $bodyNotification, 'data' => ['target_id' => $bookingId, 'target_model' => 'Booking', 'data_type' => 'Booking']], $bookingUpdateData->created_by, TRUE);
                            endif;
                        endif;
                    endif;
                    $bookingUpdate = \App\Booking::where('id', $bookingId);
                    $updateD = [];
                    $updateD = ['payment_status' => $order->eventName, 'payment_params' => json_encode($order)];
                    if (in_array($order->eventName, \App\Booking::$_BookingApprovedStatus)):
                        $updateD += ['status' => '1'];
                    endif;
                    $bookingUpdate->update($updateD);
                    dd($bookingUpdate->id, $order->eventName, $order);
                endif;
            endif;
            dd($order, $bookingId, $booking->count(), $booking->get());
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
