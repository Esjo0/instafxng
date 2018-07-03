<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$response_msg = array();
if(isset($_POST['resend_sms'])){
    $message = $db_handle->sanitizePost(trim($_POST['message']));
    $phone = $db_handle->sanitizePost(trim($_POST['phone']));
    $x = $system_object->send_sms($phone, $message);
    if($x){
        $node = count($response_msg);
        $response_msg[$node]['type'] = '1';
        $response_msg[$node]['msg'] = "Message Sent Successfully.<br/>
                <b>Message: </b> $message<br/>
                <b>Phone: </b> $phone<br/>
                <b>Sent: </b> ".datetime_to_text(date('y-m-d h:m:s'))."<br/>
                <b>Status: </b> Delivery In Progress<br/>";
    }else{
        $node = count($response_msg);
        $response_msg[$node]['type'] = '0';
        $response_msg[$node]['msg'] = "Message Failed.<br/>
                <b>Message: </b> $message<br/>
                <b>Phone: </b> $phone<br/>
                <b>Status: </b> Delivery Failed<br/>";
    }
}
if(isset($_POST['forward_sms'])){
    $message = $db_handle->sanitizePost(trim($_POST['message']));
    $phone = explode(',', $db_handle->sanitizePost(trim($_POST['phone'])));
    foreach ($phone as $key){
        $x = $system_object->send_sms($key, $message);
        if($x){
            $node = count($response_msg);
            $response_msg[$node]['type'] = '1';
            $response_msg[$node]['msg'] = "Message Sent Successfully.<br/>
                <b>Message: </b> $message<br/>
                <b>Phone: </b> $key<br/>
                <b>Sent: </b> ".datetime_to_text(date('y-m-d h:m:s'))."<br/>
                <b>Status: </b> Delivery In Progress<br/>";
        }else{
            $node = count($response_msg);
            $response_msg[$node]['type'] = '0';
            $response_msg[$node]['msg'] = "Message Failed.<br/>
                <b>Message: </b> $message<br/>
                <b>Phone: </b> $key<br/>
                <b>Status: </b> Delivery Failed<br/>";
        }
    }
}

$query = "SELECT * FROM sms_records ORDER BY created DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int)$_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) {    $currentpage = $totalpages;}
if ($currentpage < 1) {    $currentpage = 1;}
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {    $prespagehigh = $numrows;}
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$records = $db_handle->fetchAssoc($result);

function response_message($msg_array){
    if(!empty($msg_array)){
        foreach ($msg_array as $row){
            if(!empty($row['type']) && !empty($row['msg'])){
                if($row['type'] == '1'){
                    echo "<div class='alert alert-success'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Success!</strong>".$row['msg'].
                        "</div>";}
                if($row['type'] == '0'){
                    echo "<div class='alert alert-danger'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Oops!</strong>".$row['msg'].
                        "</div>";}
            }
        }
    }
}
function delivery_status($status){
    switch ($status){
        case '1701':
            $msg = 'SUCCESS';
            break;
        case '1702':
            $msg = 'INVALID URL/PARAMETERS';
            break;
        case '1703':
            $msg = 'INVALID USERNAME/PASSWORD';
            break;
        case '1704':
            $msg = 'INSUFFICIENT CREDIT';
            break;
        case '1705':
            $msg = 'MOBILES TO LONG (MAX.500)';
            break;
        case '1706':
            $msg = 'INTERNAL ERROR';
            break;
        default:
            $msg = 'UNKNOWN';
            break;
    }
    return $msg;
}
function get_sender($sender){
    global $admin_object;
    if($sender != 'INSTAFXNG'){
        return $admin_object->get_admin_name_by_code($sender);
    }else{
        return $sender;
    }
}
$account_balance = "&#8358; ".number_format(file_get_contents("http://sms.smsworks360.com/api/?username=support@instafxng.com&password=fisayo75&request=balance"), 2, '.', ',');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - SMS Records</title>
        <meta name="title" content="Instaforex Nigeria | Admin - SMS Records" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
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
                            <h4><strong>SMS RECORDS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-3 pull-right">
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead><tr><th><b class="text-success">Balance:  <?php echo $account_balance;?></b></th></tr></thead>
                                    </table>
                                </div>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Phone</th>
                                            <th>Message</th>
                                            <th>Sender</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($records) && !empty($records)) {foreach ($records as $row) {?>
                                        <tr>
                                            <td><?php echo $row['phone_no']; ?></td>
                                            <td><?php echo $row['message']; ?></td>
                                            <td><?php echo get_sender($row['sender']); ?></td>
                                            <td><?php echo delivery_status($row['status']); ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td class="nowrap">
                                                <button title="Resend Message" type="button" data-target="#resend_<?php echo $row['record_id'];?>" data-toggle="modal" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-repeat"></i></button>

                                                <button title="Forward Message" type="button" data-target="#forward_<?php echo $row['record_id'];?>" data-toggle="modal" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-forward"></i></button>
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                    <!--Modal - confirmation boxes-->
                                                    <div id="resend_<?php echo $row['record_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                    <h4 class="modal-title">Resend SMS Message</h4></div>
                                                                <div class="modal-body">
                                                                    <p class="text-justify">Do you want to resend this message to <?php echo $row['phone_no'];?></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="msg" value="<?php echo $row['message']; ?>">
                                                                    <input type="hidden" name="phone" value="<?php echo $row['phone_no'];?>">
                                                                    <input name="resend_sms" value="Proceed" class="btn btn-xs btn-success" type="submit">
                                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-xs btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                    <!--Modal - confirmation boxes-->
                                                    <div id="forward_<?php echo $row['record_id'];?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                                    <h4 class="modal-title">Forward SMS Message</div>
                                                                <div class="modal-body">
                                                                    <p class="text-justify">Please fill the form below to forward this message.</p>
                                                                    <textarea class="form-control" rows="2" disabled><?php echo $row['message']; ?></textarea><br/>
                                                                    <textarea placeholder="Phone Number" rows="2" name="phone" class="form-control" required></textarea>
                                                                    <span style="font-size: x-small" class="text-muted">* Multiple phone numbers should be comma separated.</span>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="msg" value="<?php echo $row['message']; ?>">
                                                                    <input name="forward_sms" value="Proceed" class="btn btn-xs btn-success" type="submit">
                                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-xs btn-danger">Close!</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
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