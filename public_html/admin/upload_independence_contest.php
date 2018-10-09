<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

function independence_date_duplicate($point_date) {
    global $db_handle;

    $query = "SELECT date_earned FROM independence_promo_date WHERE date_earned = '$point_date'";
    $result = $db_handle->numRows($query);

    return $result ? true : false;
}

$client_operation = new clientOperation();

if (isset($_POST["upload_points"])) {
    $point_date = $_POST["point_date"];

    if(independence_date_duplicate($point_date)) {
        $message_error = "Looks like you have uploaded points for the selected date";
    } else {

        // SELECTING PARTICIPANTS
        $query = "SELECT user_code FROM independence_promo";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        foreach ($fetched_data AS $value) {
            $my_user_code = $value['user_code'];
            $query = "SELECT SUM(point_earned) AS my_points FROM point_based_reward WHERE user_code = '$my_user_code' AND type = '2' AND date_earned = '$point_date' GROUP BY user_code";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);

            $my_points = $fetched_data[0]['my_points'];

            // UPDATE THE POINTS
            $query = "UPDATE independence_promo SET total_points = total_points + $my_points WHERE user_code = '$my_user_code' LIMIT 1";
            $result = $db_handle->runQuery($query);
        }

        // INSERT INTO DATE LOG
        $query = "INSERT INTO independence_promo_date (date_earned) VALUE ('$point_date')";
        $db_handle->runQuery($query);

        $message_success = "Commission details has been uploaded into the system";
    }
}

$query = "SELECT date_earned FROM independence_promo_date ORDER BY date_earned DESC LIMIT 1";
$result = $db_handle->runQuery($query);
$last_updated = $db_handle->fetchAssoc($result);
$last_updated = date_to_text($last_updated[0]['date_earned']);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
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
                            <h4><strong>GENERATE INDEPENDENCE CONTEST POINTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                
                                <p>Choose a date below to generate points for the Independence Contest Table.</p>

                                <p class="text-danger"><small>Points Last Updated: <?php echo $last_updated; ?></small></p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="point_date">Point Date:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date" id="datetimepicker">
                                                <input name="point_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(function () {
                                                $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                    format: 'YYYY-MM-DD'
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="button" data-target="#upload-point-confirm" data-toggle="modal" class="btn btn-success"><i class="fa fa-upload fa-fw"></i> Generate Points</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal - confirmation boxes -->
                                    <div id="upload-point-confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                    <h4 class="modal-title">Generate Independence Point Confirmation</h4>
                                                </div>
                                                <div class="modal-body">Are you sure you want to generate points?</div>
                                                <div class="modal-footer">
                                                    <input name="upload_points" type="submit" class="btn btn-success" value="Generate" />
                                                    <button type="submit" name="decline" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>