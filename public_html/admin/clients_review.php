<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$admin_code = $_SESSION['admin_unique_code'];

if(isset($_POST['reviewed'])){
    $user_code = $db_handle->sanitizePost($_POST['user_code']);
    $query = "INSERT INTO client_review (user_code, status, admin) VALUE('$user_code', '1', '$admin_code')";
    $result = $db_handle->runQuery($query);
    if($result){
        $message_success = "Review Successfully Completed";
    }else{
        $message_error = "Not successfull. Kindly Try again";
    }
}

$query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.phone, u.email, u.created FROM user AS u
WHERE user_code NOT IN( SELECT user_code FROM client_review )";
$numrows = $db_handle->numRows($query);
$rowsperpage = 10;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' '.'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_users = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Trading Signal Users</title>
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
                            <h4><strong>CLIENT REVIEW PAGE</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
<h3 class="text-center">Top 10 un-reviewed clients.</h3>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                        <th>Done</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_users) && !empty($all_users)) {foreach ($all_users as $row) {?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <a target="_blank" title="Add Comment" class="btn btn-xs btn-info" href="client_reach.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'client_review'; ?>&c=<?php echo dec_enc('encrypt', 'CLIENT REVIEW'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i></a>
                                                <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                <a target="_blank" class="btn btn-xs btn-info" title="Send Email" href="campaign_email_single.php?name=<?php $name = $row['f_name']." ".$row['m_name']." ".$row['l_name']; echo  dec_enc('encrypt', $name).'&email='.dec_enc('encrypt', $row['email']);?>" ><i class="glyphicon glyphicon-envelope"></i></a>
                                                <a target="_blank" class="btn btn-xs btn-info" title="Send SMS" href="campaign_sms_single.php?lead_phone=<?php echo dec_enc('encrypt', $row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                            </td>
                                            <td>
                                                <form method="post" action="" role="form">
                                                    <input type="hidden" name="user_code" value="<?php echo $row['user_code'];?>">
                                                    <button class="btn btn-primary btn-xs" name="reviewed" type="submit"><i class="fa fa-check"></i></button>
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


    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>

