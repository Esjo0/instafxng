<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['submit'])) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    if(empty($full_name) || empty($email_address) || empty($phone_number) || empty($attendant)) {
        $message_error = "All fields must be filled, please try again";
    } elseif (!check_email($email_address)) {
        $message_error = "You have supplied an invalid email address, please try again.";
    } else {

        $client_full_name = $full_name;
        $full_name = str_replace(".", "", $full_name);
        $full_name = ucwords(strtolower(trim($full_name)));
        $full_name = explode(" ", $full_name);

        if(count($full_name) == 3) {
            $last_name = trim($full_name[0]);

            if(strlen($full_name[2]) < 3) {
                $middle_name = trim($full_name[2]);
                $first_name = trim($full_name[1]);
            } else {
                $middle_name = trim($full_name[1]);
                $first_name = trim($full_name[2]);
            }

        } else {
            $last_name = trim($full_name[0]);
            $middle_name = "";
            $first_name = trim($full_name[1]);
        }

        if(empty($first_name)) {
            $first_name = $last_name;
            $last_name = "";
        }

        $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone, attendant, entry_point) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number', $attendant, '$entry_point')";
        $result = $db_handle->runQuery($query);

        // create profile for this client
        $client_operation = new clientOperation();
        $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $attendant);
        //...//

        if($result) {

            $subject = "$first_name, your Journey to Consistent Income Starts Here";
            $body = <<<MAIL
<div style="margin: 0; padding: 0; width: 100%; -webkit-font-smoothing: antialiased; mso-margin-top-alt: 0px; mso-margin-bottom-alt: 0px; mso-padding-alt: 0px 0px 0px 0px; background: #ffffff;"><!--  header  -->
<table style="font-size: 14px; border: 0;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
<tbody>
<tr style="height: 30.75px;">
<td style="height: 30.75px;">
<table style="border-collapse: collapse; height: 112px;" width="596" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td style="width: 593.5px;" height="40">&nbsp;</td>
</tr>
<tr>
<td style="width: 593.5px;"><!--  Logo  -->
<table style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0" align="left">
<tbody>
<tr>
<td><a href="#"><img style="display: block; border: none!important;" src="https://www.instafxng.com/images/ifxlogo.png" alt="Instafxng" border="0" /> </a></td>
</tr>
</tbody>
</table>
<!--  navigation menu  -->
<table style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" border="0" cellspacing="0" cellpadding="0" align="right">
<tbody>
<tr>
<td height="3">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td style="width: 593.5px;" height="40">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!--  end header  --> <!--  billboard stlye 1  --><!--  end billboard  --> <!--  services  -->
<table style="border-collapse: collapse; height: 687px;" width="595" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff">
<tbody>
<tr style="height: 48.75px;">
<td style="height: 48.75px; width: 592.5px; font-size: 15px;">
<p style="text-align: justify;">Hello $first_name,</p>
<p style="text-align: justify;"> A very warm welcome to you. The first step on the journey 
            to making consistent income from Forex trading is getting adequate knowledge 
            and I'm glad yours has begun. </p>
<p style="text-align: justify;">Tighten your seat belt as this is going to be an amazing journey 
            and I can only giggle right now as I know that when you are done, 
            you will be armed with enough knowledge to conquer the world, in this case,
             take all that you deserve from life instead of just settling with what life has to offer.</p>
<ul>
                <li>	Be sure to give this training your 100% attention and thoroughly go through each lesson without 
                forgetting to attempt all the test exercises.</li><br/>
                <li>	Don’t hesitate to use any of the message box to ask a question when you need more clarity on 
                something or you have a hard time understanding a particular lesson and you will be swiftly responded to. </li><br/>
                <li>	Rest assured that I am fully committed to holding your hands even as I guide you through all the lessons of this course. </li>
            </ul>
            <p style="text-align: justify;">$first_name, brace up for you are about to start one heck of an amazing journey that leads you to getting
                all that you deserve from life. </p>
              <center><a href="http://bit.ly/2ffEeKl">You can click here to start the training.</a></center>
              <p>It’s a big Welcome once again from me to you and I look forward to seeing you on the other side.</p>
            <p>Best Regards,</p>
            <p>Mercy,<br />
                Client Relationship Manager,<br />
                www.instafxng.com</p>
            <br /><br />
</td>
</tr>
</tbody>
</table>
<!--  end services  --> <!--  testimonials  --><center>

<table style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#dfe7f2">
<tbody>
<tr>
<td style="text-align: center;"><a style="text-decoration: none; border: 0;" href="#"> <img style="display: inline-block;" title="Instafxng" src="https://www.instafxng.com/images/ifxlogo.png" alt="Instafxng" border="0" /> </a></td>
</tr>
<tr>
<td style="text-align: center; color: #bcc9dd; font-family: 'Raleway', arial; font-weight: 400; font-size: 14px; letter-spacing: .5px;"><a style="color: #646262; text-decoration: none;" href="https://instafxng.com/">Services</a> &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: #646262; text-decoration: none;" href="https://instafxng.com/blog.php">News</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a style="color: #646262; text-decoration: none;" href="https://instafxng.com/contact_info.php">Contact Us</a></td>
</tr>
<tr>
<td style="text-align: center; color: #646262; font-family: 'Raleway', arial; font-weight: 400; font-size: 12px; letter-spacing: .5px;">
<p>&copy; 2018 <a style="text-decoration: none; color: #646262;" href="https://instafxng.com/admin/www.instafxng.com" target="_blank">www.instafxng.com</a></p>
<p><strong>We're Social</strong></p>
<p><a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png" alt="" /></a> <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png" alt="" /></a> <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png" alt="" /></a> <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png" alt="" /></a> <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png" alt="" /></a></p>
<p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
<p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway,Lagos State.</p>
<p><strong>Office Number:</strong> 08028281192</p>
<p>This email was sent to you by Instant Web-Net Technologies Limited, the official Nigerian Representative of Instaforex, operator and administrator of the website www.instafxng.com</p>
<p>To ensure you continue to receive special offers and updates from us, please add support@instafxng.com to your address book.</p>
<p style="font-size: 9px;">You may click <a href="https://instafxng.com/unsubscribe.php">unsubscribe</a> if you wish to stop receiving newsletter emails and other special promotions, offers and complementary gifts.</p>
</td>
</tr>
</tbody>
</table>
<!--  end footer  --></div>
MAIL;
            $from_name = "Mercy - InstaFxNg";
            $system_object->send_email($subject, $body, $email_address, $first_name, $from_name);


            $message_success = "You have successfully registered the client.";
        } else {
            $message_error = "An error occurred, please try again. - Client Might have been Earlier Registered.";
        }
    }
}

$all_account_officers = $admin_object->get_all_account_officers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Free Training Registration</title>
    <meta name="title" content="Instaforex Nigeria | Free Training Registration" />
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
                    <h4><strong>NEW FREE TRAINING REGISTRATION</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php require_once 'layouts/feedback_message.php'; ?>

                        <p>Fill the form below to register a new free training client.</p>

                        <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                <div class="col-sm-9 col-lg-7"><input name="full_name" type="text" class="form-control" id="full_name" maxlength="120" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="email">Email Address:</label>
                                <div class="col-sm-9 col-lg-7"><input name="email_address" type="email" class="form-control" id="email" maxlength="50" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="phone">Phone Number:</label>
                                <div class="col-sm-9 col-lg-7"><input name="phone_number" type="text" class="form-control" id="phone" maxlength="11" required></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="attendant">Call Agent:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <select name="attendant" class="form-control" id="attendant" required>
                                        <?php foreach($all_account_officers as $key => $value) { ?>
                                            <option value="<?php echo $value['account_officers_id']; ?>"><?php echo $value['account_officer_full_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="attendant">Point of Entry:</label>
                                <div class="col-sm-9 col-lg-5">
                                    <select name="entry_point" class="form-control" id="entry" required>
                                        <option value="1">Incoming Calls</option>
                                        <option value="2">Whats App</option>
                                        <option value="3">Support Mails</option>
                                        <option value="4">Walk in Clients</option>
                                        <option value="5">Facebook Adverts</option>
                                        <option value="6">Referrals</option>
                                        <option value="7">Instagram</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input name="submit" type="submit" class="btn btn-success btn-lg" value="Register Client" />
                                </div>
                                <div class="col-sm-offset-3 col-sm-9">
                                    <br />
                                    <span>*All fields are required</span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>

    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>