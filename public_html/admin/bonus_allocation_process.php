<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}
$bonus_obj = new Bonus_Operations();
$client_operation = new clientOperation();

$get_params = allowed_get_params(['x', 'app_id']);

$app_id_encrypted = $get_params['app_id'];
$app_id = decrypt(str_replace(" ", "+", $app_id_encrypted));
$app_id = preg_replace("/[^A-Za-z0-9 ]/", '', $app_id);

#Process Transaction Release
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['release_transaction'])){
    release_transaction($trans_id, $_SESSION['admin_unique_code']);
    switch($get_params['x']) {
        case 'pending': $url = 'deposit_pending'; break;
        case 'confirmed': $url = 'deposit_confirmed'; break;
        case 'inspect': $url = 'deposit_confirmed'; break;
        case 'notified': $url = 'deposit_notified'; break;
        case 'view': $url = 'deposit_confirmed'; break;
    }
    header("Location: $url.php");
    exit();
}

#Process Transaction Release
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process'])){
    foreach($_POST as $key => $value){ $_POST[$key] = $db_handle->sanitizePost($value); }
    extract($_POST);
    $result = $bonus_obj->allocate_bonus($app_id, $amount, $comment, $trans_ref, $_SESSION['admin_unique_code']);
    $result ? $message_success = "Bonus Allocated!" : $message_error = "Operation failed.";
}

$app_detail = $bonus_obj->get_app_by_id($app_id);

if(empty($app_detail)) {
    //redirect_to("bonus_allocation.php");
    //exit;
}

$all_comments = $bonus_obj->get_all_comments($app_id);
//var_dump($app_detail);
/*$transaction_access = allow_transaction_review($trans_id, $_SESSION['admin_unique_code']);
if(!empty($transaction_access['holder'])){
    $message_error = "This transaction is currently being reviewed by {$transaction_access['holder']}";
}*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Process Bonus Allocation</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Process Bonus Allocation" />
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
        <script src="operations_comment.js"></script>
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
                            <h4><strong>PROCESS BONUS ALLOCATION</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p><a href="bonus_allocation.php" class="btn btn-sm btn-default" title="Go back to previous page"><i class="fa fa-arrow-circle-left"></i> Manage Bonus Allocation</a></p>
                                <p>Fill the actual amount allocated to this bonus account.</p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-sm-4 trans_item-thumb">
                                                        <p class="text-center"><a target="_blank" title="View Client Profile" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($trans_detail['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a></p>
                                                        <?php
                                                        if(!empty($app_detail['passport'])) { $file_location = "../userfiles/" . $app_detail['passport']; }

                                                        if(file_exists($file_location)) {
                                                            ?>
                                                            <img src="<?php echo $file_location; ?>" alt="" class="img-responsive">
                                                        <?php } else { ?>
                                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive">
                                                        <?php } ?>

                                                        <?php if($client_operation->account_flagged($app_detail['user_code'])) { ?>
                                                            <p><img class="center-block" src="../images/red-flag.png" alt="" title="This client has an account flagged."></p>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-sm-8 ">
                                                        <div class="row">
                                                            <div class="col-xs-12 trans_item_bio">
                                                                <span id="bio_name"><?php echo $app_detail['first_name']; ?> <?php echo $app_detail['last_name']; ?></span>
                                                                <span><?php echo $app_detail['phone']; ?></span>
                                                                <span><?php echo $app_detail['email']; ?></span>
                                                                <hr/>
                                                                <span class="text-danger"><strong>IFX Accounts</strong></span>
                                                                <span style="max-width: 500px; overflow: scroll">
                                    <?php
                                    $client_ifxaccounts = $client_operation->get_client_ifxaccounts($app_detail['user_code']);
                                    $count = 1;
                                    foreach($client_ifxaccounts as $key => $value) {
                                        $ifx_acct_no = $value['ifx_acct_no'];
                                        if($count < count($client_ifxaccounts)) { $ifx_acct_no = $ifx_acct_no . ", "; }
                                        echo $ifx_acct_no;
                                        $count++;
                                    }
                                    ?>
                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="trans_item">
                                            <div class="trans_item_content">
                                                <div class="row">
                                                    <div class="col-sm-12 ">
                                                        <span><strong>Bonus Amount:</strong> &dollar; <?php echo number_format($app_detail['bonus_type_value'], 2); ?> </span>
                                                        <span><strong>Date Of Review: </strong><?php echo datetime_to_text($app_detail['updated']); ?></span>
                                                        <span><strong>Account:</strong> <?php echo $app_detail['ifx_acct_no']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <hr/>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <br/>

                                        <br/>
                                        <form  data-toggle="validator" role="form" method="post" action="">
                                            <input name="amount" value="<?php echo number_format($app_detail['bonus_type_value'], 2); ?>" type="hidden">
                                            <div class="form-group">
                                                <label class="control-label" for="remarks">Transfer Reference:</label>
                                                <div><textarea name="trans_ref" id="message" rows="1" class="form-control" placeholder="Enter the transfer reference" required></textarea></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label" for="remarks">Your Remark:</label>
                                                <div><textarea name="comment" id="message" rows="3" class="form-control" placeholder="Enter your remark" required></textarea></div>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" data-target="#confirm-deposit-approve" data-toggle="modal" class="btn btn-sm btn-success">Confirm Allocation</button>
                                            </div>

                                            <!--Modal - confirmation boxes-->
                                            <div id="confirm-deposit-approve" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                    class="close">&times;</button>
                                                            <h4 class="modal-title">Approve Bonus Allocation</h4></div>
                                                        <div class="modal-body">Are you sure you want to Allocate this Bonus? This action cannot be reversed.</div>
                                                        <div class="modal-footer">
                                                            <input name="process" type="submit" class="btn btn-success" value="Confirm Deposit">
                                                            <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-sm-6">
                                        <h5>Admin Remarks</h5>
                                        <div style="word-break:break-all; max-height: 550px; overflow-y: scroll; overflow-x: hidden">
                                            <?php if(isset($all_comments) && !empty($all_comments)) { foreach ($all_comments as $row) {?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="transaction-remarks">
                                                            <span id="trans_remark_author"><?php echo $row['admin_name']; ?></span>
                                                            <span id="trans_remark"><?php echo $row['comment'];?></span>
                                                            <span id="trans_remark_date"><?php echo $row['created']; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } } else { ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="transaction-remarks">
                                                            <span class="text-danger"><em>There is no remark to display.</em></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
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