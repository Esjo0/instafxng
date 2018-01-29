<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

/** Display the top earners for this MONTH ***********/
$query = "SELECT start_date, end_date FROM point_season WHERE is_active = '1' AND type = '1' LIMIT 1";
$result = $db_handle->runQuery($query);
$current_point_season = $db_handle->fetchAssoc($result);

$from_date = $current_point_season[0]['start_date'];
$to_date = $current_point_season[0]['end_date'];

$query = "SELECT pr.month_rank, pr.month_earned_archive, pr.point_claimed, u.last_name, u.first_name AS full_name
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.month_rank DESC, full_name ASC LIMIT 20";

$result = $db_handle->runQuery($query);
$selected_loyalty = $db_handle->fetchAssoc($result);
/*****************************************************/

/** Display the top earners for this SEASON ***********/
$query = "SELECT start_date, end_date FROM point_season WHERE is_active = '1' AND type = '2' LIMIT 1";
$result = $db_handle->runQuery($query);
$current_point_season = $db_handle->fetchAssoc($result);

$from_date_year = $current_point_season[0]['start_date'];
$to_date_year = $current_point_season[0]['end_date'];

$query = "SELECT pr.year_rank, pr.year_earned_archive, pr.point_claimed, u.last_name, u.first_name AS full_name
      FROM point_ranking AS pr
      INNER JOIN user AS u ON pr.user_code = u.user_code
      ORDER BY pr.year_rank DESC, full_name ASC LIMIT 20";

$result = $db_handle->runQuery($query);
$selected_loyalty_year = $db_handle->fetchAssoc($result);
/*****************************************************/

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Point Based Loyalty Reward</title>
        <meta name="title" content="Instaforex Nigeria | Point Based Loyalty Reward" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script type="text/javascript">
            function calculatePoints(acct, type) {
                if (acct=="") {
                    document.getElementById("monthlyPoints").innerHTML="";
                    return;
                }
                if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                } else {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        document.getElementById("monthlyPoints").innerHTML=xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "logic/calculate_loyalty_point.php?acct="+acct+"&type="+type, true);
                xmlhttp.send();
            }

            function calculatePoints2(acct, type) {
                if (acct=="") {
                    document.getElementById("yearlyPoints").innerHTML="";
                    return;
                }
                if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                } else {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        document.getElementById("yearlyPoints").innerHTML=xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "logic/calculate_loyalty_point.php?acct="+acct+"&type="+type, true);
                xmlhttp.send();
            }
        </script>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-7 col-md-push-5 col-lg-8 col-lg-push-4">
                    
                    <!-- Unique Page Content Starts Here
                    ================================================== -->

                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-12">
                                <img style="" src="images/ilpr_landing_image.jpg" alt="" class="img-thumbnail img img-responsive" />
                            </div>
                            <div class="col-sm-12">
                                <h2>Instafxng Point Based Loyalty Program and Reward</h2>
                                <p>Make Up To $4, 200 and N1, 000, 000 Extra While You Take Your Normal Trades.</p>
                                <p><strong>Current Round:</strong><br />
                                    <strong>Start Date:</strong> <?php echo datetime_to_text2($from_date_year); ?><br />
                                    <strong>End Date:</strong> <?php echo datetime_to_text2($to_date_year); ?>
                                </p>
                            </div>

                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>What is thrilling is how much you can get without doing anything extra other
                                    than just trading as usual. You do not enter for a draw or trade a particular
                                    lot size to qualify "Just Trade!"</p>

                                <h4>What is it?</h4>
                                <p>Our new unified reward program which rewards every single one of our loyal clients daily, monthly and annually.</p>
                                <p>Several tens of thousands of dollars will be paid out to clients in rewards and redemptions during the one year duration of each round of the promo.</p>
                                <p>The exciting thing is that everyone is a winner. Every client gets a piece of the pie as long as you deposit funds to your account or you complete trades on any of your accounts held with us.</p>

                                <h4>How does it work?</h4>
                                <p>Each time you make a deposit to your account or trade as usual you earn some point’s equivalent to your deposit or trading actions. Our proprietary reward technology automatically calculates the points you have earned and your reward account is updated accordingly.</p>

                                <h4>When can I redeem points?</h4>
                                <ol>
                                    <li>You can only redeem your points to credit your instaforex account from 100 points and above.</li>
                                    <li>If you have a minimum of 100 points you will be presented with the option of redeeming some of
                                        your points for InstaForex credit to your account. For example if you are initiating a deposit
                                        transaction of $100 to your InstaForex account and you have 1000 reward points, you will be able
                                        to redeem up to 1000 points for another $100 hence your InstaForex account will be credited with
                                        a total of $200. Every client is eligible for this as long as you have earned the minimum 100
                                        reward points.</li>
                                    <li>Points can only be redeemed when depositing funds.</li>
                                </ol>

                                <h4>But that's not all...</h4>
                                <p>We will be rewarding $500 to five (5) clients with the highest number of loyalty points as seen from the
                                    monthly ranking for the month in view. The prizes are as follows</p>
                                <ul>
                                    <li>1st Prize = $150</li>
                                    <li>2nd Prize = $120</li>
                                    <li>3rd Prize = $100</li>
                                    <li>4th Prize = $80</li>
                                    <li>5th prize = $50</li>
                                </ul>
                                <p>In addition to the prizes, each winner gets a guaranteed invite to our annual lavish dinner which holds at the
                                    end of every year in December.</p>

                                <h4>We are not done yet...</h4>
                                <p>You still have N2,250,000 up for grabs. At the end of every annual cycle, the ten (10)
                                    clients with the highest number of reward points as seen from the cumulative annual
                                    ranking will be awarded prizes. An annual cycle runs from December 1 to November 30 of
                                    the following year. The prizes are as follows</p>
                                <ul>
                                    <li>1st Prize = N1,000,000</li>
                                    <li>2nd Prize = N500,000</li>
                                    <li>3rd Prize = N250,000</li>
                                    <li>4th Prize = N150,000</li>
                                    <li>5th Prize = N100,000</li>
                                    <li>6th – 10th Prize = N50,000 each</li>
                                </ul>
                                <p>Prizes will be presented to winners during our end of the year dinner in December</p>

                                <h4>HOW much can you earn from this rewards Program...?</h4>
                                <p>You can earn up to $4,200 and N1,000,000 every year. Below is a breakdown of how easy it is.</p>
                                <p>If your deposit and trading activities earn you an average of 2, 000 points every month making you
                                    the highest point earner every single month of the year, then you will earn the following.</p>
                                <ol>
                                    <li>As first prize monthly winner you will get $150 every month making a total of $1, 800 for one year.</li>
                                    <li>With 2,000 points monthly for 12 months you will have 24, 000 points and you can redeem that to get $2, 400.
                                        <br />$2,400 + $1,800 = $4,200
                                    </li>
                                    <li>As the first prize annual cumulative winner, you will get one million naira (N1,000,000).</li>
                                </ol>
                                <p>This is an awesome opportunity to make money in the New Year. Get set and take your positions
                                    immediately and make the points start counting.</p>

                                <p class="text-left"><a href="loyalty_archive.php" class="btn btn-success" title="Archived Results"><i class="fa fa-arrow-circle-right"></i> Archived Results</a></p>
                                <br /><hr />

                            </div>

                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Rules of the Instafxng Point Based Loyalty Program and Rewards</a></h5>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>InstaForex Nigeria provides clients with the opportunity to make more money through the Instafxng Loyalty Programme.</p>
                                                <p>This campaign will be held from December 1, 2016 and will run till November 30, 2017.</p>
                                                <ul>
                                                    <li>Points are earned for all account deposit actions.</li>
                                                    <li>Points are earned for all account trading activity.</li>
                                                    <li>Accounts not enrolled in ILPR only earn reward points anytime they make deposits.</li>
                                                    <li>Account deposit of $50 earns the same reward points as trading a volume of 1 insta lot.</li>
                                                    <li>Accounts enrolled in ILPR earn reward points for both deposit activities and all their trading activities.</li>
                                                    <li>Points can be redeemed only for account deposit when you have earned a minimum of one hundred points. This can thereafter be withdrawn to your bank account or for further trading.</li>
                                                    <li>Points can be redeemed when you make a deposit equivalent to the amount of points you want to redeem. E.g. If you have 500 points and you want to take 200points from it, you need to make a deposit of $20.</li>
                                                    <li>Each earned point has a life span of one year after which if not redeemed it will expire and will automatically be deleted.</li>
                                                    <li>Redeemed points will be deducted from total earned points and only the new points balance will count for monthly and annual ranking.</li>
                                                    <li>Clients with multiple accounts under the same email address will have all their rewards points summed up and aggregated.</li>
                                                    <li>Members of staff of Instant Web-Net Technologies Ltd are disqualified from taking part in this promotion.</li>
                                                    <li>InstaForex Nigeria holds the sole right to amend or supplement the rules without prior notice.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">How winners are chosen</a></h5>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Monthly - Top 5 clients with the highest ranks in a calendar month within the current loyalty year gets the
                                                    following prizes at the beginning of a new month.</p>
                                                <ul>
                                                    <li>First Prize: $150</li>
                                                    <li>Second Prize: $120</li>
                                                    <li>Third Prize: $100</li>
                                                    <li>Fourth Prize: $80</li>
                                                    <li>Fifth Prize: $50</li>
                                                </ul>
                                                <p>Yearly - Top 10 clients with the highest ranks within the current loyalty year gets the following prizes at our
                                                    annual end of the year event.</p>
                                                <ul>
                                                    <li>First Prize: One million naira</li>
                                                    <li>Second Prize: Five hundred thousand naira</li>
                                                    <li>Third Prize: Two hundred and fifty thousand naira</li>
                                                    <li>Fourth Prize: One hundred and fifty thousand naira</li>
                                                    <li>Fifth Prize: One hundred thousand naira</li>
                                                    <li>Sixth to tenth prize: Fifty thousand naira each</li>
                                                </ul>
                                                <p>Winners are determined within 48 hours after the close of ranking.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Presentation of Prizes</a></h5>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Winners for each monthly and yearly round of the promotion will be displayed on the loyalty page, the winners will be
                                                    contacted and their winnings deposited into their InstaForex accounts.</p>
                                                <p>Please be informed that InstaForex holds the right to update the rules of the
                                                    campaign without prior notice.</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->
                    
                </div>
                <!-- Main Body - Side Bar  -->
                <div id="main-body-side-bar" class="col-md-5 col-md-pull-7 col-lg-4 col-lg-pull-8 left-nav">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="nav-display super-shadow">
                                <header><h5>Top 20 Rank in <?php echo date('F, Y', strtotime($from_date)); ?></h5></header>
                                <article>
                                    <div class="table-responsive mtl" style="overflow: scroll; max-height: 500px;">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Name</th>
                                                <th>Rank Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            $count = 1;
                                            if(isset($selected_loyalty) && !empty($selected_loyalty)) {
                                                foreach ($selected_loyalty as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count; ?></td>
                                                        <td><?php if($row['full_name'] == 'Management' || empty($row['full_name'])) { echo $row['last_name']; } else { echo $row['full_name']; }; ?></td>
                                                        <td><?php echo number_format(($row['month_rank']), 2, ".", ","); ?></td>
                                                    </tr>
                                                    <?php $count++; } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <hr />

                                    <h5 class="text-center">Haven't made it to the top 20 in <?php echo date('F, Y', strtotime('this month')); ?> yet? No worries, check your position below and speed things up to appear on the rank list</h5>
                                    <br />

                                    <div class="form-group">
                                        <label for="ifx_account">Instaforex Account: </label>
                                        <input type="text" class="form-control" id="ifx_account" name="ifx_account" placeholder="Instaforex Account" value="" onchange="calculatePoints(this.value, 'month');" onfocus="calculatePoints(this.value, 'month');" onblur="calculatePoints(this.value, 'month');" />
                                        <span class="help-block">Press the enter key after typing</span>
                                    </div>

                                    <br />
                                    <div id="monthlyPoints"></div>
                                </article>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="nav-display super-shadow">
                                <header><h5>Top 20 Rank in current loyalty year</h5></header>
                                <article>
                                    <div class="table-responsive mtl" style="overflow: scroll; max-height: 500px;">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Name</th>
                                                <th>Rank Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>

                                                <?php
                                                $count = 1;
                                                if(isset($selected_loyalty_year) && !empty($selected_loyalty_year)) {
                                                foreach ($selected_loyalty_year as $row) {
                                                ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php if($row['full_name'] == 'Management' || empty($row['full_name'])) { echo $row['last_name']; } else { echo $row['full_name']; }; ?></td>
                                                <td><?php echo number_format(($row['year_rank']), 2, ".", ","); ?></td>
                                            </tr>
                                            <?php $count++; } } else { echo "<tr><td colspan='3' class='text-danger'><em>No results to display</em></td></tr>"; } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <hr />

                                    <h5 class="text-center">Haven't made it to the top 20 in the current loyalty year? Check your position below and speed things up to appear on the rank list</h5>
                                    <br />

                                    <div class="form-group">
                                        <label for="ifx_account">Instaforex Account: </label>
                                        <input type="text" class="form-control" id="ifx_account" name="ifx_account" placeholder="Instaforex Account" value="" onchange="calculatePoints2(this.value, 'year');" onfocus="calculatePoints2(this.value, 'year');" onblur="calculatePoints2(this.value, 'year');" />
                                        <span class="help-block">Press the enter key after typing</span>
                                    </div>

                                    <br />
                                    <div id="yearlyPoints"></div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>