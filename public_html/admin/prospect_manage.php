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
        pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id
        FROM prospect_biodata AS pb
        INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
        WHERE pb.email_address LIKE '%$search_text%' OR pb.first_name LIKE '%$search_text%' OR pb.last_name LIKE '%$search_text%' OR pb.phone_number LIKE '%$search_text%'
        ORDER BY created DESC ";

    $numrows = $db_handle->numRows($query);
    $rowsperpage = $numrows;

} else {

    if(isset($_SESSION['prospect_source_filter']) && $_SESSION['prospect_source_filter'] <> 'all') {
        $prospect_source_filter = $_SESSION['prospect_source_filter'];

        $query = "SELECT pb.email_address, CONCAT(pb.first_name, SPACE(1), pb.last_name) AS full_name,
            pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id, psc.prospect_sales_contact_id,
            psc.prospect_id, psc.comment, psc.status, pb.prospect_source
            FROM prospect_biodata AS pb
            INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
            INNER JOIN prospect_sales_contact AS psc ON pb.prospect_biodata_id = psc.prospect_id
            WHERE psc.status = 'PENDING' AND ps.prospect_source_id = $prospect_source_filter
            ORDER BY pb.created DESC ";
    } else {
        $query = "SELECT pb.email_address, CONCAT(pb.first_name, SPACE(1), pb.last_name) AS full_name,
            pb.phone_number, pb.created, ps.source_name, pb.prospect_biodata_id, psc.prospect_sales_contact_id,
            psc.prospect_id, psc.comment, psc.status, pb.prospect_source
            FROM prospect_biodata AS pb
            INNER JOIN prospect_source AS ps ON pb.prospect_source = ps.prospect_source_id
            INNER JOIN prospect_sales_contact AS psc ON pb.prospect_biodata_id = psc.prospect_id
            WHERE psc.status = 'PENDING'
            ORDER BY pb.created DESC ";
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

///////////////////////////

if (isset($_POST['process_successfull']))
{
    extract($_POST);
    global $comments;
    if(isset($_POST['training_online']))
    {
        $comments .= $_POST['training_online'].', ';
        $parts = explode(' ', $_POST['full_name']);

        $firstname = $parts[0];
        $first_name = trim($firstname);
        $lastname = $parts[1];
        $last_name = trim($lastname);

        $query = "INSERT INTO free_training_campaign (first_name, last_name, email, phone) VALUE ('$first_name', '$last_name', '$email_address', '$phone_number')";
        $result = $db_handle->runQuery($query);
        $inserted_id = $db_handle->insertedId();

        if($result)
        {

            $assigned_account_officer = $system_object->next_account_officer();

            $query = "UPDATE free_training_campaign SET attendant = $assigned_account_officer WHERE free_training_campaign_id = $inserted_id LIMIT 1";
            $db_handle->runQuery($query);

            // create profile for this client
            $client_operation = new clientOperation();
            $log_new_client = $client_operation->new_user_ordinary($client_full_name, $email_address, $phone_number, $assigned_account_officer);
            //...//

            $subject = "Welcome to the world of Money making!";
            $body = <<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
            <hr />
            <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hi $first_name,</p>
            <p>I wanted to take a second to welcome you and to let you in on all the
            benefits you are going to enjoy being with us.</p>
            <p>My name is Bunmi, Client Relationship Manager at InstaForex Nigeria for over
            six years.</p>
            <p>You know, I'm truly excited and grateful that you've decided to join over
                seven thousands of Nigerians making consistent income from the Forex market
                during this economic recession Nigeria is undergoing.</p>
            <p>This is going to be something you haven't experienced before...</p>
            <p>Because you will be taught all you need to know to be profitable in Forex
                and you will be guided all the way. Below are a few of the things you are going
                to learn:</p>
            <ul>
                <li>How to trade profitably</li>
                <li>Money Management</li>
                <li>How to remain profitable while making money</li>
                <li>How to conquer greed</li>
            </ul>
            <p>But here's the thing...</p>
            <p>Remember... this is designed to teach you how to get to where you want to
                be (making consistent income in this economy meltdown).</p>
            <p>I believe each of these lessons are vital components for making money in
                this economy...</p>
            <p>So, here's what you can expect from me...</p>
            <p>Very shortly you're going to receive a call welcoming you on board and
                getting to know a little bit of you.</p>
            <p>Subsequently you will receive Emails.</p>
            <p>These emails will continue to educate you on how to trade and keep growing
                your Profits. (I promise not to disturb you with my mails).</p>
            <p>These are only for you, our subscriber.</p>
            <p>Sound fair? GOOD!</p>
            <p>Here's what you need to do now to get started...</p>
            <p><strong>STEP 1:</strong> Whitelist and prioritize all emails from Instafxng</p>
            <p>This is important!</p>
            <p>Not only will you receive updates about new articles on the blog, you'll
                also receive notifications about new projects we are working on, exclusive
                interviews and all the 'subscriber only' content I just told you about.</p>
            <p>But if my emails aren't getting through to you, you will miss these important
                updates and you won't receive the full benefits of being a subscriber.</p>
            <p>So please follow this quick one-step guide to make sure nothing slips through
                the cracks:</p>
            <p>1) If you are a Gmail user or you use any other web-based email that filters
                broadcasts away from your main inbox, be sure to 'drag' any emails from
                'Instafxng' into your Priority Inbox. (Again, you don't want to miss
                something.)</p>
            <p><strong>STEP 2:</strong> Take two-seconds and join the InstaForex Nigeria
                Facebook page, as this will be our primary method of sending signals, and
                again you won't want to miss a thing:</p>
            <p><strong>Facebook: https://www.facebook.com/InstaForexNigeria/</strong></p>
            <p>You can also follow us on Twitter and Instagram:</p>
            <p>
                <strong>Twitter: <a href='https://twitter.com/instafxng'>@instafxng</a><br />
                    Instagram: <a href='https://www.instagram.com/instafxng/'>@instafxng</a>
                </strong>
            </p>


            <p>If you need to get in touch with me directly, you can simply reply this mail</p>
            <p>Do you have any question or any inquiry? You can call us on any of our help
                desk lines 08182045184 or 08083956750</p>
            <p>Our help desk lines are always available from Monday through Fridays between
                9:00 a.m - 5:00 p.m</p>

            <br /><br />
            <p>Talk soon.</p>
            <p>Bunmi,<br />
                Client Relationship Manager,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaFxNg"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos State.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book. You may click
                    <a href='https://instafxng.com/unsubscribe.php'>unsubscribe</a> if you wish
                    to stop receiving newsletter emails and other special promotions, offers
                    and complementary gifts.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

            $from_name = "Bunmi - InstaFxNg";
            $system_object->send_email($subject, $body, $email_address, $first_name, $from_name);

        }


    }

    if(isset($_POST['training_offline']))
    {
        $comments .= $_POST['training_offline'].', ';
    }

    if(isset($_POST['open_acct_30']))
    {
        $comments .= $_POST['training_online'].', ';
    }

    if(isset($_POST['open_acct_55']))
    {
        $comments .= $_POST['open_acct_55'].', ';
    }

    if(isset($_POST['open_acct_20']))
    {
        $comments .= $_POST['open_acct_22'].', ';
    }

    if(isset($_POST['open_acct_edu']))
    {
        $comments .= $_POST['open_acct_edu'].', ';
    }

    $comments .= $_POST['comment'];
    $update = $obj_prospects->Update_successful($prospect_sales_contact_id, $comments);
    if($update)
    {
        $message_success = 'Operation Successful!';
    }
    else
    {
        $message_error = 'Operation Failed!';
    }
}

if(isset($_POST['process_pending']))
{
    extract($_POST);
    $update = $obj_prospects->Update_pending($prospect_sales_contact_id, $comment);
    if($update)
    {
        $message_success = 'Operation Successful!';
    }
    else
    {
        $message_error = 'Operation Failed!';
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
                            <h4><strong>MANAGE PROSPECT</strong></h4>
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
                                        <th>Created</th>
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
                                                    <!--<a title="Comment" class="btn btn-success" href="prospect_sales.php?x=<?php /*echo dec_enc('encrypt', $row['prospect_biodata_id']); */?>&pg=<?php /*echo $currentpage; */?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>-->
                                                    <button class="btn btn-success" data-target="#bookmark<?php echo $row['prospect_biodata_id']; ?>" data-toggle="modal" ><i class="glyphicon glyphicon-bookmark"></i></button>
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
                                                                                <input name="process_pending" type="submit" class="btn btn-success" value="Proceed">
                                                                            </form>
                                                                        </div>
                                                                        <div id="successfull" class="tab-pane fade">
                                                                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                                                            <p>You have <strong>successfully</strong> contacted <?php echo $row['full_name']; ?>.</p>
                                                                            <p>Use the checkboxes below to select <?php echo $row['full_name']; ?>'s interests. </p>
                                                                            <input name="prospect_sales_contact_id" type="hidden" value="<?php echo $row['prospect_sales_contact_id']; ?>">
                                                                                <input name="admin_code" type="hidden" value="<?php echo $_SESSION['admin_unique_code'] ?>">
                                                                                <input name="full_name" type="hidden" value="<?php echo $row['full_name']; ?>">
                                                                                <input name="email" type="hidden" value="<?php echo $row['email_address']; ?>">
                                                                                <input name="phone" type="hidden" value="<?php echo $row['phone_number']; ?>">
                                                                            <p><strong>Training</strong></p>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="training_online" value="Registered For Online Training" id="training" /> Add For Online Training</label></div></div>
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="training_offline" value="Registered For Offline Training" id="training" /> Add For Offline Training</label></div></div>
                                                                            </div>
                                                                            <hr/>
                                                                            <p><strong>Bonus Accounts</strong></p>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="open_acct_30" value="Sent Link To Open 30% No Deposit Bonus Account" id="open_acct"/>30% No Deposit Bonus Account</label></div></div>
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="open_acct_55" value="Sent Link To Open 55% No Deposit Bonus Account" id="open_acct"/>55% No Deposit Bonus Account</label></div></div>
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="open_acct_20" value="Sent Link To Open $20 Welcome Bonus Account" id="open_acct"/>$20 Welcome Bonus Account</label></div></div>
                                                                                <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="open_acct_edu" value="Sent Link To Open Educational Bonus Account" id="open_acct"/>Educational Bonus Account</label></div></div>
                                                                            </div>
                                                                            <hr/>
                                                                            <p><strong>ILPR Account</strong></p>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-4"><div class="checkbox"><label for=""><input type="checkbox" name="open_acct_ilpr" value="Sent Link To Open ILPR Account" id="open_acct" /> Open ILPR Account</label></div></div>
                                                                            </div>
                                                                            <hr/>
                                                                            <br/>
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-12">
                                                                                    <textarea name="comment" placeholder="Other Comments (if any)" id="comment" rows="3" class="form-control"></textarea>
                                                                                    <br>
                                                                                    <input name="process_successfull" type="submit" class="btn btn-success" value="Proceed">
                                                                                </div>
                                                                            </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
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