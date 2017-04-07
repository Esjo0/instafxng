<?php
require_once 'init/initialize_general.php';
$thisPage = "Promotion";
$all_entries = $db_handle->fetchAssoc($db_handle->runQuery("SELECT entries_idnumber, entries_transid, entries_name FROM entries WHERE entries_remark = 'yes' AND expired = '0' ORDER BY entries_idnumber DESC"));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Lucky 150 Promotion - Win a Fun Trip to Glamorous Dubai</title>
        <meta name="title" content="Instaforex Nigeria | Lucky 150 Promotion - Win a Fun Trip to Glamorous Dubai" />
        <meta name="keywords" content="instaforex, forex promotions, lucky 150 promo, instaforex promos, instaforex nigeria." />
        <meta name="description" content="With the Lucky 150 Promotion, you stand a chance of winning a Blackberry SmartPhone, an HP mini Laptop and other prizes as provided by InstaForex Nigeria." />
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
                    <div class="super-shadow page-top-section">
                        <div class="row ">
                            <div class="col-sm-6">
                                <h1>Win Big In The Lucky 150 Promotion</h1>
                                <p>The stakes are now very much higher for winners in the lucky 150 promo. We are excited 
                                    to announce that star winner at every round of the Lucky 150 promotions will win a 
                                    free all expenses paid fun <span class="text-danger"><strong>Trip to Dubai</strong></span> and the entry 150
                                    will win a <span class="text-danger"><strong>Samsung Galaxy Notebook</strong></span>.</p>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/lucky-150-promo-winner.jpg" alt="" class="img-responsive" />
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12 text-danger">
                                <h4><strong>Lucky 150 Promotion</strong></h4>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Yes! It's a big one, but there's more. Other prizes to be given out include Samsung Galaxy Notebooks and smart phones and you could be a winner!</p>
                                
                                <h5>How To Qualify For The Lucky 150 Promotion</h5>
                                <ul class="fa-ul">
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 1</strong></li>
                                        <p>You need to have an InstaForex Account which must be enrolled in the InstaFxNg Loyalty Program and Rewards (ILPR). Please confirm with our
                                        <a href="contact_info.php" target="_blank" title="Confirm that you have a qualifying account">support department</a> that your InstaForex Account qualifies.</p>
                                        <p>If you don't have an InstaForex Account yet, <a href="live_account.php" target="_blank" title="Open A Live Trading Account">open a qualifying account</a> now.</p>
                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i><strong>Step 2</strong></li>
                                        <p><a href="deposit_funds.php" target="_blank" title="Fund Your Instaforex Account">Fund your InstaForex Account</a> with a minimum of $500.
                                         Each funding gets you an entry into the promotion and a chance to win any of the prizes. The more entries you make, the more your chances of winning.</p>
                                        <p>All qualifying entries in the current round are updated automatically.</p>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Entries in the current round of Lucky 150 Promotion</h5>
                                <div class="table-responsive mtl" style="overflow: scroll; max-height: 500px;">
                                    
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Entry</th>
                                                <th>Trans ID</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count = count($all_entries);
                                                foreach ($all_entries as $row) { 
                                                    extract($row); 
                                            ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $entries_transid; ?></td>
                                                <td><?php echo $entries_name; ?></td>
                                            </tr>
                                            <?php $count--; } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">How The Winners Are Chosen</a></h5>
                                        </div>
                                        <div id="collapse1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p><strong>Guaranteed Winner 1 (Entry 150)</strong><br />
                                                The first winner shall be the person who has the entry number 150 for each round of the promotion. This is the <strong>lucky 150 winner</strong>. This winner will receive a Samsung Galaxy Notebook.</p>
                                                <p><strong>The Lucky Draw Star Winner</strong><br />
                                                A draw will be held at the end of each round and a star winner will be picked. The star winner shall win the star prize of an All Expense Paid Fun Trip to Dubai. This shall include visa, return ticket, 4 star hotel accomodation and Dubai City tour.</p>
                                                <p><strong>The Lucky Draw Co-Winners</strong><br />
                                                During the draw at the end of each round of promotion, additional winners will emerge to receive branded high performance Samsung Galaxy Smart Phones.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Rules Of The Lucky 150 Promotion</a></h5>
                                        </div>
                                        <div id="collapse2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="fa-ul">
                                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Your InstaForex Account must be enrolled in the InstaFxNg Loyalty Program and Rewards (ILPR) <a href="live_account.php" target="_blank">Click Here To Open a qualifying ILPR enrolled account now!</a></li>
                                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Each individual funding of $500 or more to your InstaForex Account through <a href="deposit_funds.php" target="_blank">this website</a> automatically gives you an entry into the promotion.</li>
                                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>You can make as many entries as you want to improve your chances of winning.</li>
                                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>You may only make a withdrawal of any profit made and not the initial funds during the course of the round of the promotion in which you have an entry. Withdrawing part or all of the initial funding that got you an entry during the course of a round of the Lucky 150 promotion disqualifies your entry.</li>
                                                    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>All staff, stakeholders of InstaForex Nigeria and their family are disqualified from taking part in this promotion.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse3">When Will Draws Hold?</a></h5>
                                        </div>
                                        <div id="collapse3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>A draw will hold in the first week of every month during the Traders Forum provided   that a Lucky Entry 150 winner has emerged for that round of the   promotion. The star winner and the co winners will emerge from the draw. Everyone with an entry will be invited to attend the draw.</p>
                                                <p>If the number of entries has reached 300 before the draw   date, there shall be 2 Lucky 150 winners ( entry number 150 and entry   number 300). The other winners will emerge through the draws.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Presentation Of Prizes</a></h5>
                                        </div>
                                        <div id="collapse4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>The draws shall be covered and broadcasted on this page. The winners will be contacted and   the presentation will be broadcasted too.</p>
                                                <p>A New Round Begins Immediately After The Winners Are Announced For Each Round.</p>
                                                <p><strong>What Are You Waiting For? Join InstaForex Lucky 150 Promotion Now. E Fit Be You.</strong></p>
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
                <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
                <?php require_once 'layouts/sidebar.php'; ?>
                </div>
            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>
    </body>
</html>