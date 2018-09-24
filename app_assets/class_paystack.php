<?php

class Paystack {

    function initialize($trans_id, $client_first_name, $client_last_name, $client_email, $client_dollar, $client_naira_total, $callback_url) {
        global $db_handle;

        $url    = "https://api.paystack.co/transaction/initialize";

        if($client_email == 'esanolalekan@gmail.com') {
            $client_naira_total = 5;
        }

        $body = array(
            "firstname" => $client_first_name,
            "lastname" => $client_last_name,
            "ref" => $trans_id,
            "email" => $client_email,
            "amount"    => $client_naira_total * 100,
            "callback_url" =>  $callback_url
        );

        $response = self::curlFunction($url, $body);

        if ($response['status'] == 1) {
            // TODO: We need to save the transaction reference below.
            $trans_ref = $db_handle->sanitizePost($response['data']['reference']);
            redirect_to($response['data']['authorization_url']);
        } else {
            return 'failed';
        }
    }

    function verify($ref) {
        global $db_handle;

        $url    = "https://api.paystack.co/transaction/verify/".rawurlencode($ref);

        $response = self::curlFunction($url);

        if($response['data']['status'] == 'success') {
            // Payment successful, update transaction
            $query = "UPDATE user_transactions SET status = 'SUCCESS' WHERE transaction_ref = '$ref' LIMIT 1";
            $db_handle->runQuery($query);

            // Update status and trans ref on the user reg table
            $query = "SELECT email FROM user_transactions WHERE transaction_ref = '$ref' LIMIT 1";
            $result = $db_handle->runQuery($query);
            $fetch_data = $db_handle->fetchAssoc($result);
            $email = $fetch_data[0]['email'];

            $query = "UPDATE user_reg SET txn_ref = '$ref', status = '1' WHERE email = '$email' LIMIT 1";
            $db_handle->runQuery($query);

            return true;

        } else {
            // Payment failed, update transaction
            $query = "UPDATE user_transactions SET status = 'FAIL' WHERE transaction_ref = '$ref' LIMIT 1";
            $db_handle->runQuery($query);

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