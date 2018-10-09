<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

if(isset($_POST['opt_in'])) {
    $acct_no = $db_handle->sanitizePost($_POST['acct_no']);
    $terms = $db_handle->sanitizePost($_POST['terms']);

    if ($terms == '1') {
        $query = "SELECT u.user_code
            FROM user_credential AS uc
            INNER JOIN user AS u ON uc.user_code = u.user_code
            LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code
            INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code
            LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code
            WHERE (uc.doc_status = '111') AND ui.ifx_acct_no = '$acct_no'";
        $result = $db_handle->runQuery($query);
        $details = $db_handle->fetchArray($result);

        if($details) {
            foreach ($details AS $row) {
                extract($row);
                $query = "INSERT IGNORE INTO independence_promo (user_code, ifx_acct_no) VALUE('$user_code', '$acct_no')";
                $result = $db_handle->runQuery($query);
            }
            $cookie_name = "ifxng_promo";
            $cookie_value = $acct_no;
            setcookie($cookie_name, $cookie_value, time() + (86400 * 10), "/"); // 86400 = 1 day
            $message_success = "You have successfully registered";
        } else {
            $message_error = "This account is not an ILPR account <a target='_blank' href='https://instafxng.com/live_account.php'> Click Here to Open ILPR account</a>";
        }
    } else {
        $message_error = "Registration failed. You did not accept terms. Please try again.";
    }
}

// Get all contest participants
$query = "SELECT u.first_name, u.last_name, ip.total_points
    FROM independence_promo AS ip
    INNER JOIN user AS u ON ip.user_code = u.user_code
    ORDER BY total_points DESC LIMIT 20";
$result = $db_handle->runQuery($query);
$contest_members = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Independence Trade Contest</title>
        <meta name="title" content="Instaforex Nigeria | Independence Trade Contest" />
        <meta name="keywords" content="instaforex, forex promo, forex promotion, instaforex nigeria." />
        <meta name="description" content="Win $250 in our Independence Trade Contest, let's celebrate the Independence of Nigeria together." />
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
                    <div  class="item super-shadow home-page-top-section">
                        <div class="row">

                            <div class="col-sm-12"><img class="img img-responsive" src="https://instafxng.com/imgsource/independence_contest_winners.png" alt="" /></div>
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
                                <h1>Independence Trade Contest</h1>
                                <p>
                                    Have you got all it takes to be our Independence hero?
                                    Here's your chance to prove you are a pro-trader! It's time to claim your Title and show that you
                                    are a Forex trade Champion.
                                </p>
                                <a data-target="#contest-register" data-toggle="modal" class="btn btn-success" style="cursor: pointer;"><strong>Click Here to Join the Contest.</strong></a>
                                <br />
                                <hr />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-7">
                                <div class="row">
                                    <div class="col-sm-12 text-danger">
                                        <h4><strong>Contest Summary</strong></h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>To emerge one of our Independence Heroes and top three winners of $120, $80 and $50 respectively, it is necessary to fulfill the following conditions:</p>

                                        <h5>How To Qualify For The Independence Trade Contest</h5>

                                        <ul class="fa-ul">
                                            <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 1:</strong>
                                                You need to have an InstaForex Account. Your account has to be enrolled into the
                                                InstaFxNg Loyalty Programs and Rewards (ILPR)
                                                <a href="live_account.php" target="_blank" title="Open A Live Trading Account">open
                                                    a qualifying account</a> now.
                                            </li>

                                            <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 2:</strong>
                                                Fill the contest registration form to participate in the contest.
                                                <a data-target="#contest-register" data-toggle="modal" style="cursor: pointer;">Click Here to Join the Contest.</a>
                                            </li>

                                            <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 3:</strong>
                                                Trade with your account to start earning points. You can
                                                <a href="deposit.php" target="_blank" title="Fund your account in Naira">fund your account here</a>.
                                            </li>

                                            <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 4:</strong>
                                                Earn a minimum of 25 points and also trade to be the highest point earner.
                                            </li>
                                        </ul>
                                        <p>The contest will hold from October 1, 2018 to October 5, 2018. Thus, on Monday October 8, 2018,
                                            three heros will emerge to share the $250.</p>
                                        <p style="text-align: center"><a data-target="#contest-register" data-toggle="modal" class="btn btn-success" style="cursor: pointer;"><b>Join the Contest</b></a></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel-group" id="accordion">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">How The Winners are Chosen</a></h5>
                                                </div>
                                                <div id="collapse1" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <p>The top three traders with the highest number of loyalty points will get the prize of $250 as displayed on the contest table.</p>

                                                        <p>1st Prize = $120<br>
                                                        2nd Prize = $80<br>
                                                        3rd Prize = $50</p>

                                                       <p> Winners will be chosen at the end of the week.</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Rules Of The Independence Trade Contest</a></h5>
                                                </div>
                                                <div id="collapse2" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <p>All traders participating in this contest must meet the following rules to win.</p>
                                                        <ul>
                                                            <li>Only ILPR enrolled accounts are qualified for this contest. <a href="live_account.php" target="_blank" title="Open A Live Trading Account">Open a qualifying account.</a></li>
                                                            <li>You earn points when you trade during the contest.</li>
                                                            <li>Participant must accrue a minimum of 5 points daily and 25 points weekly to qualify to win.</li>
                                                            <li>The rank of all qualified participants will be displayed on a daily basis.</li>
                                                            <li>The points earned by a participant in a day is automatically generated by our system and added up before 10am next day.</li>
                                                            <li>Contest ends by 11:59pm on Friday 5th October, 2018. The top three highest point winners, will get a prize of $120, $80 and $50 respectively</li>
                                                            <li>In a case where there is a tie for the highest point earner, there would be a draw.</li>
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
                                <h5>Top 20 Contest Participants</h5>

                                <div class="row">
                                    <div class="col-sm-12" style="max-height: 600px; overflow: scroll;">

                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Points</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($contest_members) && !empty($contest_members)) { foreach ($contest_members as $row) { ?>
                                                <tr>
                                                    <td>
                                                        <?php if(!empty($row['first_name'])) {
                                                            echo $row['first_name'];
                                                        } else {
                                                            echo $row['last_name'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['total_points']; ?></td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='2' class='text-danger'><em>No participant yet.</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <!--Modal - confirmation boxes-->
                                <form data-toggle="validator"  class="form-horizontal" role="form" method="post" action="">
                                    <div id="contest-register" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                                                    <p class="modal-title"><strong>Independence Trade Contest Registration</strong></p></div>
                                                <div class="modal-body">
                                                    <?php include 'layouts/feedback_message.php'; ?>
                                                    <p>Enter Your INSTAFOREX ILPR account number.</p>
                                                    <div class="form-group">

                                                        <div class="col-sm-12 col-lg-12">
                                                            <input maxlength="10" value="" placeholder="Instaforex Account Number" name="acct_no" type="text" class="form-control" required>
                                                            <p class="text-center">All traders participating in this contest must meet the following T&Cs to win.</p>
                                                            <ul>
                                                                <li>Only ILPR enrolled accounts are qualified for this contest. <a href="live_account.php" target="_blank" title="Open A Live Trading Account">Open a qualifying account.</a></li>
                                                                <li>You earn points when you trade during the contest.</li>
                                                                <li>Participant must accrue a minimum of 5 points daily and 25 points weekly to qualify to win.</li>
                                                                <li>The rank of all qualified participants will be displayed on a daily basis.</li>
                                                                <li>The points earned by a participant in a day is automatically generated by our system and added up before 10am next day.</li>
                                                                <li>Contest ends by 11:59pm on Friday 5th October, 2018. The top three highest point winners, will get a prize of $120, $80 and $50 respectively</li>
                                                                <li>In a case where there is a tie for the highest point earner, there would be a draw.</li>
                                                                <li>Winners will be announced on Monday October 8, 2018.</li>
                                                            </ul>

                                                            <input type="checkbox" name="terms" value="1" required> <b>Tick to <em>Agree</em> With Terms $ Condition</b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input name="opt_in" type="submit" class="btn btn-success" value="Join Contest Now">
                                                    <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
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
        <?php if (!isset($_COOKIE['ifxng_promo'])) { ?>
        <script>
            <?php if(!$message_success) { ?>
            $(document).ready(function () {
                $('##contest-register').modal("show");
            });
            <?php } ?>
        </script>
    <?php }?>
    </body>
</html>