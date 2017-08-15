<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$client_operation = new clientOperation();

$get_params = allowed_get_params(['x', 'id']);

$trans_id_encrypted = $get_params['id'];
$trans_id = decrypt(str_replace(" ", "+", $trans_id_encrypted));
$trans_id = preg_replace("/[^A-Za-z0-9 ]/", '', $trans_id);

switch($get_params['x']) {
    case 'pending': $deposit_process_pending = true; $page_title = '- PENDING'; break;
    case 'confirmed': $deposit_process_confirmed = true; $page_title = '- CONFIRMED'; break;
    case 'inspect': $deposit_process_inspect = true; $page_title = '- INSPECT'; break;
    case 'notified': $deposit_process_notified = true; $page_title = '- NOTIFIED'; break;
    case 'view': $deposit_process_view = true; $page_title = '- VIEW'; break;
    default: $no_valid_page = true; break;
}

if($no_valid_page) {
    header("Location: ./");
}

// Process form for replying to client comment on deposit transaction
if (isset($_POST['reply-deposit-comment'])) {
    
    $trans_id = $db_handle->sanitizePost($_POST['trans_id']);
    $client_email = $db_handle->sanitizePost($_POST['client_email']);
    $client_name = $db_handle->sanitizePost($_POST['client_name']);
    $body = stripslashes($_POST['content']);
    $my_message = str_replace('[NAME]', $client_name, $body);
    $subject = "RE: InstaForex Funding Order Invoice - " . $trans_id;

    $update_comment_response = $client_operation->update_deposit_client_comment_response($trans_id);
    
    if($update_comment_response) {
        $system_object->send_email($subject, $my_message, $client_email, $client_name);
        $message_success = "Your message has been sent.";
    } else {
        $message_error = "An error occurred, your message was not sent.";
    }
}

// Process Notified Deposit
if ($deposit_process_notified && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];

    $realamtpaid = $_POST['realamtpaid'];
    $realamtpaid = str_replace(",", "", $realamtpaid);

    $realDolVal = $_POST['realDolVal'];
    $realDolVal = str_replace(",", "", $realDolVal);
    
    $points_claimed_id = $_POST['points_claimed_id'];

    switch ($_POST['process']) {
        case 'Confirm Deposit':
            $status = '5'; // Transaction: CONFIRMED
            break;
        case 'Decline Deposit':
            $status = '4'; // Transaction: CONFIRMATION DECLINED
            break;
        case 'Pend Deposit':
            $status = '3'; // Transaction: CONFIRMATION IN PROGRESS
            break;
    }
    
    $client_operation->deposit_transaction_confirmation($transaction_id, $realamtpaid, $realDolVal, $status, $remarks, $_SESSION['admin_unique_code']);
    
    if(isset($points_claimed_id) && !empty($points_claimed_id)) {
        if($status == '4') {
            $point_status = '3';
            $client_operation->update_loyalty_point($points_claimed_id, $point_status);
        }
    }
    
    header("Location: deposit_notified.php");
}

// Process Confirmed Deposit
if ($deposit_process_confirmed && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];
    $transaction_reference = $_POST['trans_ref'];
    $points_claimed_id = $_POST['points_claimed_id'];

    switch ($_POST['process']) {
        // Transaction: COMPLETED
        case 'Complete Deposit': $status = '8'; break;
        
        // Transaction: FUNDING DECLINED
        case 'Decline Deposit': $status = '7'; break;
        
        // Transaction: FUNDING IN PROGRESS
        case 'Pend Deposit': $status = '6'; break;
    }
    $client_operation->deposit_transaction_completion($transaction_id, $transaction_reference, $status, $remarks, $_SESSION['admin_unique_code']);
    
    if(!empty($points_claimed_id)) {
        switch ($_POST['process']) {
            case 'Complete Deposit': $point_status = '2'; break;
            case 'Decline Deposit': $point_status = '3'; break;
        }

        $client_operation->update_loyalty_point($points_claimed_id, $point_status);
    }

    $trans_id_encrypted = encrypt($transaction_id);
    header("Location: deposit_view_details.php?id=$trans_id_encrypted");
}

// Process Pending Deposit
if ($deposit_process_pending && ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['process'] == true)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    
    $transaction_id = $_POST['transaction_id'];
    $remarks = $_POST['remarks'];

    $client_operation->deposit_comment($transaction_id, $_SESSION['admin_unique_code'], $remarks);
    
    header("Location: deposit_pending.php");
    exit;
}

$trans_detail = $client_operation->get_deposit_by_id_full($trans_id);

if(empty($trans_detail)) {
    redirect_to("./");
    exit;
} else {
    $trans_remark = $client_operation->get_deposit_remark($trans_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Process Deposit</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Process Deposit" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script type="text/javascript">
            function showdolval(str,str1,str2) {
                if (str=="") {
                    document.getElementById("realDol").innerHTML="";
                    return;
                }
                if (str1=="") {
                    document.getElementById("realDol").innerHTML="";
                    return;
                }
                if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                } else {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        document.getElementById("realDol").innerHTML=xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "logic/calculate_trans.php?q="+str+"&r="+str1+"&do="+str2, true);
                xmlhttp.send();
            }
        </script>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
                height: 250,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

            });
        </script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
                
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-lg-9">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>PROCESS DEPOSIT <?php if($page_title) { echo $page_title; } ?></strong></h4>
                        </div>
                    </div>
                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <?php 
                                    if($deposit_process_pending) { include_once 'views/deposit_process/deposit_process_pending.php'; }
                                    if($deposit_process_confirmed) { include_once 'views/deposit_process/deposit_process_confirmed.php'; }
                                    if($deposit_process_inspect) { include_once 'views/deposit_process/deposit_process_inspect.php'; }
                                    if($deposit_process_notified) { include_once 'views/deposit_process/deposit_process_notified.php'; }
                                    if($deposit_process_view) { include_once 'views/deposit_process/deposit_process_view.php'; }
                                ?>
                                
                            </div>
                        </div>
                        
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>