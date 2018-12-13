<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT u.user_code, a.audit_location, a.audit_date, a.created, u.email AS email, u.phone AS phone, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
    FROM account_audit AS a
INNER JOIN user AS u ON a.user_code = u.user_code
    ORDER BY a.audit_date DESC ";
$_SESSION['query'] = $query;

$query = $_SESSION['query'];

$numrows = $db_handle->numRows($query);

$rowsperpage = 40;

$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {
    $currentpage = (int)$_GET['pg'];
} else {
    $currentpage = 1;
}
if ($currentpage > $totalpages) {
    $currentpage = $totalpages;
}
if ($currentpage < 1) {
    $currentpage = 1;
}

$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if ($prespagehigh > $numrows) {
    $prespagehigh = $numrows;
}

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$participants = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Admin - Account Audit</title>
    <meta name="title" content="Instaforex Nigeria | Admin - Dinner 2018"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <?php require_once 'layouts/head_meta.php'; ?>
    <script>
        function proceed() {
            document.getElementById("proceed").style.display = "block";
            document.getElementById("pro").style.display = "none";
        }
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
                    <h4><strong>List of Clients who have registered for account audit</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include 'layouts/feedback_message.php'; ?>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Date</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Audit Venue</th>
                                <th>Audit Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($participants) && !empty($participants)) {
                                foreach ($participants as $row) {
                                    extract($row) ?>
                                    <tr>
                                        <td><?php echo $full_name; ?></td>
                                        <td><?php echo datetime_to_text($created); ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $phone; ?></td>
                                        <td><?php if ($audit_location == 1) {
                                                echo "Diamond Estate";
                                            } elseif ($audit_location == 2) {
                                                echo "HFP Office";
                                            } elseif ($audit_location == 3) {
                                                echo "Online";
                                            } ?></td>
                                        <td><?php echo datetime_to_text($audit_date); ?></td>
                                        <td nowrap="nowrap">
                                            <a target="_blank" title="View" class="btn btn-sm btn-info"
                                               href="client_detail.php?id=<?php echo dec_enc('encrypt', $user_code); ?>"><i
                                                    class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            <a class="btn btn-sm btn-primary" target="_blank" title="Send Email"
                                               href="campaign_email_single.php?name=<?php $name;
                                               echo dec_enc('encrypt', $name) . '&email=' . dec_enc('encrypt', $email); ?>"><i
                                                    class="glyphicon glyphicon-envelope"></i></a>
                                            <a class="btn btn-sm btn-success" target="_blank" title="Send SMS"
                                               href="campaign_sms_single.php?lead_phone=<?php echo dec_enc('encrypt', $phone) ?>"><i
                                                    class="glyphicon glyphicon-phone-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                        <?php if (isset($participants) && !empty($participants)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">
                                    Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?>
                                    entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($participants) && !empty($participants)) {
                    require_once 'layouts/pagination_links.php';
                } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>