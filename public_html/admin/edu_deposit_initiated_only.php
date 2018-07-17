<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if(isset($_POST['search']) && strlen($_POST['search_text']) > 3) {
    $search_text = $db_handle->sanitizePost($_POST['search_text']);
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
            FROM user_edu_deposits AS ued
            INNER JOIN user AS u ON ued.user_code = u.user_code
            WHERE ued.status = '1' AND (u.email LIKE '%$search_text%' OR u.phone LIKE '%$search_text%' OR u.last_name LIKE '%$search_text%' OR u.first_name LIKE '%$search_text%') AND u.user_code  NOT IN(SELECT u.user_code FROM user_edu_deposits AS ued
            INNER JOIN user AS u ON ued.user_code = u.user_code
            WHERE ued.status = '3') GROUP BY ued.user_code
            ORDER BY ued.created DESC ";
} else {
    $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone, MAX(ued.created) AS created
            FROM user_edu_deposits AS ued
            INNER JOIN user AS u ON ued.user_code = u.user_code
            WHERE ued.status = '1' AND u.user_code  NOT IN(SELECT u.user_code FROM user_edu_deposits AS ued
            INNER JOIN user AS u ON ued.user_code = u.user_code
            WHERE ued.status = '3') GROUP BY ued.user_code
            ORDER BY created DESC ";
}
$numrows = $db_handle->numRows($query);

// For search, make rows per page equal total rows found, meaning, no pagination
// for search results
if (isset($_POST['search_text'])) {
    $rowsperpage = $numrows;
} else {
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
$education_deposit = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Education Deposit Initiated</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Education Deposit Initiated" />
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
                            <h4><strong>EDUCATION - UNCOMPLETED DEPOSIT INITIATED</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p class="pull-left">List of all FxAcademy Students who have initiated deposit But have not completed It.</p>
                                <a href="edu_deposit_initiated.php"><button class="btn btn-default btn-sm pull-right">Go To Education Deposit Initiated <i class="glyphicon glyphicon-arrow-right"></i></button></a>
                                <div class="row">
                                <form action="" method="post" class="form-horizontal col-sm-12">
                                    <input style="width: 350px; box-sizing: border-box; border: 2px solid #ccc;
    border-radius: 4px;" class="form-input" type="text" name="search_text" placeholder="Enter Client Name, Email or Phone No....">
                                    <button name="search" type="submit" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i></button>
                                </form>
                                </div>
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                        $count = 1;
                                        if(isset($education_deposit) && !empty($education_deposit)) { foreach ($education_deposit as $row) {
                                            $table_design = $count % 2 == 0 ? "success" : "warning";
                                            $count++;
                                        ?>
                                        <tr class="<?php echo $table_design; ?>">
                                            <td><strong><?php echo $row['full_name']; ?></strong></td>
                                            <td><strong><?php echo $row['phone']; ?></strong></td>
                                            <td><strong><?php echo $row['email']; ?></strong></td>
                                            <?php
                                            $selected_trans = $education_object->get_last_trans_id($row['user_code']);
                                            if(isset($selected_trans)) { foreach ($selected_trans as $data) {
                                                ?>
                                                <td>
                                                    <a class="btn btn-info" href="edu_deposit_process.php?x=initiated&id=<?php echo encrypt($data['trans_id']); ?>" title="Comment on Students last Transaction"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
                                                </td>
                                            <?php } } ?>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='7' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($education_deposit) && !empty($education_deposit)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if(isset($education_deposit) && !empty($education_deposit)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>