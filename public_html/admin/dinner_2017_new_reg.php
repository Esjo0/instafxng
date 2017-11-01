<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

// Process submitted form
if (isset($_POST['process']))
{
    foreach($_POST as $key => $value)
    {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);
    if(empty($full_name) || empty($state_of_residence) || !isset($ticket_type))
    {
        $message_error = "All fields are compulsory, please try again.";
    }
    elseif (!check_email($email))
    {
        $message_error = "You have provided an invalid email address. Please try again.";
    }
    elseif ($admin_object->dinner_guest_2017_is_duplicate($email))
    {
        $message_error = "Duplicate";
    }
    else
    {
        $new_reg = $admin_object->add_new_dinner_guest_2017($full_name, $email, $phone_number, $ticket_type, $state_of_residence, $comments);
        if($new_reg)
        {
            $subject = "AFRO NITE 2017";
            $message = <<<MAIL
    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $full_name,</p>

            <p>It’s been almost 365 days and a lot has happened during the year,
            but get ready for one more!</p>

            <p>I want to take this opportunity to appreciate you for showing
            consistent confidence in the services we render and allowing us to
            service you this year. You are truly one of the most loyal clients
            of our company and serving you remains our pleasure.</p>

            <p>To roundup the year together, I humbly request that you join us
            for an exciting and entertaining time at our annual Christmas Dinner
            Event specially designed so you could relax and have fun with the
            members of our team and other Forex traders like yourself.</p>

            <p>This year’s dinner is themed the <strong>Classic 80s Night</strong>. When you step
            inside the venue; the music, the theme and overall ambience will
            transport you to a simpler and more innocent time where you can have the
            feel of what it used to be in the 80s.</p>

            <p>If you love to have fun, you should be at this event as the outfit,
            the looks the music and the venue will represent what it used to be
            from way back 80s, and there would be lots of exciting activities lined
            up alongside the 3 course dinner to be served.</p>

            <p style="text-align: center"><strong>One last thing ...</strong></p>

            <p>We have one last surprise for you before the year ends, you will
            witness the launch of a new service created to make you more money
            daily even as you trade.</p>

            <!---<p style="text-align: center">You can
            <a href="https://instafxng.com/dinner.php?x=$id_encrypt">click here</a>
            to reserve your seat now.</p>-->

            <p><strong>NOTE: Admission is strictly by Invitation.</strong></p>

            <p>It would be an honor to have you at the event as I earnestly look
            forward to welcoming you and meeting you in person.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Fujah Abideen,<br />
            Corporate Communications Manager<br />
                www.instafxng.com</p>
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
                <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
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
            $system_object->send_email($subject, $message, $email, $full_name);
            $message_success = "You have successfully created a new dinner reservation.";
        }
        else
        {
            $message_error = "Looks like something went wrong or you didn't make any change.";
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
    <title>Instaforex Nigeria | New 2017 Dinner Registration</title>
    <meta name="title" content="Instaforex Nigeria | New 2017 Dinner Registration" />
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
                        <h4><strong>NEW 2017 DINNER REGISTRATION</strong></h4>
                    </div>
                </div>

                <div class="section-tint super-shadow">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php require_once 'layouts/feedback_message.php'; ?>
                            <p>Fill the form below to add a guest.</p>
                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="full_name">Full Name:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                            <input name="full_name" type="text" id="full_name" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="email">Email Address:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                            <input name="email" type="text" id="email" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="phone_number">Phone Number:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                            <input name="phone_number" type="text" id="phone_number" value="" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="ticket_type">Ticket Type:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-ticket fa-fw"></i></span>
                                            <select id="ticket_type" name="ticket_type" class="form-control" required>
                                                <option></option>
                                                <option value="5">Staff</option>
                                                <option value="4">Hired Help</option>
                                                <option value="0">Single</option>
                                                <option value="1">Plus One (Double)</option>
                                                <option value="2">VIP Single</option>
                                                <option value="3">VIP Plus One (Double)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="state_of_residence">State Of Residence:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-location-arrow fa-fw"></i></span>
                                            <select id="state_of_residence" name="state_of_residence" class="form-control" required>
                                                <option></option>
                                                <option value="Abia State">Abia State</option>
                                                <option value="Adamawa State">Adamawa State</option>
                                                <option value="Akwa Ibom State">Akwa Ibom State</option>
                                                <option value="Anambra State">Anambra State</option>
                                                <option value="Bauchi State">Bauchi State</option>
                                                <option value="Bayelsa State">Bayelsa State</option>
                                                <option value="Benue State">Benue State</option>
                                                <option value="Borno State">Borno State</option>
                                                <option value="Cross River State">Cross River State</option>
                                                <option value="Delta State">Delta State</option>
                                                <option value="Ebonyi State">Ebonyi State</option>
                                                <option value="Edo State">Edo State</option>
                                                <option value="Ekiti State">Ekiti State</option>
                                                <option value="Enugu State">Enugu State</option>
                                                <option value="FCT Abuja">FCT Abuja</option>
                                                <option value="Gombe State">Gombe State</option>
                                                <option value="Imo State">Imo State</option>
                                                <option value="Jigawa State">Jigawa State</option>
                                                <option value="Kaduna State">Kaduna State</option>
                                                <option value="Kano State">Kano State</option>
                                                <option value="Katsina State">Katsina State</option>
                                                <option value="Kebbi State">Kebbi State</option>
                                                <option value="Kogi State">Kogi State</option>
                                                <option value="Kwara State">Kwara State</option>
                                                <option value="Lagos State">Lagos State</option>
                                                <option value="Nasarawa State">Nasarawa State</option>
                                                <option value="Niger State">Niger State</option>
                                                <option value="Ogun State">Ogun State</option>
                                                <option value="Ondo State">Ondo State</option>
                                                <option value="Osun State">Osun State</option>
                                                <option value="Oyo State">Oyo State</option>
                                                <option value="Plateau State">Plateau State</option>
                                                <option value="Rivers State">Rivers State</option>
                                                <option value="Sokoto State">Sokoto State</option>
                                                <option value="Taraba State">Taraba State</option>
                                                <option value="Yobe State">Yobe State</option>
                                                <option value="Zamfara State">Zamfara State </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="comments">Comments:</label>
                                    <div class="col-sm-9 col-lg-5">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                                            <textarea id="comments" rows="3" name="comments" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="button" data-target="#confirm-add-reg" data-toggle="modal" class="btn btn-success">Add Guest</button>
                                    </div>
                                </div>

                                <!--Modal - confirmation boxes-->
                                <div id="confirm-add-reg" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                                <h4 class="modal-title">Add New Dinner Guest</h4></div>
                                            <div class="modal-body">
                                                Are you sure you want to save this information?
                                                This action cannot be reversed.
                                            </div>
                                            <div class="modal-footer">
                                                <input name="process" type="submit" class="btn btn-success" value="Proceed">
                                                <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
                                            </div>
                                        </div>
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