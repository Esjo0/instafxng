<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";
$get_params = allowed_get_params(['x']);
$user_code_encrypted = $get_params['x'];
$user_code = decrypt(str_replace(" ", "+", $user_code_encrypted));
$user_code = preg_replace("/[^A-Za-z0-9 ]/", '', $user_code);

// checks User account type, log in the black friday table and user get details.
if (isset($_POST['check_acct'])) {
    $acct_no = $db_handle->sanitizePost($_POST['acct_no']);

    $query = "SELECT u.user_code, u.first_name, u.last_name
            FROM user_credential AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            WHERE ui.ifx_acct_no = '$acct_no' AND ui.type = '1'";
    $result = $db_handle->runQuery($query);
    $details = $db_handle->fetchArray($result);

    if ($details) {
        foreach ($details AS $row) {
            extract($row);
            $query = "INSERT IGNORE INTO black_friday_2018 (user_code) VALUE ('$user_code')";
            $result = $db_handle->runQuery($query);
            $check_acct = true;
        }
    } else {
        $message_error = "This account is not an ILPR account <a target='_blank' href='https://instafxng.com/live_account.php'> Click Here to Open an ILPR account</a>";
    }
}

//flag the tire user can see
if (!empty($user_code_encrypted) || $check_acct = true) {

    $query = "SELECT * FROM black_friday_2018 WHERE user_code = '$user_code' AND tire IS NULL ";
    $num_rows = $db_handle->numRows($query);

    if ($num_rows == 1) {
        $opt_in = true;

        // Get client's total_comission in other to determine the tier
        $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.last_name, u.email, u.phone
            FROM trading_commission AS td
            INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
            INNER JOIN user AS u ON ui.user_code = u.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            WHERE date_earned BETWEEN '2017-12-01' AND '2018-10-30' AND u.user_code = '$user_code'
            GROUP BY u.user_code ";

        $result = $db_handle->runQuery($query);
        $selected_data = $db_handle->fetchAssoc($result)[0];

        extract($selected_data);

        if ($total_commission >= 1000) {
            $platinum = true;
            $gold = true;
        } elseif ($total_commission >= 500 AND $total_commission <= 999) {
            $platinum = true;
            $gold = true;
        } elseif ($total_commission >= 300 AND $total_commission <= 499) {
            $platinum = true;
            $gold = true;
            $silver = true;
        } elseif ($total_commission <= 299) {
            $platinum = true;
            $gold = true;
            $silver = true;
            $bronze = true;
        }
    } else {

        $query = "SELECT tire FROM black_friday_2018 WHERE user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchArray($result);

        foreach ($result AS $row) {
            extract($row);
        }

        if (!empty($tire)) {
            $message_success = "You Have Opted in earlier for the " . black_friday_tire($tire) . " category Kindly Check Your Progress.";
        } else {
            $sign_up = true;
        }
    }
} else {
    $sign_up = true;
}

//Update tire set user cookie
if (isset($_POST['opt_in'])) {

    $tire = $db_handle->sanitizePost($_POST['tire']);
    $terms = $db_handle->sanitizePost($_POST['terms']);
    $user_code = $db_handle->sanitizePost($_POST['user']);

    if ($terms == 1) {
        $query = "UPDATE black_friday_2018 SET tire = '$tire' WHERE user_code = '$user_code'";
        $result = $db_handle->runQuery($query);

        if ($result) {
            $cookie_name = "ifxng_black_friday";
            $cookie_value = $user_code;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
            $sign_up = false;
            $opt_in = false;
            $message_success = "You have sucessfully opted in for The spluge. Check Your Progress.";
        } else {
            $messsage_error = "Kindly try again.";
        }
    }
}
$query = "SELECT u.first_name, u.last_name
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire IS NOT NULL
    ORDER BY bf.created";

$total_participants = $db_handle->numRows($query);

// Get all participants
$query = "SELECT u.first_name, u.last_name
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire IS NOT NULL 
    ORDER BY bf.created DESC LIMIT 10";

$result = $db_handle->runQuery($query);
$numrows = $db_handle->numOfRows($result);
$contest_members = $db_handle->fetchAssoc($result);

$i = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Black Friday - The Splurge</title>
    <meta name="title" content="Instaforex Nigeria | Black Friday - The Splurge"/>
    <meta name="keywords"
          content="instaforex, forex promo, forex promotion, instaforex nigeria, instafxng black friday promo."/>
    <meta name="description"
          content="The Blackest Friday Splurge Is Here, Up To 150% Extra Up For Grabs. No contest! No battle! Just set your target, hit it and get 150% of the loyalty points accrued during the promo."/>
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<script>
    function progress(email) {
        if (email == "") {
            document.getElementById("progress").innerHTML = "";
            return;
        }
        console.log(email);
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("progress").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "logic/calculate_black_friday_progress.php?email=" + email, true);
        xmlhttp.send();
    }

</script>
<body>
<?php require_once 'layouts/header.php'; ?>

<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>

        <div id="main-body-content-area" class="col-md-7 col-md-push-5 col-lg-8 col-lg-push-4">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="item super-shadow home-page-top-section">
                <div class="row">
                    <div class="col-md-12">
                        <img data-target="#contest-register" data-toggle="modal" id="img_div_2" width="100%"
                             class="img img-responsive" src="https://instafxng.com/imgsource/the_splurge_website.jpg"
                             alt="The Splurge"
                             style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">
                    </div>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include 'layouts/feedback_message.php'; ?>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-sm-12 text-center">
                        <h3 style=" color: #000000 !important">The
                            Blackest Friday Splurge is Here, Up to 150% Extra up for Grabs.</h3>
                        <p class="text-danger">First Round <i class="fa fa-arrow-right"></i> Starts on Monday 5th November, 2018 To Friday 16th November, 2018.</p>
                        <a data-target="#contest-register" data-toggle="modal" class="btn btn-success"
                           style="cursor: pointer;"><strong>Click Here to Join the Splurge.</strong></a>
                        <br/>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Your Map For The Splurge Season!</strong></h4>
                            </div>
                                <div class="col-sm-12" id="details">
                                    <p>This year’s black Friday promo is themed the splurge, as we intend to reward you with more money this promo period!</p>
                                    <p>What is the highest points you have earned in the loyalty reward promo so far, can you beat your previous points? Yes! We are set to reward you every step of the way.</p>
                                    <p>The promo is divided into 2 rounds, the first round begins on the 5th of November and ends by 11:59pm on the 16th of November, while the second round begins by 12am on the 19th of November and ends by 11:59pm on the 30th of November 2018.</p>
                                    <p>To participate in this promo, we have created different categories with targets for you. You only need to choose from the set targets and work towards hitting and surpassing it. </p>
                                    <p>The more time you hit your target the more rewards you get.</p>
                                    <p>For instance, if your target is to get 1000 points in the first round, once you get to 1000 points you will be rewarded with $150 in addition to the 1000 points you have. 1000 points equals $100 so the total becomes $250.</p>
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <strong><h5 class="text-center"><u>The Breakdown</u></h5></strong>
                                        <p class="text-center">
                                            Trade 1000 points to get $150<br>
                                        The same 1000 points equals $100.<br>
                                        Total Reward - $100 + $150 = $250</p>
                                            </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                    <p>From the example above, if you choose to redeem your win and the points accrued for the promo season, you will be receiving a total of $250 extra cash!</p>

                                    <p>However, you can also choose to claim only your win of $150 and keep the 1000 points which will help you rank higher in the yearly reward programme and position you to secure your share of the yearly N2.250 Million pool by 30th November, 2018.
                                    <p>Whatever your choice is, you remain a winner!</p>
                                    <p><strong>How To Earn Points.</strong> For the purpose of this promo, both your funding and trading activity will earn you loyalty points, which will be published by 10am the following day.</p>
                                    <p><strong>No Limitations! You Can Grab Over $1000 Extra in the Splurge.</strong> There is no limit to how much you can earn in every round because every time you hit your set target, you’ll get an extra 150%. </p>
                                    <p>This means if you hit your target 2 times or more in a round, you will get 150% extra twice or more. So brace up!</p>
                                    <p><strong>Important Notice.</strong> Only ILPR enrolled accounts are qualified for this promo. <a target="_blank" href="http://bit.ly/2mpqehQ">Fill in step one and two form here to open a qualifying account now.</a></p>
                                    <p>We are ready to splurge, are you ready to earn? Click here to join the promo now.</p>
                                    <p style="text-align: center"><a data-target="#contest-register" data-toggle="modal"
                                                                     class="btn btn-success"
                                                                     style="cursor: pointer;"><b>Join Now</b></a></p>
                                </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel-group" id="accordion2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title"><a data-toggle="collapse"
                                                                           data-parent="#accordion2" href="#collapse2">How To Qualify For The Black Friday Splurge</a>
                                                </h5>
                                            </div>

                                            <div id="collapse2" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <ul class="fa-ul">
                                                        <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 1:</strong>
                                                            To participate, you need to have an InstaForex Account enrolled into the
                                                            InstaFxNg Loyalty Programs and Rewards (ILPR).
                                                            <a href="live_account.php" target="_blank"
                                                               title="Open A Live Trading Account">open a qualifying account</a> now.
                                                        </li>

                                                        <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 2:</strong>
                                                            Fill the promo form to participate in the Splurge.
                                                            <a data-target="#contest-register" data-toggle="modal"
                                                               style="cursor: pointer;">Click Here to Join Now.</a>
                                                        </li>

                                                        <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 3:</strong>
                                                            Choose your desired target and enter the promo. You will be required to hit your target within each round of the promo.
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-group" id="accordion1">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h5 class="panel-title"><a data-toggle="collapse"
                                                                           data-parent="#accordion1" href="#collapse1">Rules
                                                        of the Black Friday – The Splurge</a>
                                                </h5>
                                            </div>

                                            <div id="collapse1" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>All traders participating in this promo must meet the following
                                                        rules to win.</p>
                                                    <p>Only ILPR enrolled accounts are qualified for this contest.
                                                        <a href="live_account.php" target="_blank"
                                                           title="Open A Live Trading Account"> Open
                                                        a qualifying account.</a>
                                                    </p>
                                                    <ul>
                                                        <li>Participant would get rewarded every time he/she hits the
                                                            set target before the promo ends, so the more you hit
                                                            your target the more money you earn!
                                                        </li>
                                                        <li>Participants will be able to see his/her total points earned
                                                            within the promo period right here on this page.
                                                        </li>
                                                        <li>The points earned by a participant in a day is automatically
                                                            generated by our system and added up before 10am next day.
                                                        </li>
                                                        <li>The first round of the contest starts on Monday 5th November 2018
                                                            and ends by 11:59pm on Friday 16th November, 2018.
                                                        </li>
                                                        <li>
                                                            The Second round of the Contest starts on Monday 19th November
                                                            and ends by 11:59pm on Friday 30th November, 2018.
                                                        </li>
                                                        <li>As Participants you are allowed to withdraw points accrued during
                                                            the contest, but this is not compulsory as the points are
                                                            also valid for increasing the yearly loyalty reward race
                                                            worth N2.250 million.
                                                        </li>
                                                        <li>Prizes won will be paid into your InstaForex Account and cannot be converted into loyalty
                                                            points, during and after the promo season.
                                                        </li>
                                                        <li>
                                                            Participation in the second round will not be automatic,
                                                            you will be required to Opt-In to round 2 upon the completion of round one.
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-center">
                                            <!-- livezilla.net PLACE WHERE YOU WANT TO SHOW GRAPHIC BUTTON -->
                                            <a href="javascript:void(window.open('https://instafxng.com/livechat/chat.php?v=2','','width=590,height=760,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))" class="lz_cbl"><img src="https://instafxng.com/livechat/image.php?id=4&type=inlay" width="210" height="66" style="border:0;" alt="LiveZilla Live Chat Software"></a>
                                            <!-- livezilla.net PLACE WHERE YOU WANT TO SHOW GRAPHIC BUTTON -->
                                        <br>
                                        <a target="_blank" href="https://instafxng.com/contact_info.php">
                                            Click here to contact us </a>if
                                        you need further clarification about the black friday promotion.

                                    </p>

                                </div>
                            </div>

                    </div>
                </div>

                <div class="row">
                        <div class="col-sm-12">
                            <!--Modal - confirmation boxes-->
                            <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                <div id="contest-register" tabindex="-1" role="dialog" aria-hidden="true"
                                     class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" data-dismiss="modal" aria-hidden="true"
                                                        class="close">&times;</button>
                                                <p class="modal-title"><strong>Black Friday - Registration</strong></p>
                                            </div>
                                            <div class="modal-body">
                                                <?php include 'layouts/feedback_message.php'; ?>

                                                <div class="form-group">

                                                    <div class="col-sm-12 col-lg-12">
                                                        <input type="hidden" name="user"
                                                               value="<?php echo $user_code ?>">
                                                        <?php if ($sign_up == true) { ?>
                                                            <p>Enter Your INSTAFOREX ILPR account number.</p>
                                                            <input maxlength="10" value=""
                                                                   placeholder="Instaforex Account Number"
                                                                   name="acct_no" type="text" class="form-control"
                                                                   required>
                                                        <?php } ?>

                                                        <?php if ($opt_in == true) { ?>
                                                            <p class="text-center">Welcome <?php echo $first_name; if(empty($first_name)){echo $last_name;} ?>,
                                                                Select a suitable target OR <span onClick="window.close();" data-dismiss="modal"> Read <a
                                                                        href="">more</a> details</span></p>
                                                            <div class="text-center well">
                                                                <?php if ($platinum == true) { ?>
                                                                    <button onclick="select_tire('1')"
                                                                            class="btn btn-green"
                                                                            style="background-color: rgba(158, 158, 158, 0.75); width:300px; margin-bottom: 8px;">
                                                                        PLATINUM - (2000 loyalty points)
                                                                    </button><label><b id="platinum"
                                                                                       style="display:none; background-color: #d7d7d7; border-radius:8px; color:green !important; box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)""><span class="glyphicon glyphicon-ok"></span></b></label>
                                                                    <br>
                                                                <?php } ?>

                                                                <?php if ($gold == true) { ?>
                                                                    <button onclick="select_tire('2')"
                                                                            class="btn btn-warning"
                                                                            style="background-color: gold; width:300px; margin-bottom: 8px;">GOLD - (1000
                                                                        loyalty points)
                                                                    </button><label><b id="gold"
                                                                                       style="display:none; background-color: #d7d7d7; border-radius:8px; color:green !important; box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)""><span class="glyphicon glyphicon-ok"></span></b>
                                                                        </label>
                                                                    <br>
                                                                <?php } ?>

                                                                <?php if ($silver == true) { ?>
                                                                    <button onclick="select_tire('3')"
                                                                            class="btn btn-default"
                                                                            style="background-color: #d7d7d7; width:300px; margin-bottom: 8px;">SILVER -
                                                                        (500 loyalty points)
                                                                    </button><label><b id="silver"
                                                                                       style="display:none; background-color: #d7d7d7; border-radius:8px; color:green !important; box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)""><span class="glyphicon glyphicon-ok"></span></b>
                                                                       </label>
                                                                    <br>
                                                                <?php } ?>
                                                                <?php if ($bronze == true) { ?>
                                                                    <button onclick="select_tire('4')"
                                                                            class="btn btn-danger"
                                                                            style="background-color: saddlebrown; width:300px; margin-bottom: 8px;">
                                                                        BRONZE PRO - (200 loyalty points)
                                                                    </button><label><b id="pro"
                                                                                       style="display:none; background-color: #d7d7d7; border-radius:8px; color:green !important; box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)""><span class="glyphicon glyphicon-ok"></span></b>
                                                                       </label>
                                                                    <br>
                                                                <?php } ?>
                                                                <?php if ($bronze == true) { ?>
                                                                    <button onclick="select_tire('5')"
                                                                            class="btn btn-danger"
                                                                            style="background-color: sienna; width:300px;">BRONZE
                                                                        LITE - (100 loyalty points)
                                                                    </button><label>
                                                                        <b id="lite"
                                                                           style="display:none; background-color: #d7d7d7; border-radius:8px; color:green !important; box-shadow: 0 4px 8px 0 rgb(0, 128, 0), 0 6px 20px 0 rgba(255, 11, 0, 0.83)""><span class="glyphicon glyphicon-ok"></span></b></label>
                                                                    <br>
                                                                <?php } ?>
                                                            </div>

                                                            <p class="text-center">
                                                            <ul>
                                                                <li>Only ILPR enrolled accounts are qualified for this
                                                                    contest.
                                                                </li>
                                                                <li>You earn points when you deposit and trade during
                                                                    the contest.
                                                                </li>
                                                                <li>Participant must select a target and hit the target
                                                                    within the duration of the promo to get 150% of the
                                                                    dollar equivalent of the loyalty points.
                                                                </li>
                                                                <li>Participant would get rewarded every time he/she
                                                                    hits the set target before the promo ends, so the
                                                                    more you hit your target the more money you earn!
                                                                </li>
                                                                <li>Participants will be able to see his/her total points
                                                                    earned within the promo period right on the page.
                                                                </li>
                                                                <li>The points earned by a participant in a day is
                                                                    automatically generated by our system and added
                                                                    before 10am the next day.
                                                                </li>
                                                                <li>Contest starts on Monday 5th November 2018 and
                                                                    ends by 11:59pm on Friday 16th November, 2018.
                                                                </li>
                                                                <li>
                                                                    Participation in the second round will not be automatic,
                                                                    you will be required to Opt-In to round 2 upon the completion of round one.
                                                                </li>
                                                            </ul>
                                                            <input id="tire" type="hidden" name="tire" value=""
                                                                   required>
                                                            <input type="checkbox" name="terms" value="1" required> <b>Tick
                                                                to <em>Agree</em> With Terms & Condition</b>
                                                            </p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <?php if ($opt_in == true) { ?>
                                                    <input onclick="contest_ended()" name="opt_in" type="submit"
                                                           class="btn btn-success" value="Opt in Now">
                                                <?php } ?>
                                                <?php if ($sign_up == true) { ?>
                                                    <input onclick="contest_ended()" name="check_acct" type="submit"
                                                           class="btn btn-success" value="SUBMIT">
                                                <?php } ?>
                                                <button type="button" name="close" onClick="window.close();"
                                                        data-dismiss="modal" class="btn btn-danger">Close!
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>

        <div id="main-body-side-bar" class="col-md-5 col-md-pull-7 col-lg-4 col-lg-pull-8 left-nav">
            <div class="col-sm-12 section-tint super-shadow nav-display super-shadow">

                <h5>Total Number of Participant : <?php echo $total_participants; ?></h5>
                <div class="row">
                    <div class="col-sm-12" style="max-height: 600px; overflow: scroll;">

                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($contest_members) && !empty($contest_members)) {
                                foreach ($contest_members as $row) {
                                    $i++; ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td>
                                            <?php if (!empty($row['first_name'])) {
                                                echo $row['first_name'];
                                            } else {
                                                echo $row['last_name'];
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='2' class='text-danger'><em>No participant yet.</em></td></tr>";
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div id="progress" class="col-sm-12 text-center">
                    <span style="background: #d95450; border-radius:5px; padding:10px; color:white"><b>View Your
                            Progress Here</b></span>
                </div>
                <div class="row"
                     style="border-radius: 10px; box-shadow: 0 4px 8px 0 rgba(255, 240, 249, 0.75), 0 6px 20px 0 rgba(230, 225, 221, 0.83)">
                    <div class="col-sm-12">
                        <p class="text-center" style="font-family: 'Times New Roman', cursive !important; !important">
                            Enter your Email Address to know how you have fared in The Blackest Friday
                            Splurge</p>
                        <hr/>
                        <div class="form-group col-sm-12">
                            <div class="">
                                <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-envelope fa-fw"></i></span>
                                    <input name="email" type="text" id="email"
                                           placeholder="Enter Your Email address"
                                           class="form-control" onchange="progress(this.value);"
                                           onfocus="progress(this.value);"
                                           onblur="progress(this.value);"/>

                                </div>
                                <span class="help-block text-center"><i class="fa fa-info-circle"></i> Press the enter key after typing</span>
                            </div>
                        </div>
                        <div id="progress"></div>
                    </div>
                </div>
<!--                <div class="section-tint super-shadow" style="background-color: black;border-radius:10px">-->
<!--                    <div class="row">-->
<!--                        <div class="col-sm-12 text-lg-center text-center" id="time-counter" style="color: white; font-size: 18px;">-->
<!--                            <span  id="day"></span>-->
<!--                            <span  id="hour"></span>-->
<!--                            <span  id="min"></span>-->
<!--                            <span  id="sec"></span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
<?php if ($opt_in == true) { ?>
    <script>
        $(document).ready(function () {
            $('#contest-register').modal("show");
        });
    </script>
<?php } ?>
<script>
    function select_tire(tire) {
        document.getElementById("tire").value = tire;
        if (tire == 1) {
            document.getElementById("platinum").style.display = "block";
            document.getElementById("gold").style.display = "none";
            document.getElementById("silver").style.display = "none";
            document.getElementById("pro").style.display = "none";
            document.getElementById("lite").style.display = "none";

        }
        else if (tire == 2) {
            document.getElementById("platinum").style.display = "none";
            document.getElementById("gold").style.display = "block";
            document.getElementById("silver").style.display = "none";
            document.getElementById("pro").style.display = "none";
            document.getElementById("lite").style.display = "none";
        }
        else if (tire == 3) {
            document.getElementById("platinum").style.display = "none";
            document.getElementById("gold").style.display = "none";
            document.getElementById("silver").style.display = "block";
            document.getElementById("pro").style.display = "none";
            document.getElementById("lite").style.display = "none";
        }
        else if (tire == 4) {
            document.getElementById("platinum").style.display = "none";
            document.getElementById("gold").style.display = "none";
            document.getElementById("silver").style.display = "none";
            document.getElementById("pro").style.display = "block";
            document.getElementById("lite").style.display = "none";
        }
        else if (tire == 5) {
            document.getElementById("platinum").style.display = "none";
            document.getElementById("gold").style.display = "none";
            document.getElementById("silver").style.display = "none";
            document.getElementById("pro").style.display = "none";
            document.getElementById("lite").style.display = "block";
        }
        else {
            document.getElementById("platinum").style.display = "none";
            document.getElementById("gold").style.display = "none";
            document.getElementById("silver").style.display = "none";
            document.getElementById("pro").style.display = "none";
            document.getElementById("lite").style.display = "none";
        }
    }
</script>
<!-- Time Counter -->
<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date('2018-11-05');
    countDownDate.setDate(countDownDate.getDate());

    // Update the count down every 1 second
    var x = setInterval(function () {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
//        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

//        document.getElementById("day").innerHTML = days + "Days  ";
        document.getElementById("hour").innerHTML = hours + "Hours  ";
        document.getElementById("min").innerHTML = minutes + "Mins  ";
        document.getElementById("sec").innerHTML = seconds + "Secs  ";

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("time-counter").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>


</html>