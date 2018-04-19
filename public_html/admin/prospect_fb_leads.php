<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_POST['file_upload']))
{
    if(!isset($_FILES["csv_file"]["name"]) || empty($_FILES["csv_file"]["name"])) {   $message_error = "Please select a file for upload.";}
    $imageFileType = pathinfo($_FILES["csv_file"]["name"],PATHINFO_EXTENSION);
    if($imageFileType != "csv"){$message_error = "Please select a CSV file for upload.";}
    $target_dir = "../images/";
    $temp = explode(".", $_FILES["csv_file"]["name"]);
    $newfilename = round(time()) . '.' . end($temp);
    $target_file = $target_dir.$newfilename;
    move_uploaded_file($_FILES["csv_file"]["tmp_name"], $target_file);
    $file_contents = file_get_contents($target_file);
    $file_contents = explode("\n", $file_contents);
    //Delete the file header
    unset($file_contents[0]);
    $csv_content = array();
    $new_leads = array();
    $choice_yes = "i_have_traded_forex_before.";
    foreach ($file_contents as $row)
    {
        if(!empty($row))
        {
            $row_contents = explode(",", $row);
            $count = count($csv_content);
            $_full_name = trim($row_contents[13]);
            $_email = strtolower(trim($row_contents[12]));
            $_phone = strtolower(trim(str_replace('p:', '', $row_contents[14]))) ;
            $_choice = $row_contents[11];
            if($_choice == $choice_yes)
            {
                if($obj_loyalty_training->is_duplicate_loyalty($_email, $_phone))
                {
                    var_dump($obj_loyalty_training->is_duplicate_loyalty($_email, $_phone));
                    $leads_count = count($new_leads);
                    $new_leads[$leads_count]["name"] = $_full_name;
                    $new_leads[$leads_count]["email"] = $_email;
                    $new_leads[$leads_count]["phone"] = $_phone;
                    $new_leads[$leads_count]["choice"] = "Experienced";
                    $obj_loyalty_training->add_loyalty($_full_name, $_email, $_phone);
                }
            }
            else
            {
                if($obj_loyalty_training->is_duplicate_training($_email, $_phone))
                {
                    $leads_count = count($new_leads);
                    $new_leads[$leads_count]["name"] = $_full_name;
                    $new_leads[$leads_count]["email"] = $_email;
                    $new_leads[$leads_count]["phone"] = $_phone;
                    $new_leads[$leads_count]["choice"] = "Not Experienced";
                    $obj_loyalty_training->add_training($_full_name, $_email, $_phone);
                }
            }
        }
    }
    //Delete the uploaded file
    $delete_file = unlink($target_file);
    if($delete_file)
    {
        $message_success = "Upload Successfull.";
    }
    else
    {
        $message_error = "The upload failed, please try again.";
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
                            <h4><strong>MANAGE FACEBOOK LEADS</strong></h4>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <?php require_once 'layouts/feedback_message.php'; ?>
                                    </div>
                                <div class="col-sm-12 well">
                                    <p>Click the button below to select a file for upload</p>
                                    <form data-toggle="validator" class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="">
                                        <div id="search" class="col-sm-8 form-group input-group">
                                            <span class="input-group-btn">
                                                <label class="btn btn-default" for="file_select">Select File</label>
                                                <!--<input  name="csv_file" style="display: none" id="file_select" class="btn btn-default" type='file' />-->
                                            </span>
                                            <input onchange="show_name()" name="csv_file" style="display: none" id="file_select" class="form-control" type='file' accept=".csv" />
                                            <input placeholder="Selected filename..." id="file_show_name" name="file_show_name"  type="text" class="form-control" disabled/>
                                            <span class="input-group-btn">
                                               <button id="file_upload" data-target="#upload_confirm" data-toggle="modal"  type="button" class="btn btn-success" disabled>Upload File</button>
                                            </span>
                                        </div>
                                        <!-- Modal - confirmation boxes -->
                                        <div id="upload_confirm" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                        <h4 class="modal-title">Upload Confirmation</h4></div>
                                                    <div class="modal-body">Are you sure the contents of the selected file should be uploaded?</div>
                                                    <div class="modal-footer">
                                                        <input name="file_upload" type="submit" class="btn btn-success" value="Approve !">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal" title="Close">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="col-sm-4"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <p>Latest leads.</p>
                                <?php if(isset($new_leads) && !empty($new_leads)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo 1 . " to " . count($new_leads) . " of " . count($new_leads); ?> entries</p>
                                    </div>
                                <?php } ?>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Trading Experience</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($new_leads) && !empty($new_leads)) {
                                        foreach ($new_leads as $row) {?>
                                            <tr>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php echo $row['choice']; ?></td>
                                            </tr>
                                        <?php } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                <?php if(isset($new_leads) && !empty($new_leads)) { ?>
                                    <div class="tool-footer text-right">
                                        <p class="pull-left">Showing <?php echo 1 . " to " . count($new_leads) . " of " . count($new_leads); ?> entries</p>
                                    </div>
                                <?php } ?>
                            </div>
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