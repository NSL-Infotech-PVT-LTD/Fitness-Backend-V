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

    public static function paymentFunction($userDetails, $planName, $price) {
//        dd($userDetails,$planName,$price);
        $NPItoken = 'NmJjZDc3NzktMWYwMS00MDdhLWI4YzMtMjI5NmVhNDFjZTdmOjY5ZmE3MjI4LTE4NDEtNDdhZS05MDgzLWNmYzJlY2EyM2U5NQ==';
        $response = \App\Http\Controllers\API\ApiController::CURL_API('POST', 'https://api-gateway.sandbox.ngenius-payments.com/identity/auth/access-token', [], ['Content-Length: 0', 'Content-Type: application/vnd.ni-identity.v1+json', 'Authorization: Basic ' . $NPItoken], true);
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
      "firstName":"' . $userDetails['firstName'] . '",
      "lastName":"' . $userDetails['lastName'] . '",
      "email":"' . $userDetails['email'] . '",
      "transactionType":"SALE",
      "emailSubject": "Invoice from VOLT Services LLC",
      "invoiceExpiryDate": "' . \Carbon\Carbon::now()->addDays(2)->format('Y-m-d') . '",
      "items":[
        {
          "description":"' . $planName . '",
          "totalPrice":{
            "currencyCode":"AED",
            "value":' . (int) $price * 100 . '
          },
          "quantity": 1
        }
      ],
      "total":{
        "currencyCode":"AED",
        "value":' . $price . '
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
//        dd($response, $response->_links->payment->href);
        if (isset($response->_links->payment->href))
            return $response->_links->payment->href;
        else
            return false;
    }

}
