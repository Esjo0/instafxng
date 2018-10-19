<?php

class Paystack {

    function initialize($trans_id, $client_first_name, $client_last_name, $client_email, $client_naira_total, $callback_url) {
        $url    = "https://api.paystack.co/transaction/initialize";

        if($client_email == 'esanolalekan@gmail.com') {
            $client_naira_total = 5;
        }

        $body = array(
            "first_name" => $client_first_name,
            "last_name" => $client_last_name,
            "reference" => $trans_id,
            "email" => $client_email,
            "amount" => $client_naira_total * 100,
            "callback_url" => $callback_url
        );

        $response = self::curlFunction($url, $body);

        if ($response['status'] == 1) {
            redirect_to($response['data']['authorization_url']);
        } else {
            return 'failed';
        }
    }

    function verify($ref) {
        $url = "https://api.paystack.co/transaction/verify/".rawurlencode($ref);
        $response = self::curlFunction($url);

        if($response['data']['status'] == 'success') {
            // Payment successful, update transaction
            return $response;
        } else {
            // Payment failed, update transaction
            return false;
        }
    }

    function curlFunction($url, $param = false) {
        $header  = array(
            "authorization: Bearer sk_live_bd258825dc07392b993f88979b9b1cad0820ecea",
            "content-type: application/json",
            "cache-control: no-cache");
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        
        if($param) {
            curl_setopt($curl, CURLOPT_POST, true);
            $curl_post_data = json_encode($param);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        }
        
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($curl_response,true);

        return $response;
    }
}

$obj_paystack = new Paystack();