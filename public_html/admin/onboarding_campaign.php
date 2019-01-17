<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$admin_code = $_SESSION['admin_unique_code'];

if (isset($_POST['create'])) {
    $title = $db_handle->sanitizePost($_POST['title']);
    $details = $db_handle->sanitizePost($_POST['details']);
//generate  id
    insert_campaign_id:
    $campaign_id = rand_string(6);
    if ($db_handle->numRows("SELECT campaign_id FROM onboarding_campaign WHERE campaign_id = '$campaign_id'") > 0) {
        goto insert_campaign_id;
    };

    $link = "https://instafxng.com/forex_training/?b=" . $campaign_id;

    $query = "INSERT INTO onboarding_campaign (title, details, campaign_id, link, admin) VALUE('$title', '$details', '$campaign_id', '$link', '$admin_code')";
    $result = $db_handle->runQuery($query);
    if ($result == true) {
        $message_success = "Successfully submitted";
    } else {
        $message_error = "Not successful. Kindly Try again.";
    }
}

$query = "SELECT * FROM onboarding_campaign ";
$_SESSION['query_onboarding_campaign'] = $query;

$query = $_SESSION['query_onboarding_campaign'] ;

$numrows = $db_handle->numRows($query);

$rowsperpage = 20;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int) $_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$campaigns = $db_handle->fetchAssoc($result);

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
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">Name:</label>
                                        <div class="col-sm-5 col-lg-5">
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
                                    <th>Link</th>
                                    <th>Created By</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($campaigns) && !empty($campaigns)) {
                                    foreach ($campaigns as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo $row['details']; ?></td>
                                            <td><?php echo $row['link']; ?></td>
                                            <td><?php echo $admin_object->get_admin_name_by_code($row['admin']);?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                            <td>
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