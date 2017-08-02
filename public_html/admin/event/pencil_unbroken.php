<?php
require_once("../../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT pcr.full_name, pcr.phone_number, pcr.email_address, pcr.client_comment, s.state, pcr.created, u.user_code
      FROM pencil_comedy_reg AS pcr
      INNER JOIN state AS s ON pcr.state_of_residence = s.state_id
      LEFT JOIN user AS u ON pcr.email_address = u.email
      ORDER BY pcr.created DESC ";

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
$pencil_unbroken_reg = $db_handle->fetchAssoc($result);


$query = "SELECT u.email FROM pencil_comedy_reg AS pcr INNER JOIN user AS u ON pcr.email_address = u.email";
$result = $db_handle->numRows($query);
$reg_by_clients = $result;
$reg_by_non_clients = $numrows - $reg_by_clients;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Pencil Unbroken</title>
    <meta name="title" content="Instaforex Nigeria | Pencil Unbroken" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php require_once '../layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once '../layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-lg-3 left-nav">
            <?php require_once '../layouts/sidebar.php'; ?>
        </div>

        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-lg-9">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>PENCIL UNBROKEN REGISTRATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once '../layouts/feedback_message.php'; ?>

                        <p>Details of Pencil Unbroken Registration. You will see the Profile View button for registrations
                        made by clients.</p>

                        <p>
                            <strong>Client Reg:</strong> <?php echo $reg_by_clients; ?> <br />
                            <strong>Other Reg:</strong> <?php echo $reg_by_non_clients; ?>
                        </p>

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>State</th>
                                <th>Comment</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($pencil_unbroken_reg) && !empty($pencil_unbroken_reg)) {
                                foreach ($pencil_unbroken_reg as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email_address']; ?></td>
                                        <td><?php echo $row['phone_number']; ?></td>
                                        <td><?php echo $row['state']; ?></td>
                                        <td><?php echo $row['client_comment']; ?></td>
                                        <td><?php echo datetime_to_text($row['created']); ?></td>
                                        <td>
                                            <?php if(!empty($row['user_code'])) { ?>
                                            <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>
                        <br /><br />

                        <?php if(isset($pencil_unbroken_reg) && !empty($pencil_unbroken_reg)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <?php if(isset($pencil_unbroken_reg) && !empty($pencil_unbroken_reg)) { require_once '../layouts/pagination_links.php'; } ?>


                    </div>
                </div>

            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once '../layouts/footer.php'; ?>
</body>
</html>