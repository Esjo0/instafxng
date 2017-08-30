<?php

class Customer_Care
{

    public function add_new_customer_log($admin_code, $full_name, $email, $phone, $con_desc)
    {
        global $db_handle;
        $db_handle->runQuery("INSERT IGNORE INTO customers (full_name, email, phone) VALUES ('$full_name','$email','$phone');");
        $db_handle->runQuery("SELECT @cust_id:= customer_id FROM customers WHERE email = '$email';");
        $db_handle->runQuery("INSERT INTO customer_care_log (con_desc, admin_code, tag, type) VALUES('$con_desc', '$admin_code', @cust_id, 'CUSTOMER');");

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function add_new_client_log($admin_code, $acc_no, $con_desc)
    {
        global $db_handle;
        $db_handle->runQuery("SELECT @u_code:= user_code FROM user_ifxaccount WHERE user_ifxaccount.ifx_acct_no = '$acc_no'");
        $db_handle->runQuery("INSERT INTO customer_care_log (con_desc, admin_code, tag, type) VALUES('$con_desc', '$admin_code', @u_code, 'CLIENT')");
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
        $query = "SELECT full_name, email, phone FROM customers WHERE customers.customer_id = '$customer_id' LIMIT 1";
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
        $query = "UPDATE customer_care_log SET status = 'TREATED' WHERE log_id = '$log_id'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }
}
$obj_log = new Customer_Care();