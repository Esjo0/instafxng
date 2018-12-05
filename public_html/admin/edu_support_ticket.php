<?php
require_once("../init/initialize_admin.php");

if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['close_ticket'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);
    $ticket_code = decrypt_ssl(str_replace(" ", "+", $ticket_code));
    $ticket_code = preg_replace("/[^A-Za-z0-9 ]/", '', $ticket_code);
    $education_object->close_support_ticket($ticket_code);
}


if(isset($_POST['search_text']) && strlen($_POST['search_text']) > 3) {
    $search_text = $_POST['search_text'];
    $query = "SELECT uesr.user_edu_support_request_id, uesr.support_request_code, uesr.lesson_id, uesr.course_id,
            uesr.request, uesr.status, uesr.created, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
            FROM user_edu_support_request AS uesr
            INNER JOIN user AS u ON uesr.user_code = u.user_code
            WHERE uesr.status = '1'
            ORDER BY uesr.created DESC ";
} else {
    $query = "SELECT uesr.user_edu_support_request_id, uesr.support_request_code, uesr.lesson_id, uesr.course_id,
            uesr.request, uesr.status, uesr.created, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, u.email, u.phone
            FROM user_edu_support_request AS uesr
            INNER JOIN user AS u ON uesr.user_code = u.user_code
            WHERE uesr.status = '1'
            ORDER BY uesr.created DESC ";
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
$education_tickets = $db_handle->fetchAssoc($result);

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
                                        <input type="text" class="form-control" name="search_text" placeholder="Search term..." required>
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
                            <h4><strong>VIEW COURSE MESSAGES - OPEN</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p><a href="edu_support_ticket_closed.php" class="btn btn-default" title="Ticket - Closed"><i class="fa fa-arrow-circle-left"></i> Ticket - Closed</a></p>
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Listed below is a list of all support request from Forex Training Students, latest opened
                                support request are displayed first.</p>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>Request</th>
                                        <th>Created</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $count = 1;
                                        if(isset($education_tickets) && !empty($education_tickets)) { foreach ($education_tickets as $row) {
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Action <span class="caret"></span></a>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                                        <li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" title="View" href="edu_support_ticket_view.php?id=<?php echo encrypt_ssl($row['support_request_code']); ?>"><i class="fa fa-eye fa-fw"></i> View</a></li>
                                                        <li role="presentation"><a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#close-ticket-<?php echo $count; ?>"><i class="fa fa-lock fa-fw"></i> Close Ticket</a></li>
                                                    </ul>
                                                </div>

                                                <div id="close-ticket-<?php echo $count; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                        class="close">&times;</button>
                                                                <h4 class="modal-title">Close Ticket</h4></div>
                                                            <div class="modal-body">
                                                                <p>Do you want to close the selected ticket.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form class="form-horizontal" role="form" method="post" action="">
                                                                    <input type="hidden" name="ticket_code" value="<?php echo encrypt_ssl($row['support_request_code']); ?>" />
                                                                    <input name="close_ticket" type="submit" class="btn btn-danger" value="Close Ticket">
                                                                    <button type="submit" name="decline" data-dismiss="modal" class="btn btn-default">Cancel</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo substr($row['request'], 0, 160) . ' ...'; ?></td>
                                            <td><?php echo datetime_to_text($row['created']); ?></td>
                                        </tr>
                                    <?php $count++; } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>

                                <?php if(isset($education_tickets) && !empty($education_tickets)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if(isset($education_tickets) && !empty($education_tickets)) { require_once 'layouts/pagination_links.php'; } ?>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>