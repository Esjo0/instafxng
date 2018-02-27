<?php

class Accounting_System
{
public function get_cash_out_details($cash_out_code)
    {
        global $db_handle;
        $query = "SELECT 
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
          accounting_system_req_order.created AS created, 
           accounting_system_req_order.status AS status,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order 
          WHERE accounting_system_req_order.req_order_code = '$cash_out_code'
           AND admin.admin_code = accounting_system_req_order.author_code
          AND accounting_system_req_order.status = '2'
           AND accounting_system_req_order.payment_status = '1' ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result)[0];
    }

    public function paid_req_order($req_order_code)
    {
        global $db_handle;
        $query = "UPDATE accounting_system_req_order SET payment_status = '2' WHERE req_order_code = '$req_order_code' LIMIT 1 ";
        $result = $db_handle->runQuery($query);
        return $result ? true : false;
    }

    public function notify_of_new_order($author_code)
    {
        global $admin_object;
        global $obj_push_notification;
        global $db_handle;

        /*$message_main = '<p style="font-size: small">Author: '.$admin_object->get_admin_name_by_code($author_code)."<br/>";
        $message_main .= 'Order Total: N'.number_format($req_order_total, 2)."<br/>";
        $query ="SELECT location FROM accounting_system_office_locations WHERE location_id = '$location' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $result = $result[0]['location'];
        $message_main .= 'Order Location: '.$result."</p>";
        $recipients = "Vi1DW";
        $type = '4';
        $obj_push_notification->add_new_notification($message_main, $recipients, $type);*/
    }

    public function get_order_list($order_code)
    {
        global $db_handle;
        $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$order_code' ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_order_refund_list($order_code)
    {
        global $db_handle;
        $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$order_code' AND item_app = '2'";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function item_app_status($status)
    {
        switch ($status)
        {
            case '2': $message = '<i title="Approved" class="fa fa-check text-success"></i>'; break;
            case '1': $message = '<i title="Pending" class="fa fa-warning text-warning"></i>'; break;
            case '0': $message = '<i title="Declined" class="fa fa-asterisk text-danger"></i>'; break;
            default: $message = "Unknown"; break;
        }
        return $message;
    }
}
$obj_acc_system = new Accounting_System();