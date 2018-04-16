<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) { redirect_to("login.php"); }

if(isset($_POST['file_upload']))
{
    if(!isset($_FILES["csv_file"]["name"]) || empty($_FILES["csv_file"]["name"]))
    {
        $message_error = "Please select a file for upload.";
    }
    $imageFileType = pathinfo($_FILES["csv_file"]["name"],PATHINFO_EXTENSION);
    if($imageFileType != "csv")
    {
        $message_error = "Please select a CSV file for upload.";
    }
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
        if($row != "")
        {
            $row_contents = explode(",", $row);
            $count = count($csv_content);
            $csv_content[$count]["full_name"] = trim($row_contents[13]);
            $csv_content[$count]["email"] = strtolower(trim($row_contents[12]));
            $csv_content[$count]["phone"] = strtolower(trim(str_replace('p:', '', $row_contents[14]))) ;
            $csv_content[$count]["choice"] = $row_contents[11];
            extract($csv_content[$count]);
            if($choice == $choice_yes)
            {
                if(!is_duplicate_loyalty($email, $phone))
                {
                    $leads_count = count($new_leads);
                    $new_leads[$leads_count]["name"] = $full_name;
                    $new_leads[$leads_count]["email"] = $email;
                    $new_leads[$leads_count]["phone"] = $phone;
                    add_loyalty($full_name, $email, $phone);
                }
            }
            else
            {
                if(!is_duplicate_training($email, $phone))
                {
                    $leads_count = count($new_leads);
                    $new_leads[$leads_count]["name"] = $full_name;
                    $new_leads[$leads_count]["email"] = $email;
                    $new_leads[$leads_count]["phone"] = $phone;
                    add_training($full_name, $email, $phone);
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
        $message_error = "THe upload failed, please try again.";
    }
}




function is_duplicate_training($email_address, $phone_number)
{
    global $db_handle;
    $query = "SELECT * FROM free_training_campaign WHERE phone = '$phone_number' OR email = '$email_address' ";
    return $db_handle->numRows($query) ? true : false;
}

function add_training($full_name, $email_address, $phone_number)
{
    global $db_handle;
    global $system_object;
    $client_full_name = $full_name;
    $full_name = str_replace(".", "", $full_name);
    $full_name = ucwords(strtolower(trim($full_name)));
    $full_name = explode(" ", $full_name);

    if (count($full_name) == 3) {
        $last_name = trim($full_name[0]);

        if (strlen($full_name[2]) < 3) {
            $middle_name = trim($full_name[2]);
            $first_name = trim($full_name[1]);
        } else {
            $middle_name = trim($full_name[1]);
            $first_name = trim($full_name[2]);
        }

    }
    else {
        $last_name = trim($full_name[0]);
        $middle_name = "";
        $first_name = trim($full_name[1]);
    }

    if (empty($first_name)) {
        $first_name = $last_name;
        $last_name = "";
    }

    $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone, campaign_period) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', '2')";
    $result = $db_handle->runQuery($query);
    $inserted_id = $db_handle->insertedId();
    if ($result)
    {
        $text_message = "This is the sms";
        //$system_object->send_sms($phone_number, $text_message);

        $subject = "Welcome to InstaFxNg $first_name";
        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>At InstaFxNg, we consider it a privilege to serve and provide you with excellent and unparalleled service at all times.</p>
           
            <p>We have been around for over 7 years providing Forex services to thousands of Nigerian traders, 
            ensuring that their deposit and withdrawal transactions are promptly responded to and that every 
            challenge is totally resolved.</p>
            <p>InstaForex Nigeria is built upon seven strong foundational values; Integrity, Commitment, Speed, 
            Focus, Empathy, Reliability, and Innovation.</p>
            <p>From making deposit into your account (locally and easily) to instant stress free withdrawals (to your bank account) to unmatched customer support, we have you covered. </p>
            <p>We are dedicated to working effectively to ensure swift service delivery to you consistently.</p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, kindly click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open an InstaForex account immediately.</p>
            <p>Very shortly you're going to receive a call welcoming you on board from our ever 
            cheerful customer service representatives who will assist you in opening an InstaForex 
            account so you can get started immediately.</p>
            <p>Don’t forget to click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open your InstaForex account. You can also click <a href="https://instafxng.com/deposit_funds.php">here</a> to fund your account so you can get started immediately.</p>
            <p>I’m so glad you are here and I can’t wait for you to start earning from your trades and in the InstaFxNg Loyalty Points and Reward Program.</p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
        //$system_object->send_email($subject, $message, $email_address, $first_name);

        $assigned_account_officer = $system_object->next_account_officer();
        $query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
        $db_handle->runQuery($query);
        // create profile for this client
        $client_operation = new clientOperation();
        $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
        //...//
    }
}

function is_duplicate_loyalty($email_address, $phone_number)
{
    global $db_handle;
    $query = "SELECT * FROM prospect_ilpr_biodata WHERE phone = '$phone_number' OR email = '$email_address' ";
    return $db_handle->numRows($query) ? true : false;
}

function add_loyalty($full_name, $email_address, $phone_number)
{
    global $db_handle;
    global $system_object;
    //$client_full_name = $full_name;
    $full_name = str_replace(".", "", $full_name);
    $full_name = ucwords(strtolower(trim($full_name)));
    $full_name = explode(" ", $full_name);
    if (count($full_name) == 3)
    {
        $last_name = trim($full_name[0]);
        if (strlen($full_name[2]) < 3) { $middle_name = trim($full_name[2]); $first_name = trim($full_name[1]);}
        else { $middle_name = trim($full_name[1]); $first_name = trim($full_name[2]); }
    }
    else{
        $last_name = trim($full_name[0]);
        $middle_name = "";
        $first_name = trim($full_name[1]);
    }
    if (empty($first_name))
    {
        $first_name = $last_name;
        $last_name = "";
    }
    $query = "INSERT INTO prospect_ilpr_biodata (f_name, l_name, m_name, email, phone) VALUE ('$first_name', '$last_name', '$middle_name', '$email_address', '$phone_number')";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $text_message = "This is the sms";
        //$system_object->send_sms($phone_number, $text_message);


        $subject = "Welcome to InstaFxNg $first_name";
        $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>
            <p>Welcome on board!</p>
            <p>I would like to take this opportunity to let you know how pleased and excited I am that you have chosen to trade with InstaForex Nigeria (www.InstaFxNg.com).</p>
            <p>You have joined over 14,000 Nigerians who make consistent income from the Forex market using the InstaForex platform and also earn more money just for trading.</p>
            <p>At InstaFxNg, we consider it a privilege to serve and provide you with excellent and unparalleled service at all times.</p>
           
            <p>We have been around for over 7 years providing Forex services to thousands of Nigerian traders, 
            ensuring that their deposit and withdrawal transactions are promptly responded to and that every 
            challenge is totally resolved.</p>
            <p>InstaForex Nigeria is built upon seven strong foundational values; Integrity, Commitment, Speed, 
            Focus, Empathy, Reliability, and Innovation.</p>
            <p>From making deposit into your account (locally and easily) to instant stress free withdrawals (to your bank account) to unmatched customer support, we have you covered. </p>
            <p>We are dedicated to working effectively to ensure swift service delivery to you consistently.</p>
            <p>To start your journey to earning more money in our Loyalty Rewards Program, kindly click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open an InstaForex account immediately.</p>
            <p>Very shortly you're going to receive a call welcoming you on board from our ever 
            cheerful customer service representatives who will assist you in opening an InstaForex 
            account so you can get started immediately.</p>
            <p>Don’t forget to click <a href="https://instafxng.com/live_account.php?id=lp">here</a> to open your InstaForex account. You can also click <a href="https://instafxng.com/deposit_funds.php">here</a> to fund your account so you can get started immediately.</p>
            <p>I’m so glad you are here and I can’t wait for you to start earning from your trades and in the InstaFxNg Loyalty Points and Reward Program.</p>
            <br/><br/>
            <p>Best Regards,</p>
            <p>Mercy,</p>
            <p>Client Relations Manager,</p>
            <p>InstaForex Nigeria</p>
            <p>www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos. </p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
        //$system_object->send_email($subject, $message, $email_address, $first_name);
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($new_leads) && !empty($new_leads)) {
                                        foreach ($new_leads as $row) {?>
                                            <tr>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
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