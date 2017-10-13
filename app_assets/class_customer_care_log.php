<?php

class Customer_Care
{

    public function add_new_customer_log($admin_code, $first_name, $other_name, $last_name, $email, $phone, $con_desc, $prospect_source)
    {
        global $db_handle;
        $query = "INSERT IGNORE INTO prospect_biodata (first_name, last_name, other_names , email_address, phone_number, prospect_source) VALUES ('$first_name', '$last_name', '$other_name','$email','$phone', '$prospect_source')";
        $db_handle->runQuery($query);
        $query = "SELECT @prospect_id:= prospect_biodata_id FROM prospect_biodata WHERE email_address = '$email' OR phone_number = '$phone' ";
        $db_handle->runQuery($query);
        $db_handle->runQuery("INSERT INTO customer_care_log (con_desc, admin_code, tag, log_type) VALUES('$con_desc', '$admin_code', @prospect_id, '2') ");

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function add_new_client_log($admin_code, $acc_no, $con_desc)
    {
        global $db_handle;
        $db_handle->runQuery("SELECT @u_code:= user_code FROM user_ifxaccount WHERE user_ifxaccount.ifx_acct_no = '$acc_no'");
        $db_handle->runQuery("INSERT INTO customer_care_log (con_desc, admin_code, tag, log_type) VALUES('$con_desc', '$admin_code', @u_code, '1')");
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_logs_by_admin($admin_code)
    {
        global $db_handle;
        $query = "SELECT * FROM customer_care_log WHERE customer_care_log.admin_code = '$admin_code' ORDER BY log_id DESC ";
        $result = $db_handle->runQuery($query);
        $all_logs_by_admin = $db_handle->fetchAssoc($result);
        return $all_logs_by_admin;
    }

    public function customer_details($customer_id)
    {
        global $db_handle;
        $query = "SELECT first_name, other_names, last_name,  email_address, phone_number FROM prospect_biodata WHERE prospect_biodata.prospect_biodata_id = '$customer_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $customer_details = $db_handle->fetchAssoc($result);
        return $customer_details;
    }

    public function client_details($user_code)
    {
        global $db_handle;
        $query = "SELECT first_name, middle_name, last_name, phone, email, user_ifxaccount.ifx_acct_no  FROM user, user_ifxaccount WHERE user.user_code = user_ifxaccount.user_code AND user.user_code = '$user_code' AND user_ifxaccount.user_code = '$user_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $client_details = $db_handle->fetchAssoc($result);
        return $client_details;
    }

    public function get_all_logs() {
        global $db_handle;
        $query = "SELECT * FROM customer_care_log";
        $result = $db_handle->runQuery($query);
        $all_logs = $db_handle->fetchAssoc($result);
        return $all_logs;
    }

    public function get_all_conversations($tag)
    {
        global $db_handle;
        $query = "SELECT log_id, con_desc, status, created FROM customer_care_log WHERE tag = '$tag' ";
        $result = $db_handle->runQuery($query);
        $customer_details = $db_handle->fetchAssoc($result);
        return $customer_details;
    }

    public function log_delete($log_id)
    {
        global $db_handle;
        $query = "DELETE FROM customer_care_log WHERE customer_care_log.log_id = '$log_id'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }
    public function log_treated($log_id)
    {
        global $db_handle;
        $query = "UPDATE customer_care_log SET status = '2' WHERE log_id = '$log_id'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }
}
$obj_customer_care_log = new Customer_Care();