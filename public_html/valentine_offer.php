<?php
require_once 'init/initialize_general.php';
$thisPage = "Promo";

$page_requested = "";

// This section processes - views/val_offer_info.php
if(isset($_POST['val_offer_info'])) {
    $account_no = $db_handle->sanitizePost($_POST['ifx_acct_no']);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();

    if($user_ifx_details) {
        extract($user_ifx_details); // turn table columns selected into variables

        $page_requested = 'val_offer_upload_php';

    } else {
        $message_error = "Your account details could not be found, please contact support";
    }
}

if(isset($_POST['val_offer_upload'])) {
    $page_requested = 'val_offer_upload_php';

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $client_operation = new clientOperation($account_no);
    $user_ifx_details = $client_operation->get_client_data();
    extract($user_ifx_details);

    if($_FILES["pictures"]["error"] == UPLOAD_ERR_OK) {
        if(isset($_FILES["pictures"]["name"])) {
            $tmp_name = $_FILES["pictures"]["tmp_name"];
            $name = strtolower($_FILES["pictures"]["name"]);

            // Get file extension of original uploaded file and create a new file name
            $extension = explode(".", $name);

            new_name:
            $name_string = rand_string(25);
            $newfilename = $name_string . '.' . end($extension);
            $picture = strtolower($newfilename);

            if(file_exists("images/val-pics/$picture")) {
                goto new_name;
            }

            move_uploaded_file($tmp_name, "images/val-pics/$picture");
        }
    }

    $query = "INSERT INTO user_val_2017 (user_code, val_pics) VALUES
                ('$client_user_code', '$picture')";
    $db_handle->runQuery($query);

    $client_id = $db_handle->insertedId();

    if(!empty($client_id)) {

        $val_link = "https://instafxng.com/my_val/id/" . $client_id . "/";
        $subject = "Instafxng Who's Your Valentine Contest";
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_full_name,</p>

            <p>Congratulations, your Valentine page has been generated. You now have a chance to be one
            of our winners that would be rewarded with $20 Valentine gift.<br /></p>

            <p>See your picture page by following the link below, visit the link, click the share button
             to share with your friends on Facebook, your friends will click to visit the picture page
             and like your pictures, the more likes you get the better your chances.<br /></p>

            <p>Your Link: $val_link</p><br />

            <p>Happy Valentine's Day.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
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
        $system_object->send_email($subject, $body, $client_email, $client_full_name);

        $page_requested = 'val_offer_complete_php';
    } else {
        $message_error = "An error occurred, please try again.";
    }


}

switch($page_requested) {
    case '': $val_offer_info_php = true; break;
    case 'val_offer_upload_php': $val_offer_upload_php = true; break;
    case 'val_offer_complete_php': $val_offer_complete_php = true; break;
    default: $val_offer_info_php = true;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Special Valentine Offer</title>
        <meta name="title" content=" " />
        <meta name="keywords" content=" ">
        <meta name="description" content=" ">
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>
                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="images/happy_val_day.jpg" alt="Happy Valentine's Day" class="img-responsive" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="section-tint super-shadow">
                                <h3 class="text-center">Happy Valentine. We love you!</h3>
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <?php
                                    if($val_offer_info_php) { include_once 'views/val_offer/val_offer_info.php'; }
                                    if($val_offer_upload_php) { include_once 'views/val_offer/val_offer_upload.php'; }
                                    if($val_offer_complete_php) { include_once 'views/val_offer/val_offer_complete.php'; }
                                ?>

                            </div>

                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>