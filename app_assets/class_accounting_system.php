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

    public function paid_req_order($req_order_code, $admin_email){
        global $db_handle;
        global $admin_object;
        global $system_object;

        $query = "UPDATE accounting_system_req_order SET payment_status = '2' WHERE req_order_code = '$req_order_code' LIMIT 1 ";
        $result = $db_handle->runQuery($query);
        $order_details = $db_handle->fetchAssoc($db_handle->runQuery("SELECT req_order_total, author_code FROM accounting_system_req_order WHERE req_order_code = '$req_order_code' LIMIT 1 "))[0];
        $order_total = number_format($order_details['req_order_total'], 2, ".", ",");
        $order_owner = $admin_object->get_admin_name_by_code($order_details['author_code']);

        $subject = "Cash Dispense";
        $mail_sender = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hi,</p>
            <p>Please disburse &#8358; $order_total to $order_owner.</p>
            <p>Thank you for your prompt response.</p>
   
            <br/><br/>
            <p>Best Regards,</p>
            <p>$mail_sender,</p>
            <p>InstaFxNg</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        $system_object->send_email($subject, $message, $admin_email, '', $mail_sender);
        return $result ? true : false;
    }

    public function get_order_list($order_code){
        global $db_handle;
        $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$order_code' ";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_order_refund_list($order_code){
        global $db_handle;
        $query = "SELECT * FROM accounting_system_req_item WHERE order_code = '$order_code' AND item_app = '2'";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function item_app_status($status){
        switch ($status) {
            case '2': $message = '<i title="Approved" class="fa fa-check text-success"></i>'; break;
            case '1': $message = '<i title="Pending" class="fa fa-warning text-warning"></i>'; break;
            case '0': $message = '<i title="Declined" class="fa fa-asterisk text-danger"></i>'; break;
            default: $message = "Unknown"; break;
        }
        return $message;
    }
}
$obj_acc_system = new Accounting_System();