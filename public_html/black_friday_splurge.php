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

    $query = "SELECT u.user_code
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
        $message_error = "This account is not an ILPR account <a target='_blank' href='https://instafxng.com/live_account.php'> Click Here to Open ILPR account</a>";
    }
}

//flag the tire user can see
if (!empty($user_code_encrypted) || $check_acct = true) {
    //since GET values are set, we will confirm if its a true refund transaction
    $query = "SELECT * FROM black_friday_2018 WHERE user_code = '$user_code' AND tire IS NULL ";
    $num_rows = $db_handle->numRows($query);
    if ($num_rows == 1) {
        $opt_in = true;
        //platinum
        $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone
FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
INNER JOIN user AS u ON ui.user_code = u.user_code
INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
INNER JOIN admin AS a ON ao.admin_code = a.admin_code
WHERE date_earned BETWEEN '2017-12-01' AND '2018-10-30' AND u.user_code = '$user_code'
GROUP BY u.user_code
HAVING total_commission >= 1000 ";
        $platinum = $db_handle->numRows($query);
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        foreach ($result AS $row) {
            extract($row);
        }
        if ($platinum == 1) {
            $platinum = true;
        } elseif ($platinum == 0) {
            //gold
            $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone
FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
INNER JOIN user AS u ON ui.user_code = u.user_code
INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
INNER JOIN admin AS a ON ao.admin_code = a.admin_code
WHERE date_earned BETWEEN '2017-12-01' AND '2018-10-30' AND u.user_code = '$user_code'
GROUP BY u.user_code
HAVING total_commission BETWEEN 500 AND 999 ";;
            $gold = $db_handle->numRows($query);
            $result = $db_handle->runQuery($query);
            $result = $db_handle->fetchAssoc($result);
            foreach ($result AS $row) {
                extract($row);
            }
        }
        if ($gold == 1) {
            $platinum = true;
            $gold = true;
        } elseif ($gold == 0) {
            //silver
            $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone
FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
INNER JOIN user AS u ON ui.user_code = u.user_code
INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
INNER JOIN admin AS a ON ao.admin_code = a.admin_code
WHERE date_earned BETWEEN '2017-12-01' AND '2018-10-30' AND u.user_code = '$user_code'
GROUP BY u.user_code
HAVING total_commission BETWEEN 300 AND 499 ";
            $silver = $db_handle->numRows($query);
            $result = $db_handle->runQuery($query);
            $result = $db_handle->fetchAssoc($result);
            foreach ($result AS $row) {
                extract($row);
            }
        }
        if ($silver == 1) {
            $gold = true;
            $silver = true;
        } elseif ($silver == 0) {
            //bronze
            $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone
FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no
INNER JOIN user AS u ON ui.user_code = u.user_code
INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
INNER JOIN admin AS a ON ao.admin_code = a.admin_code
WHERE date_earned BETWEEN '2017-12-01' AND '2018-10-30' AND u.user_code = '$user_code'
GROUP BY u.user_code
HAVING total_commission BETWEEN 0 AND 300 ";
            $bronze = $db_handle->numRows($query);
            $result = $db_handle->runQuery($query);
            $result = $db_handle->fetchAssoc($result);
            foreach ($result AS $row) {
                extract($row);
            }
        }
        if ($bronze == 1) {
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
if (isset($_POST['progress'])) {
    $email = $db_handle->sanitizePost($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT bf.total_points, CONCAT(u.last_name, SPACE(1), u.first_name) AS name, bf.tire
            FROM user AS u
            INNER JOIN black_friday_2018 AS bf ON u.user_code = bf.user_code
            WHERE u.email = '$email' AND bf.tire IS NOT NULL";
        $result = $db_handle->runQuery($query);
        $details = $db_handle->fetchAssoc($result);


        if ($details) {
            foreach ($details AS $row) {
                extract($row);
                if (empty($total_points)) {
                    $total_points = 0;
                }
                $points_to_target = black_friday_tire_target($tire) - ($total_points % black_friday_tire_target($tire));
                $target_reached = round($total_points / black_friday_tire_target($tire), 0, PHP_ROUND_HALF_DOWN);
            }
        } else {
            $message_error = "You are not enrolled for the black friday Splurge <a data-target=\"#contest-register\" data-toggle=\"modal\"> Click Here to Join</a>";
        }
    } else {
        $message_error = "Looks like you entered an invalid email, please try again.";
    }
}

// Get all participants
$query = "SELECT u.first_name, u.last_name
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire IS NOT NULL 
    ORDER BY bf.created ASC LIMIT 10";
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
    <meta name="title" content="Instaforex Nigeria | Independence Trade Contest"/>
    <meta name="keywords" content="instaforex, forex promo, forex promotion, instaforex nigeria."/>
    <meta name="description"
          content="Win $250 in our Independence Trade Contest, let's celebrate the Independence of Nigeria together."/>
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>

<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>

        <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3 ">

            <!-- Unique Page Content Starts Here
            ================================================== -->
            <div class="item super-shadow home-page-top-section">
                <div class="row">
                    <div class="col-md-12">
                        <img data-target="#contest-register" data-toggle="modal" id="img_div_2" width="100%"
                             class="img img-responsive" src="https://instafxng.com/imgsource/the_splurge_website.jpg"
                             alt="The Splurge"
                             style="height:300px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)">
                    </div>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <?php include 'layouts/feedback_message.php'; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h1 style="font-family: 'Oleo Script', cursive !important; color: #000000 !important">The
                            Blackest Friday Splurge is Here, Up to 150% Extra up for Grabs.</h1>
                        <p class="text-danger">Starting from November 1, 2018 To November 30, 2018.</p>
                        <a data-target="#contest-register" data-toggle="modal" class="btn btn-success"
                           style="cursor: pointer;"><strong>Click Here to Join the Splurge.</strong></a>
                        <br/>
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-7">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Are you ready for the Splurge?</strong></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="details">
                                <p>It’s the season of splurge and you can get over $1000 extra in this Black Friday Promo.</p>

                                <p>No contest! No battle! Just set your target, hit it and get 150% of the loyalty points accrued during the promo.</p>

                                <p>You get your reward every time you hit your target</p>

                                <h5>How To Qualify For The Black Friday Splurge</h5>

                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 1:</strong>
                                        To participate, you need to have an InstaForex Account enrolled into the InstaFxNg Loyalty Programs and Rewards (ILPR).
                                        <a href="live_account.php" target="_blank" title="Open A Live Trading Account">open
                                            a qualifying account</a> now.
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 2:</strong>
                                        Fill the promo form to participate in the Splurge.
                                        <a data-target="#contest-register" data-toggle="modal" style="cursor: pointer;">Click Here to Join Now.</a>
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 3:</strong>
                                        Select your desired target and enter the promo. You will be required to hit your target within the duration of the promo.
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 4:</strong>
                                        You would be able to redeem your prize every time you hit your set target while the promo is on.
                                    </li>
                                </ul>
                                <p>So not to worry, you do not have to wait for the promo to end before cashing out!</p>
                                <p style="text-align: center"><a data-target="#contest-register" data-toggle="modal"
                                                                 class="btn btn-success" style="cursor: pointer;"><b>Join
                                            Now</b></a></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion"
                                                                       href="#collapse1">Rules of the Black Friday– The Splurge</a>
                                            </h5>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>All traders participating in this promo must meet the following rules to win.</p>
                                                <p>Only ILPR enrolled accounts are qualified for this contest. Open a qualifying account.
                                                    You earn points when you deposit and trade during the contest.
                                                    Participant must select a target and hit the target within the
                                                    duration of the promo to get 150% of the dollar equivalent of the loyalty points.
                                                </p>
                                                <p>E.g. If you select  a target of 3000 points for the Splurge promo and you work smart to hit this target,
                                                    You will be redeeming a whopping $750 into your InstaForex account instead of the regular $300.
                                                </p>
                                                <ul>
                                                    <li>Participant would get rewarded every time he/she hits the set target before the promo ends, so the more times you hit your target the more money extra money you earn!</li>
                                                    <li>Participants will be able to see his total points earned within the promo period right here on this page.</li>
                                                    <li>The points earned by a participant in a day is automatically generated by our system and added up before 10am next day.</li>
                                                    <li>Contest starts on Thursday 1st November 2018 and ends by 11:59pm on Friday 30th  November, 2018.</li>
                                                    <li>Participants are allowed to withdraw points accrued during the contest, but this is not compulsory as the points are also valid for increasing the yearly loyalty reward race worth N2.2 million.</li>
                                                    <li>Prizes won are monetary and cannot be converted into loyalty points, during and after the promo season.</li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
<h5 class="text-center"> Need more clarification on any of the rule? <a href="https://instafxng.com/contact_info.php">Click here to ask!</a></h5>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-5">
                        <div class="col-sm-12 text-center" ><button type="button" class="btn btn-disabled btn-danger"><b>View Your
                                    Progress Here</b></button></div>
                        <div class="row" style="border-radius: 10px; box-shadow: 0 4px 8px 0 rgba(255, 240, 249, 0.75), 0 6px 20px 0 rgba(230, 225, 221, 0.83)">
                            <div class="col-sm-12">
                                <h3 class="text-center" style="font-family: 'Oleo Script', cursive !important; !important">
                                    Enter your Email Address to know how you have fared in The Blackest Friday
                                    Splurge</h3>
                                <hr/>
                                <form data-toggle="validator" class="form-horizontal text-center" role="form" method="post"
                                      action="">
                                    <div class="form-group col-sm-9">
                                        <div class="">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-envelope fa-fw"></i></span>
                                                <input name="email" type="text" id=""
                                                       placeholder="Enter Your Email address" value=""
                                                       class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="">
                                            <input name="progress" type="submit" class="btn btn-success" value="SUBMIT"/>
                                        </div>
                                    </div>
                                </form>
                                <?php if ($details) { ?>
                                    <div class="row">
                                        <div class="col-sm-12"><p style="color:black; !important;"
                                                                  class="text-center"><?php echo $name ?> , You are in
                                                the <?php echo black_friday_tire($tire) ?> Category With a target
                                                of <?php echo black_friday_tire_target($tire); ?> loyality points</p>
                                        </div>
                                        <div class="col-sm-4">
                                            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                                                style="display:block">
                                                <h6><b>Total Points Gained</b></h6>
                                                <h5><?php echo $total_points ?> Points</h5>
                                            </li>
                                        </div>
                                        <div class="col-sm-4">
                                            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                                                style="display:block">
                                                <h6><b>Total Points to Target</b></h6>
                                                <h5><?php echo $points_to_target ?> Points</h5>
                                            </li>
                                        </div>

                                        <div class="col-sm-4">
                                            <li class="list-group-item d-flex justify-content-between lh-condensed text-center"
                                                style="display:block">
                                                <h6><b>Target Reached</b></h6>
                                                <h5><?php if ($target_reached > 1) {
                                                        echo $target_reached . " Times";
                                                    } elseif ($target_reached == 1) {
                                                        echo "Once";
                                                    } else {
                                                        echo "Not Yet.";
                                                    } ?></h5>
                                            </li>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
<br>
                        <h5>Total Number of Participant : <?php echo $numrows; ?></h5>

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
                                            <p class="modal-title"><strong>Black Friday - Registration</strong></p></div>
                                        <div class="modal-body">
                                            <?php include 'layouts/feedback_message.php'; ?>

                                            <div class="form-group">

                                                <div class="col-sm-12 col-lg-12">
                                                    <?php if ($sign_up == true) { ?>
                                                        <p>Enter Your INSTAFOREX ILPR account number.</p>
                                                        <input maxlength="10" value=""
                                                               placeholder="Instaforex Account Number" name="acct_no"
                                                               type="text" class="form-control" required>
                                                    <?php } ?>
                                                    <?php if ($opt_in == true) { ?>
                                                        <p class="text-center">Welcome <?php echo $first_name ?>, Select
                                                            a suitable target OR <span data-dismiss="modal"> Read <a
                                                                    href="">more</a> details</span></p>
                                                        <div class="text-center well">
                                                            <?php if ($platinum == true) { ?>
                                                                <button class="btn btn-green btn-lg"
                                                                        style="background-color: gainsboro;">PLATINUM -
                                                                    TARGET(2000 loyalty points)
                                                                </button><label><input type="radio" name="tire"
                                                                                       value="1" required>
                                                                    <b></b></label><br>
                                                                <hr>
                                                            <?php } ?>
                                                            <?php if ($gold == true) { ?>
                                                                <button class="btn btn-warning btn-lg"
                                                                        style="background-color: gold;">GOLD --------
                                                                    TARGET(1000 loyalty points)
                                                                </button><label><input type="radio" name="tire"
                                                                                       value="2" required>
                                                                    <b></b></label><br>
                                                                <hr>
                                                            <?php } ?>
                                                            <?php if ($silver == true) { ?>
                                                                <button class="btn btn-default btn-lg"
                                                                        style="background-color: silver;">SILVER -------
                                                                    TARGET(500 loyalty points)
                                                                </button><label><input type="radio" name="tire"
                                                                                       value="3" required>
                                                                    <b></b></label><br>
                                                                <hr>
                                                            <?php } ?>
                                                            <?php if ($bronze == true) { ?>
                                                                <button class="btn btn-danger btn-lg"
                                                                        style="background-color: saddlebrown;">BRONZE -
                                                                    1 - TARGET(200 loyalty points)
                                                                </button><label><input type="radio" name="tire"
                                                                                       value="4" required>
                                                                    <b></b></label><br>
                                                                <hr>
                                                            <?php } ?>
                                                            <?php if ($bronze == true) { ?>
                                                                <button class="btn btn-danger btn-lg"
                                                                        style="background-color: sienna;">BRONZE - 2 -
                                                                    TARGET(100 loyalty points)
                                                                </button><label><input type="radio" name="tire"
                                                                                       value="5" required>
                                                                    <b></b></label><br>
                                                                <hr>
                                                            <?php } ?>
                                                        </div>

                                                        <p class="text-center"><input type="checkbox" name="terms"
                                                                                      value="1" required> <b>Tick
                                                                to <em>Agree</em> With Terms $ Condition</b></p>
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
<?php if (!isset($_COOKIE['ifxng_black_friday'])) { ?>
    <script>
        $(document).ready(function () {
            $('#contest-register').modal("show");
        });
    </script>
<?php } ?>
</html>