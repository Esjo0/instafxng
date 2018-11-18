<?php require_once("../init/initialize_admin.php"); ?>
<?php
if($_POST['type'] == '1')
{
    $query_count = 0;
    $success_count = 0;
    $query = explode("*", $_POST['query']);
    foreach ($query as $row)
    {
        if(!empty($row))
        {
            $query_count++;
            $result = $db_handle->runQuery($row);
            $result ? $success_count++ : $success_count = $success_count;
        }
    }
    if($query_count == $success_count){?>
        <div id="success_alert" class="alert alert-success">
            <a id="alert_dismiss" style="display: none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> Your order was successfully submitted.
            <br/>
            Click <a href="accounting_system_requisition_orders.php">HERE</a> to view the status of this order.
            <br/>
            Review your last order below or click <a href="javascript:void(0);" onclick="acc_system.new_order_list();">HERE</a> to create a new order.
        </div>
        <?php
        $title = "New Requisition Notification";
        $message = " A new requisition order was added <br/><a href='https://instafxng.com/admin/accounting_system_confirmation_requests.php' target='_blank'>Click here to review this order.</a>";
        $recipients = $obj_push_notification->get_recipients_by_access(41);
        $author = $admin_object->get_admin_name_by_code($_SESSION['admin_unique_code']);
        $source_url = "https://instafxng.com/admin/accounting_system_confirmation_requests.php";
        $notify_support = $obj_push_notification->add_new_notification($title, $message, $recipients, $author, $source_url);

        //$admin_details = $admin_object->get_admin_detail_by_code("narCT");
        $admin_details = $admin_object->get_admin_detail_by_code("Vi1DW");
        $mail_subject = "New Requisition Notification";
        $mail_message = <<<MAIL
            <div style="background-color: #F3F1F2">
            <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
            <img src="https://instafxng.com/images/ifxlogo.png" />
            <hr/>
            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear Joshua,</p>
            <p>A new requisition order was added</p>
            <p><a href='https://instafxng.com/admin/accounting_system_confirmation_requests.php' target='_blank'>Click here to review this order.</a></p>
            <br /><br />
            <p>Best Regards,</p>
            <p>Instaforex Nigeria,<br />www.instafxng.com</p>
            <br /><br />
            </div>
            <hr />
            <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
            <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
            <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
            <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
            <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
            <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
            <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
            </p>
            <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
            <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
            <p><strong>Office Number:</strong> 08028281192</p><br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
            <p>This email was sent to you by Instant Web-Net Technologies Limited, the official Nigerian Representative of Instaforex, operator and administrator of the website www.instafxng.com</p>
            <p>To ensure you continue to receive special offers and updates from us, please add support@instafxng.com to your address book.</p>
            </div>
            </div>
            </div>
            </div>
MAIL;
        $system_object->send_email($mail_subject, $mail_message, $admin_details['email'], $admin_details['first_name']);
        ?>
<?php }else{ ?>
        <div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Oops!</strong> Your order failed, please try again.
        </div>
<?php }} ?>

























