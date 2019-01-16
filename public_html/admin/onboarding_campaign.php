<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

//generate inventory id
insert_campaign_id:
$campaign_id = rand_string(6);
if ($db_handle->numRows("SELECT campaign_id FROM onboarding_campaign WHERE campaign_id = '$campaign_id'") > 0) {
    goto insert_invent_id;
};

$link = "https://instafxng.com/forex_training/?b=" . $link;


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
        <?php  require_once 'layouts/head_meta.php'; ?>
        <?php require_once 'hr_attendance_system.php'; ?>
        <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>


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
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Create New Campaign</h3>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">Title:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group">
                                                <input name="title" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">Description:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group">
                                                <textarea name="details" class="form-control" aria-label="With textarea" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="create" type="submit" class="btn btn-success" value="Create New Campaign" /></div>
                                    </div>

                                </form>
                            </div>
                    <div class="col-md-12">
                        <h3>List of all Campaigns</h3>
                        <div>
                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Campaign Name</th>
                                    <th>Campaign Description</th>
                                    <th>Date Created</th>
                                    <th>Link</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($searched_withdrawal) && !empty($searched_withdrawal)) {
                                    foreach ($searched_withdrawal as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['trans_id']; ?></td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td>&dollar; <?php echo number_format($row['dollar_withdraw'], 2, ".", ","); ?></td>
                                            <td><?php echo status_user_withdrawal($row['status']); ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
                                                <a target="_blank" title="View" class="btn btn-info" href="withdrawal_search_view.php?id=<?php echo dec_enc('encrypt', $row['trans_id']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if(isset($searched_withdrawal) && !empty($searched_withdrawal)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>


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