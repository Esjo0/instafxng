<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";

$top_entries = $obj_easter_promo->get_top_entries(date('Y-m-d'), date('Y-m-d'), 10);

if(isset($_POST['search']))
{
    $search_result = $obj_easter_promo->get_points_per_acc($_POST['acc_number']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Milestone Celebration Promo</title>
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
                <div class="col-sm-12">
                    <div class="section-tint super-shadow">
                        <center>
                            <img src="images/Q2_Promo/Instafxng_Milestone_Celebration.jpg" alt="" class="img-responsive" />
                        </center>

                        <h3 class="text-center">INSTAFXNG'S CELEBRATE A MILESTONE PROMO</h3>
                        <p class="text-justify">To celebrate the completion of the first quarter, we’re rewarding 3 clients with an All-Expense Paid Buffet at Four Points by Sheraton Hotel.</p>
                        <p class="text-justify">It's More than a Buffet, It’s an Experience!</p>
                        <p class="text-justify">Enjoy an Exclusive All-Expense-Paid Buffet Treat at Four Points by Sheraton Hotel in the Celebrate a Milestone promo.</p>
                        <p class="text-justify">The promo runs for 3 days only. It starts on Wednesday, 4th of April and ends on Friday 6th of April, 2018.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-tint super-shadow">
                        <?php require_once 'layouts/feedback_message.php'; ?>
                        <div class="row">
                            <div class="col-sm-7">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">Today's Race as at <?php echo date('h:i:s A')?>, <?php echo date('d M Y')?></th>
                                    </tr>
                                    <tr>
                                        <th>Position</th>
                                        <th>Participants Name</th>
                                        <!--<th>Promo Points</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($top_entries as $row){ extract($row); ?>
                                        <tr>
                                            <th><?php echo $count; ?></th>
                                            <td><?php echo $obj_easter_promo->get_client_by_name($participant)['first_name']; ?></td>
                                            <!--<th><?php /*//echo number_format($points); */?></th>-->
                                        </tr>
                                        <?php $count++; } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-5">
                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Leading In Today's Race</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="text-center text-success text-uppercase text-bold"><b>
                                                <?php
                                                $winner = $obj_easter_promo->get_winner(date('Y-m-d'), date('Y-m-d'));
                                                if(isset($winner['participant']) && !empty($winner['participant']) && $winner['participant']!= 0)
                                                {
                                                    echo $obj_easter_promo->get_client_by_name($winner['participant'])['first_name'];
                                                }
                                                else
                                                {
                                                    echo "---";
                                                }
                                                ?>
                                            </b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><a href="deposit.php">Do you want to take the lead in today's race, click here to fund your Instaforex Account now.</a></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <p>Check your position in today's race!</p>

                                <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <p>Enter your account number below to begin.</p>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="acc_number" placeholder="Account Number" required/>
                                        <span class="input-group-btn">
                                                    <button name="search" class="btn btn-success" type="submit">
                                                        <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                    </div>
                                    <?php if(isset($search_result) && (!empty($search_result['entries']) && !empty($search_result['points']))) {?>
                                        <p class="text-success">You have <b><?php echo number_format($search_result['entries']);?> entry(s)</b><br/>
                                            You have <b><?php echo number_format($search_result['points']);?> point(s)</b></p>
                                    <?php }?>
                                    <?php if(isset($search_result) && (empty($search_result['entries']) && empty($search_result['points']))) {?>
                                            <p class="text-danger">Sorry you do not yet have an entry yet, please fund you Instaforex Account to enter the Race.<br/>
                                            You have <b><?php echo number_format($search_result['entries']);?> entry(s)</b><br/>
                                            You have <b><?php echo number_format($search_result['points']);?> point(s)</b></p>
                                    <?php }?>
                                </form>
                                <br/>
                            </div>
                            <div class="col-sm-12">
                                <a class="btn-group-justified btn btn-success btn-lg" href="deposit.php"><b>Join The Race Now!!!</b></a>
                            </div>
                            <div class="col-sm-12">
                                <p><b>TERMS & CONDITIONS</b></p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Only ILPR clients are eligible to participate.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   The minimum deposit that qualifies you for the promo is $50.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Make a deposit of a lump sum and it will be automatically split into multiples of $50 portions to generate the equivalent unit amount.
                                    E.g. If you intend to deposit the sum of $1000 into your InstaForex account, you don’t have to deposit $50 twenty times.
                                    Just make a deposit of $1000 and our system will automatically calculate your frequency.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   The trader with the highest number of multiple $50 transactions in
                                    a day is the winner for that particular day.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   All daily winners automatically qualify for the prize.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   Winners will be contacted at the end of the promotion to redeem their prizes. </p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   The prize is an Exclusive All-Expense-Paid Buffet Treat at Four Points by Sheraton Hotel.</p>
                                <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   No cash equivalent will be issued out to winner in place of the stated prize.</p>
                            </div>
                        </div>
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