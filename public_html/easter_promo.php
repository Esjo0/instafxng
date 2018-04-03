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
        <title>Instaforex Nigeria | Special Easter Promo</title>
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
                                    <img src="images/Easter/Untitled.png" alt="" class="img-responsive" />
                                </center>

                                <h3 class="text-center">INSTAFXNG'S CELEBRATE A MILESTONE PROMO</h3>
                                <p class="text-justify">To celebrate the completion of the first quarter, we’re rewarding 3 clients with an All-Expense Paid Buffet at Four Points by Sheraton Hotel.</p>
                                <p class="text-justify">It’s More than a Buffet, It’s an Experience!</p>
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
                                                <th>Promo Points</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $count = 1;
                                            foreach ($top_entries as $row){ extract($row); ?>
                                            <tr>
                                                <th><?php echo $count; ?></th>
                                                <td><?php echo $obj_easter_promo->get_client_by_name($participant)['first_name']; ?></td>
                                                <th><?php echo number_format($points); ?></th>
                                            </tr>
                                            <?php $count++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-5">
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th colspan="2" class="text-center">Guaranteed Winners</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Participants Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Day 1 -> <?php echo date_to_text('04-04-2018')?></td>
                                                    <td>
                                                        <?php
                                                        $winner = $obj_easter_promo->get_winner('2018-04-04', '2018-04-04');
                                                        if(isset($winner['participant']) && !empty($winner['participant']) && $winner['participant']!= 0)
                                                        {
                                                            echo $obj_easter_promo->get_client_by_name($winner['participant'])['first_name'];
                                                        }
                                                        else
                                                        {
                                                            echo "<center>---</center>";
                                                        }

                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Day 2 -> <?php echo date_to_text('05-04-2018')?></td>
                                                    <td>
                                                        <?php
                                                        $winner = $obj_easter_promo->get_winner('2018-04-05', '2018-04-05');
                                                        if(isset($winner['participant']) && !empty($winner['participant']))
                                                        {
                                                            echo $obj_easter_promo->get_client_by_name($winner['participant'])['first_name'];
                                                        }
                                                        else
                                                        {
                                                            echo "<center>---</center>";
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Day 3 -> <?php echo date_to_text('06-04-2018')?></td>
                                                    <td>
                                                        <?php
                                                        $winner = $obj_easter_promo->get_winner('2018-04-06', '2018-04-06');
                                                        if(isset($winner['participant']) && !empty($winner['participant']))
                                                        {
                                                            echo $obj_easter_promo->get_client_by_name($winner['participant'])['first_name'];
                                                        }
                                                        else
                                                        {
                                                            echo "<center>---</center>";
                                                        }
                                                        ?>
                                                    </td>
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
                                            <?php if(isset($search_result) && !empty($search_result)) {?>
                                                <center> You have <b><?php echo $search_result['entries'];?> entry(s)</b><br/>
                                                You have <b><?php echo $search_result['points'];?> point(s)</b></center>
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
                                        <p class="text-justify"><i class="glyphicon glyphicon-check"></i>   You can choose to fund multiples units of $50 in a day or a lump
                                            sum that will be divided into multiples of 50.<br/>
                                            E.g. If you intend to fund $1000 in a day, you can either fund $50 twenty times in a day or you could deposit the lump sum of $1000
                                            at once.<br/>Your deposit transaction will be split into $50 portions to generate equivalent unit amount.</p>
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