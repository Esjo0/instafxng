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
            $silver = $db_handle->numOfRows($query);
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
            $bronze = $db_handle->numOfRows($query);
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
            $message_success = "You Have Opted in earlier for the " . black_friday_tire($tire) . " category Kindly Check Your Progress <a href='black_friday_splurge_view.php'>Here</a>. ";
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
        $query = "UPDATE black_friday_2018 SET tire = '$terms' WHERE user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        if ($result) {
            $cookie_name = "ifxng_black_friday";
            $cookie_value = $user_code;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
            $sign_up = false;
            $opt_in = false;
            $message_success = "You have sucessfully opted in for The spluge. Check Your Progress <a href='black_friday_splurge_view.php'>Here</a>";
        } else {
            $messsage_error = "Kindly try again.";
        }
    }
}

// Get all participants
$query = "SELECT u.first_name, u.last_name
    FROM black_friday_2018 AS bf
    INNER JOIN user AS u ON bf.user_code = u.user_code
    WHERE bf.tire IS NOT NULL 
    ORDER BY bf.created ASC LIMIT 20";
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
    <title>Instaforex Nigeria | Independence Trade Contest</title>
    <meta name="title" content="Instaforex Nigeria | Independence Trade Contest"/>
    <meta name="keywords" content="instaforex, forex promo, forex promotion, instaforex nigeria."/>
    <meta name="description"
          content="Win $250 in our Independence Trade Contest, let's celebrate the Independence of Nigeria together."/>
    <?php require_once 'layouts/head_meta.php'; ?>
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
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
                            Blackest Friday Splurge is Here, Up to 150% Extra up for grap.</h1>
                        <p class="text-danger">Starting from November 12, 2018 To November 23, 2018.</p>
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
                                <h4><strong>Details</strong></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" id="details">
                                <p>To enjoy up to 150% extra this November it is necessary to fulfill the following
                                    conditions:</p>

                                <h5>How To Qualify For The Black Friday Splurge</h5>

                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 1:</strong>
                                        You need to have an InstaForex Account. Your account has to be enrolled into the
                                        InstaFxNg Loyalty Programs and Rewards (ILPR)
                                        <a href="live_account.php" target="_blank" title="Open A Live Trading Account">open
                                            a qualifying account</a> now.
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 2:</strong>
                                        Fill the contest registration form to participate in the contest.
                                        <a data-target="#contest-register" data-toggle="modal" style="cursor: pointer;">Click
                                            Here to Join the Contest.</a>
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 3:</strong>
                                        Trade with your account to start earning points. You can
                                        <a href="deposit.php" target="_blank" title="Fund your account in Naira">fund
                                            your account here</a>.
                                    </li>

                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 4:</strong>
                                        Earn a minimum of 25 points and also trade to be the highest point earner.
                                    </li>
                                </ul>
                                <p>The contest will hold from November 12, 2018 To November 23, 2018.</p>
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
                                                                       href="#collapse1">How The Winners are Chosen</a>
                                            </h5>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You become a winner as soon as you reach your target</p>

                                                <p> Platinum - 2000 Loyalty Points <br>
                                                    Gold - 1000 Loyalty Points <br>
                                                    Silver - 500 Loyalty Points <br>
                                                    Bronze 1 - 200 Loyalty Points <br>
                                                    Bronze 2 - 100 Loyalty Points <br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion"
                                                                       href="#collapse2">Black Friday Rules</a></h5>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>All traders participating in this contest must meet the following
                                                    rules to win.</p>
                                                <ul>
                                                    <li>Only ILPR enrolled accounts are qualified for this contest. <a
                                                            href="live_account.php" target="_blank"
                                                            title="Open A Live Trading Account">Open a qualifying
                                                            account.</a></li>
                                                    <li>You earn points when you trade during the contest.</li>
                                                    <li>Participant must accrue a minimum of 5 points daily and 25
                                                        points weekly to qualify to win.
                                                    </li>
                                                    <li>The rank of all qualified participants will be displayed on a
                                                        daily basis.
                                                    </li>
                                                    <li>The points earned by a participant in a day is automatically
                                                        generated by our system and added up before 10am next day.
                                                    </li>
                                                    <li>Contest ends by 11:59pm on Friday 5th October, 2018. The top
                                                        three highest point winners, will get a prize of $120, $80 and
                                                        $50 respectively
                                                    </li>
                                                    <li>In a case where there is a tie for the highest point earner,
                                                        there would be a draw.
                                                    </li>
                                                    <li>Winners will be announced on Monday October 8, 2018.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-5">
                        <div class="col-sm-12 text-center"><a target="_blank" href="black_friday_splurge_view.php"
                                                              class="btn btn-primary btn-md"><b>View Your
                                    Progress</b></a></div>

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
                                            <p class="modal-title"><strong>Independence Trade Contest
                                                    Registration</strong></p></div>
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
                                                        <p class="text-center">Welcome <?php echo $first_name ?> Select
                                                            A suitable Target OR <span data-dismiss="modal"> Read <a
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