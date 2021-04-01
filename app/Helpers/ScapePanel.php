<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ScapePanel {

    /**
     * default Parameters
     * @param Class $table Object of class name Blueprint
     * @param Bool $userParent Set <b>TRUE</b> to set users id as a foreign key
     * @author Gaurav Sethi <gaurav@netscapelabs.com>
     * @return default Parameters
     */
    public static function getUserRoles($roleID = null, $type = 'key') {
        $roles = config('ScapePanel.user_roles');
        if ($type == 'key'):
            return ($roleID != null) ? $roles[$roleID] : $roles;
        else:
            $rolesM = [];
            foreach ($roles as $key => $role):
                if ((strpos($role, $roleID) !== false)):
                    $rolesM[$key] = $role;
                endif;
            endforeach;
            return $rolesM;
        endif;
    }

    /**
     * 
     * @param object $user object of User Model
     * @param int $bookingID Booking Id which you're booking
     * @return boolean
     */
//    public static function paymentFunction(\App\User $user, $bookingID, $price) {
    public static function paymentFunction(\App\User $user, $bookingID = null, $price = null) {
//        dd($userDetails,$planName,$price);
//        dd($user->first_name,$user->last_name);
        if ($bookingID == null)
            $bookingID = $user->id;
        if ($price == null)
            $price = \App\User::whereId($user->id)->first()->role->current_plan->fee;
//        $NPItoken = 'NmJjZDc3NzktMWYwMS00MDdhLWI4YzMtMjI5NmVhNDFjZTdmOjY5ZmE3MjI4LTE4NDEtNDdhZS05MDgzLWNmYzJlY2EyM2U5NQ==';
//        $NPItoken = 'YTA5OWI1YjUtYjRkNy00ZDIzLTlmYzgtMGU5OTM4ZWVlMDQ5OjNlMTE4NzVmLWZmZWYtNGE4OC1hMGJkLTFiZWE5ZTU4MDc3YQ==';
        $NPItoken = 'ZjQ1N2JjMjAtMmQ3Yi00YTNlLTg0NTItOGFkOGFmNTYxMWQ5OjJkZTdiNzdjLWNlZDQtNGU0OC1hM2Q5LTQ3NjQ4ZjBmMGE0ZQ==';
        $response = \App\Http\Controllers\API\ApiController::CURL_API('POST', 'https://api-gateway.ngenius-payments.com/identity/auth/access-token', [], ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic ' . $NPItoken], true);
//        $response = \App\Http\Controllers\API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token', [], ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic ' . $NPItoken], true);
//        dd($response);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-gateway.ngenius-payments.com/invoices/outlets/e70039f6-ad05-40a5-9a78-79e89d21f563/invoice',
//            CURLOPT_URL => 'https://api-gateway.sandbox.ngenius-payments.com//invoices/outlets/bc0cfc35-f3a7-4cbc-8b47-eddaa0559b00/invoice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
      "firstName":"' . $user->first_name . '",
      "lastName":"' . $user->last_name . '",
      "email":"' . $user->email . '",
      "transactionType":"SALE",
      "emailSubject": "Invoice from VOLT Services LLC",
      "invoiceExpiryDate": "' . \Carbon\Carbon::now()->addDays(2)->format('Y-m-d') . '",
      "items":[
        {
          "description":"' . $bookingID . '",
          "totalPrice":{
            "currencyCode":"AED",
            "value":' . (int) $price * 100 . '
          },
          "quantity": 1
        }
      ],
      "total":{
        "currencyCode":"AED",
        "value":' . (int) $price * 100 . '
      },
      "message":"Thank you for shopping with VOLT Services LLC. Please visit the link provided below to pay your bill."
    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.ni-invoice.v1+json',
                'Authorization: Bearer ' . $response->access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        dd($response);
//        dd($response, $response->_links->payment->href);
        if (isset($response->_links->payment->href))
            return $response->_links->payment->href;
        else
            return false;
    }

}
