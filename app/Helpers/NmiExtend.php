<?php

namespace App\Helpers;

class NmiExtend {

    public static function identify($apikey) {

        $idUrl = "https://identity-uat.ngenius-payments.com/auth/realms/ni/protocol/openid-connect/token";
        $idHead = array("Authorization: Basic " . $apikey, "Content-Type: application/x-www-form-urlencoded");
        $idPost = http_build_query(array('grant_type' => 'client_credentials'));
        $idOutput = self::invokeCurlRequest("POST", $idUrl, $idHead, $idPost);
        return $idOutput;
    }

    function pay($session, $token, $outlet) {

        // construct order object JSON
        $ord = new stdClass;
        $ord->action = "SALE";
        $ord->amount = new stdClass;
        $ord->amount->currencyCode = "AED";
        $ord->amount->value = "100";

        $payUrl = "https://api-gateway-uat.ngenius-payments.com/transactions/outlets/" . $outlet . "/payment/hosted-session/" . $session;
        $payHead = array("Authorization: Bearer " . $token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
        $payPost = json_encode($ord);

        $payOutput = invokeCurlRequest("POST", $payUrl, $payHead, $payPost, true);

        return $payOutput;
    }

    public static function invokeCurlRequest($type, $url, $headers, $post, $json = null) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($type == "POST" || $type == "PUT") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            if ($type == "PUT") {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            }
        }

        $server_output = curl_exec($ch);
        return json_decode($server_output);
    }

}
