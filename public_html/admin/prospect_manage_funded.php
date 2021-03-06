<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(empty($_SESSION['prospect_source_filter']) || !isset($_SESSION['prospect_source_filter'])) {
    $_SESSION['prospect_source_filter'] = 'all';
}

if (isset($_POST['apply_filter'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    $_SESSION['prospect_source_filter'] = $prospect_source;
}

if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    $search_text = $_POST['search_text'];

    $query = "SELECT pb.email_address, CONCAT(pb.first_name, SPACE(1), pb.last_name) AS full_name,
        pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id, u.user_code
        FROM user_deposit AS ud
        INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
        INNER JOIN user AS u ON ui.user_code = u.user_code
        INNER JOIN prospect_biodata AS pb ON u.email = pb.email_address
        INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
        WHERE ud.status = '8' AND pb.email_address LIKE '%$search_text%' OR pb.first_name LIKE '%$search_text%' OR pb.last_name LIKE '%$search_text%' OR pb.phone_number LIKE '%$search_text%'
        ORDER BY ud.created DESC ";

    $numrows = $db_handle->numRows($query);
    $rowsperpage = $numrows;

} else {

    if(isset($_SESSION['prospect_source_filter']) && $_SESSION['prospect_source_filter'] <> 'all') {
        $prospect_source_filter = $_SESSION['prospect_source_filter'];

        $query = "SELECT pb.email_address, CONCAT(pb.first_name, SPACE(1), pb.last_name) AS full_name,
            pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id, u.user_code
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN prospect_biodata AS pb ON u.email = pb.email_address
            INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
            WHERE ud.status = '8' AND ps.prospect_source_id = $prospect_source_filter
            GROUP BY u.email ORDER BY ud.created DESC ";

    } else {
        $query = "SELECT pb.email_address, CONCAT(pb.first_name, SPACE(1), pb.last_name) AS full_name,
            pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id, u.user_code
            FROM user_deposit AS ud
            INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN prospect_biodata AS pb ON u.email = pb.email_address
            INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
            WHERE ud.status = '8'
            GROUP BY u.email ORDER BY ud.created DESC ";
    }

    $numrows = $db_handle->numRows($query);
    $rowsperpage = 20;
}

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
$all_prospect = $db_handle->fetchAssoc($result);

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
            <div class="search-section">
                <div class="row">
                    <div class="col-xs-12">
                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                            <div class="input-group">
                                <input type="hidden" name="search_param" value="all" id="search_param">
                                <input type="text" class="form-control" name="search_text" value="" placeholder="Search term..." required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 text-danger">
                    <h4><strong>MANAGE PROSPECT - FUNDED</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <?php $all_prospect_sources = $admin_object->get_all_prospect_source(); ?>

                        <form data-toggle="validator" class="form-inline" role="form" method="post" action="">

                            <div class="form-group">
                                <label for="prospect_source">Source:</label>
                                <select name="prospect_source" class="form-control" id="prospect_source" required>
                                    <option value="all"> All Sources</option>
                                    <?php if(isset($all_prospect_sources) && !empty($all_prospect_sources)) { foreach ($all_prospect_sources as $row) { ?>
                                        <option value="<?php echo $row['prospect_source_id']; ?>" <?php if(isset($_SESSION['prospect_source_filter']) && $row['prospect_source_id'] == $_SESSION['prospect_source_filter']) { echo "selected='selected'"; } ?>><?php echo $row['source_name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <input name="apply_filter" type="submit" class="btn btn-primary" value="Apply Filter">
                        </form>

                        <br /><hr />

                        <p>List of prospect that have been added to the system.</p>

                        <?php if(isset($all_prospect) && !empty($all_prospect)) { require 'layouts/pagination_links.php'; } ?>

                        <?php if(isset($all_prospect) && !empty($all_prospect)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Source</th>
                                <th>Order Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($all_prospect) && !empty($all_prospect)) {
                                foreach ($all_prospect as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['email_address']; ?></td>
                                        <td><?php echo $row['phone_number']; ?></td>
                                        <td><?php echo $row['source_name']; ?></td>
                                        <td><?php echo datetime_to_text2($row['created']); ?></td>
                                        <td>
<!--                                            <a title="Comment" class="btn btn-success" href="prospect_sales.php?x=--><?php //echo encrypt($row['prospect_biodata_id']); ?><!--&pg=--><?php //echo $currentpage; ?><!--"><i class="glyphicon glyphicon-comment icon-white"></i> </a>-->
                                            <a target="_blank" title="View" class="btn btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                        </td>
                                    </tr>
                                <?php } } else { echo "<tr><td colspan='5' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                            </tbody>
                        </table>

                        <?php if(isset($all_prospect) && !empty($all_prospect)) { ?>
                            <div class="tool-footer text-right">
                                <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if(isset($all_prospect) && !empty($all_prospect)) { require 'layouts/pagination_links.php'; } ?>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>