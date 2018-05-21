<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_POST['file_upload']))
{
    if(!isset($_FILES["csv_file"]["name"]) || empty($_FILES["csv_file"]["name"])) {$message_error = "Please select a file for upload.";}
    $imageFileType = pathinfo($_FILES["csv_file"]["name"],PATHINFO_EXTENSION);
    if($imageFileType != "csv"){$message_error = "Please select a CSV file for upload.";}
    $target_dir = "../images/";
    $temp = explode(".", $_FILES["csv_file"]["name"]);
    $newfilename = round(time()) . '.' . end($temp);
    $target_file = $target_dir.$newfilename;
    move_uploaded_file($_FILES["csv_file"]["tmp_name"], $target_file);
    $file_contents = file_get_contents($target_file);
    $file_contents = explode("\n", $file_contents);

    $csv_content = array();
    foreach ($file_contents as $row)
    {
        if(!empty($row))
        {
            $row_contents = explode(",", $row);
            //$count = count($csv_content);
            $_full_name = split_name(trim($row_contents[3]));
            $_email = strtolower(trim($row_contents[2]));
            $_phone = strtolower(trim(str_replace('p:', '', $row_contents[4]))) ;
            $_interest = $row_contents[1];
            $created = str_replace('T', ' ', $row_contents[0]);
            $created = str_replace('+01:00', '', $created);
            $obj_loyalty_training->add_lead($_full_name['first_name'], $_full_name['last_name'], $_email, $_phone, $_interest, 2, $created);
        }
    }
    //Delete the uploaded file
    $delete_file = unlink($target_file);
    if($delete_file) {$message_success = "Upload Successfull.";}
    else{ $message_error = "The upload failed, please try again.";}
}

if(isset($_POST['cat']) && !empty($_POST['cat'])){ $_SESSION['cat'] = $_POST['cat'];}

if(empty($_SESSION['cat'])){$_SESSION['cat'] = '1';}

$cat = $_SESSION['cat'];

switch ($cat)
{
    case '1':
        $query = "SELECT *, user.user_code, campaign_leads.phone 
FROM campaign_leads, user 
WHERE campaign_leads.email = user.email 
ORDER BY campaign_leads.created DESC";
        $msg = "all leads.";
        break;
    case '2':
        $query = "SELECT *, user.user_code, campaign_leads.phone 
FROM campaign_leads, user 
WHERE campaign_leads.email = user.email
 AND campaign_leads.interest = '1'
ORDER BY campaign_leads.created DESC";
        $msg = "all Training leads";
        break;
    case '3':
        $query = "SELECT *, user.user_code, campaign_leads.phone 
FROM campaign_leads, user 
WHERE campaign_leads.email = user.email
 AND campaign_leads.interest = '2'
ORDER BY campaign_leads.created DESC";
        $msg = "all ILPR leads";
        break;
    case '4':
        $search = $db_handle->sanitizePost($_POST['search']);
        $query = "SELECT *, user.user_code, campaign_leads.phone 
FROM campaign_leads, user 
WHERE campaign_leads.email = '$search'
ORDER BY campaign_leads.created DESC";
        $msg = "Results For ".$search;
        break;
    default:
        $query = "SELECT *, user.user_code, campaign_leads.phone 
FROM campaign_leads, user 
WHERE campaign_leads.email = user.email 
ORDER BY campaign_leads.created DESC";
        $msg = "All leads.";
        break;
}

//$query = "SELECT *, user.user_code, campaign_leads.phone FROM campaign_leads, user WHERE campaign_leads.email = user.email ORDER BY campaign_leads.created DESC";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if(isset($_GET['pg']) && is_numeric($_GET['pg'])){$currentpage = (int) $_GET['pg'];}
else{$currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$new_leads = $db_handle->fetchAssoc($result);
$client_operation = new clientOperation();
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
        <script>
            function show_name() {
                document.getElementById('file_show_name').value = document.getElementById('file_select').files.item(0).name;
                document.getElementById('file_upload').disabled = false;
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
                            <h4><strong>MANAGE CAMPAIGN LEADS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                    <button id="file_upload" data-target="#upload_confirm" data-toggle="modal"  type="button" class="btn btn-sm btn-success">Upload File</button>
                            </div>
                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="">
                                <!-- Modal - confirmation boxes -->
                                <div id="upload_confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 class="modal-title">Upload Leads</h4></div>
                                            <div class="modal-body">
                                                <p>Click the button below to select a file for upload</p>
                                                <span class="input-group-btn"><label class="btn btn-default" for="file_select">Select File</label>
                                                                <!--<input  name="csv_file" style="display: none" id="file_select" class="btn btn-default" type='file' />--></span>
                                                <input onchange="show_name()" name="csv_file" style="display: none" id="file_select" class="form-control" type='file' accept=".csv" />
                                                <input placeholder="Selected filename..." id="file_show_name" name="file_show_name"  type="text" class="form-control" disabled/>
                                            </div>
                                            <div class="modal-footer">
                                                <input name="file_upload" type="submit" class="btn btn-sm btn-success" value="Upload !">
                                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" title="Close">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="col-sm-12">
                                <p>Below is the list of <?php echo $msg; ?>.</p>
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <center>
                                        <div class="input-group-sm">
                                    <button class="btn btn-sm <?php if($_SESSION['cat'] == '1'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="1">All Leads</button>
                                    <button class="btn btn-sm <?php if($_SESSION['cat'] == '2'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="2">All Training Leads</button>
                                    <button class="btn btn-sm <?php if($_SESSION['cat'] == '3'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="3">All ILPR Leads</button>
                                            <form method="post" action="campaign_leads.php" data-toggle="validator" class="form-vertical" role="form">
                                                <input type="text" class="input" name="search" placeholder="Enter email address">
                                                <button class="btn btn-sm <?php if($_SESSION['cat'] == '4'){echo 'btn-info';}else{echo 'btn-default';} ?>" name="cat" type="submit" value="4">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </form>
                                    </div>
                                    </center>
                                    <br/><br/>
                                </form>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="3">Details</th>
                                            <th colspan="1">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($new_leads) && !empty($new_leads))
                                    {
                                        foreach ($new_leads as $row)
                                        {
                                            $client_ilpr_account = $client_operation->get_client_ilpr_accounts_by_code($row['user_code']);
                                            $client_non_ilpr_account = $client_operation->get_client_non_ilpr_accounts_by_code($row['user_code']);
                                            $education_history = $education_object->get_client_lesson_history($row['user_code']);
                                            $client_deposits = $system_object->get_all_funding($row['user_code']);
                                            $c_count = count($client_deposits);
                                            $e_count = count($education_history);
                                            $total_point = $client_operation->get_loyalty_point_earned($row['user_code']);
                                            $last_trade_date = $client_operation->get_last_trade_detail($row['user_code'])['date_earned'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <b>Full Name:</b> <?php echo strtoupper($row['l_name']);?> <?php echo $row['f_name'];?> <?php echo $row['m_name'];?><br/>
                                                    <span><b>Email:</b> <?php echo $row['email'];?></span><br/>
                                                    <span><b>Phone:</b> <?php echo $row['phone'];?></span><br/>
                                                    <span><b>Major Interest:</b> <?php echo $obj_loyalty_training->lead_interest($row['interest'])?></span><br/>
                                                </td>
                                                <td>
                                                    <?php if($row['interest'] == '1'): ?>
                                                    <span><b>FxAcademy:</b><br/>
                                                            First Login Date -> <?php if(!empty($education_history[0]['lesson_log_date'])){ echo datetime_to_text($education_history[0]['lesson_log_date']);}?><br/>
                                                            Last Course -> <?php if(!empty($education_history[$e_count - 1]['course_title'])){ echo $education_history[$e_count - 1]['course_title'];}?><br/>
                                                            Last Lesson -> <?php echo $education_history[$e_count - 1]['lesson_title'];?><br/>
                                                            Last Seen -> <?php if(!empty($education_history[$e_count - 1]['lesson_log_date'])){ echo datetime_to_text($education_history[$e_count - 1]['lesson_log_date']);}?>
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($row['interest'] == '2'): ?>
                                                        <span><b>Loyalty Points Earned:</b> <?php if(!empty($total_point)) { echo $total_point; } else { echo '0.00'; } ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span><b>Instaforex Account: </b><br/>
                                                        First Fund Amount -> $<?php echo number_format($client_deposits[0]['dollar_ordered'], 2, '.', ','); ?><br/>
                                                        First Fund Date -> <?php if(!empty($client_deposits[0]['created'])){echo date_to_text($client_deposits[0]['created']);}?><br/>
                                                        Last Fund Date -> <?php if(!empty($client_deposits[$c_count - 1]['created'])){echo date_to_text($client_deposits[$c_count - 1]['created']);} ?><br/>
                                                        <!--First Trade Date -> <br/>-->
                                                        Last Trade Date -> <?php if(!empty($last_trade_date)){ echo date_to_text($last_trade_date); }?><br/>
                                                        Account Numbers -> <br/>
                                                        <?php
                                                        if(isset($client_ilpr_account) && !empty($client_ilpr_account)) {foreach ($client_ilpr_account as $key){echo $key['ifx_acct_no']."(ILPR), ";}}
                                                        if(isset($client_non_ilpr_account) && !empty($client_non_ilpr_account)){foreach ($client_non_ilpr_account as $key){echo $key['ifx_acct_no']."(None-ILPR), ";}}
                                                        ?>
                                                </td>
                                                <td>
                                                    <!--<input data-toggle="toggle" type="checkbox">
                                                    <br/>-->
                                                    <a title="View" class="btn btn-sm btn-success" href="client_reach.php?x=<?php echo encrypt($row['user_code']); ?>&r=<?php echo 'campaign_leads'; ?>&c=<?php echo encrypt('NEW CAMPAIGN LEADS'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i></a>
                                                    <br/><br/>
                                                    <a target="_blank" title="View" class="btn btn-sm btn-info" href="client_detail.php?id=<?php echo encrypt($row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                                    <br/><br/>
                                                    <a title="View" class="btn btn-sm btn-info" href="edu_free_training_view.php?x=<?php echo encrypt($row['lead_id']); ?>&pg=<?php echo $currentpage; ?>&selector=1"><i class="glyphicon glyphicon-eye-open icon-white"></i></a>
                                                    <br/><br/>
                                                    <a class="btn btn-sm btn-info" title="Send Email" href="campaign_email_single.php?name=<?php $name = $row['f_name']." ".$row['m_name']." ".$row['l_name']; echo  encrypt_ssl($name).'&email='.encrypt_ssl($row['email']);?>" ><i class="glyphicon glyphicon-envelope"></i></a>
                                                    <br/><br/>
                                                    <a class="btn btn-sm btn-info" title="Send SMS" href="campaign_sms_single.php?lead_phone=<?php echo encrypt_ssl($row['phone']) ?>"><i class="glyphicon glyphicon-phone-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($new_leads) && !empty($new_leads)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                    </div>
                                    <?php if(isset($new_leads) && !empty($new_leads)) { include 'layouts/pagination_links.php'; } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    </div>
                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                </div>
            </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>