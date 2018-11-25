<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$query = "SELECT * FROM prospect_ilpr_biodata ORDER BY created DESC ";
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }

$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$all_prospect = $db_handle->fetchAssoc($result);

// This section processes - views/live_account_ilpr_reg.php
if(isset($_POST['live_account_ilpr_reg'])) {
    $page_requested = "live_account_ilpr_reg_php";
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);
    $full_name = $db_handle->sanitizePost($_POST['full_name']);
    $email_address = $db_handle->sanitizePost($_POST['email']);
    $phone_number = $db_handle->sanitizePost($_POST['phone']);
    if(empty($full_name) || empty($email_address) || empty($phone_number) || empty($account_no)) {
        $message_error = "All fields must be filled.";
    } elseif (!check_email($email_address)) {
        $message_error = "You have provided an invalid email addresss. Please try again.";
    } else {

        $client_operation = new clientOperation();
        $log_new_ifxaccount = $client_operation->new_user($account_no, $full_name, $email_address, $phone_number, $type = 2, $my_refferer);

        if($log_new_ifxaccount) {
            $message_success = "Live Account Opening Completed";
        } else {
            $message_error = "Something went wrong, the operation could not be completed. Please try again.";
        }
    }
}

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
                            <h4><strong>MANAGE ILPR PROSPECTS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>List of prospects that have been added to the system.</p>
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
                                        <th>Created</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($all_prospect) && !empty($all_prospect)) {
                                        foreach ($all_prospect as $row) {?>
                                            <tr>
                                                <td><?php echo $row['f_name']." ".$row['m_name']." ".strtoupper($row['l_name']); ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php echo datetime_to_text2($row['created']); ?></td>
                                                <td>
                                                    <!--<a title="Comment" class="btn btn-success" href="prospect_sales.php?x=<?php /*echo encrypt_ssl($row['prospect_biodata_id']); */?>&pg=<?php /*echo $currentpage; */?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>-->
                                                    <button class="btn btn-sm btn-success" data-target="#bookmark<?php echo $row['prospect_biodata_id']; ?>" data-toggle="modal" ><i class="glyphicon glyphicon-bookmark"></i></button>
                                                    <!--Modal - confirmation boxes-->
                                                    <div id="bookmark<?php echo $row['prospect_biodata_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                                    <h4 class="modal-title">Bookmark Prospect</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <ul class="nav nav-tabs">
                                                                        <li class="active"><a data-toggle="tab" href="#pending">Pending</a></li>
                                                                        <li><a data-toggle="tab" href="#successfull">Successful</a></li>
                                                                    </ul>
                                                                    <div class="tab-content">
                                                                        <div id="pending" class="tab-pane fade in active">
                                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                                <input name="prospect_sales_contact_id" type="hidden" value="<?php echo $row['prospect_sales_contact_id']; ?>">
                                                                            <p><strong>Select A Comment</strong></p>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-4"><div class="radio"><label for="comment"><input id="comment" type="radio" name="comment" value="Requested For Call Back" id="" /> Requested For Call Back</label></div></div>
                                                                                <div class="col-sm-4"><div class="radio"><label for="comment"><input id="comment" type="radio" name="comment" value="Did Not Take Call" id="" /> Did Not Take Call</label></div></div>
                                                                                <div class="col-sm-4"><div class="radio"><label for="comment"><input id="comment" type="radio" name="comment" value="Unreachable Line" id="" /> Unreachable Line</label></div></div>
                                                                            </div>
                                                                                <hr/>
                                                                                <br/>
                                                                                <div class="form-group row">
                                                                                    <div class="col-sm-12">
                                                                                        <textarea name="comment" placeholder="Other Comments (if any)" id="comment" rows="3" class="form-control"></textarea>
                                                                                        <br>
                                                                                    </div>
                                                                                </div>
                                                                                <input name="process_pending" type="submit" class="btn btn-success" value="Proceed">
                                                                            </form>
                                                                        </div>
                                                                        <div id="successfull" class="tab-pane fade">
                                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                                <p>You have <strong>successfully</strong> contacted <?php echo $row['f_name']." ".$row['m_name']." ".$row['l_name']; ?>.
                                                                                    <br/>Enrol his/her Instaforex Account into the INSTAFXNG LOYALTY PROGRAM AND REWARDS (ILPR).
                                                                                </p>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                                                                    <div class="col-sm-9 col-lg-5">
                                                                                        <input name="full_name" type="text" class="form-control" id="full_name" value="<?php echo $row['f_name']." ".$row['m_name']." ".$row['l_name']; ?>" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="email">Email Address:</label>
                                                                                    <div class="col-sm-9 col-lg-5">
                                                                                        <input name="email" type="text" class="form-control" id="email" value="<?php echo $row['email']; ?>" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                                                                    <div class="col-sm-9 col-lg-5">
                                                                                        <input name="phone" type="text" class="form-control" id="phone" value="<?php echo $row['phone']; ?>" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="control-label col-sm-3" for="ifx_acct_no">Instaforex Account Number:</label>
                                                                                    <div class="col-sm-9 col-lg-5">
                                                                                        <input name="ifx_acct_no" type="text" class="form-control" id="ifx_acct_no" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <div class="col-sm-offset-3 col-sm-9"><input name="live_account_ilpr_reg" type="submit" class="btn btn-success" value="Submit" /></div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
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