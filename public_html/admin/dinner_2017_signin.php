<?php
    require_once("../init/initialize_admin.php");
    if (!$session_admin->is_logged_in())
    {
        redirect_to("login.php");
    }

if(isset($_POST['search']))
{
    $reservation_code = $db_handle->sanitizePost(trim($_POST['reservation_code']));

    function get_dinner_reg_remark($reservation_code)
    {
        global $db_handle;

        $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, dc.comment, dc.created
                FROM dinner2017_comment AS dc
                INNER JOIN admin AS a ON dc.admin_code = a.admin_code
                WHERE dc.reservation_code = '$reservation_code' ORDER BY dc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data ? $fetched_data : false;
    }

    $attendee_detail = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM dinner_2017 WHERE reservation_code = '$reservation_code' "));
    $attendee_detail = $attendee_detail[0];

    if(empty($attendee_detail))
    {
        //redirect_to("./");
        //exit;
    }
    else
    {
        $db_handle->runQuery("UPDATE dinner_2017 SET attended = '1' WHERE reservation_code = '$reservation_code'");
        $admin_remark = get_dinner_reg_remark($reg_code);
    }

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | 2017 Dinner Sign In</title>
        <meta name="title" content="Instaforex Nigeria | 2017 Dinner Sign In" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script type="text/javascript" src="../js/instascan.min.js"></script>
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
                            <h4><strong>SIGN IN FORM</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <p>Enter a reservation code below.</p>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input name="reservation_code" type="text" id="reservation_code" placeholder="Reservation Code" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button id="search" name="search" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <video width="400" height="400" class="img-responsive" id="preview"></video>
                                                        <script type="text/javascript">
                                                            var scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
                                                            scanner.addListener('scan', function (content) {
                                                                console.log(content);
                                                                document.getElementById('reservation_code').value = content;
                                                                document.getElementById('search').click();
                                                            });
                                                            Instascan.Camera.getCameras().then(function (cameras) {
                                                                if (cameras.length > 0) {
                                                                    scanner.start(cameras[0]);
                                                                } else {
                                                                    console.error('No cameras found.');
                                                                }
                                                            }).catch(function (e) {
                                                                console.error(e);
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  class="col-sm-12">
                                    <?php
                                    if(isset($attendee_detail) && !empty($attendee_detail)): ?>
                                            <p><b>Full Name: </b><?php echo $attendee_detail['full_name']; ?></p>
                                            <p><b>Ticket Type: </b><?php echo dinner_ticket_type($attendee_detail['ticket_type']); ?></p>
                                        <p><b>Reservation Code: </b><?php echo $attendee_detail['reservation_code']; ?></p>
                                        <?php
                                        if($attendee_detail['attended'] > 0):?>
                                            <p><b>Guest Signed In Already</b></p>
                                            <?php endif; ?>
                                        <?php
                                            if(isset($admin_remark) && !empty($admin_remark))
                                            {
                                                foreach ($admin_remark as $row2) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="transaction-remarks">
                                                                <span id="trans_remark_author"><?php echo $row2['admin_full_name']; ?></span>
                                                                <span id="trans_remark"><?php echo $row2['comment']; ?></span>
                                                                <span id="trans_remark_date"><?php echo datetime_to_text($row2['created']); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } }  ?>
                                        <?php endif; ?>
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