<?php
require_once 'init/initialize_general.php';
$thisPage = "Signals";

$id = $db_handle->sanitizePost(trim($_POST['id']));
$query = "UPDATE signal_intraday SET views = views + 1 WHERE id = '$id'";

$result =$db_handle->runQuery($query);
$query = "UPDATE signal_views SET views = views + 1 WHERE id = 'page_views' ";
$result = $db_handle->runQuery($query);

$query = "SELECT * FROM signal_intraday ";
$numrows = $db_handle->numRows($query);

$rowsperpage = 10;

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
$all_signals = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Online Instant Forex Trading Services</title>
        <meta name="title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta name="keywords" content="instaforex, forex trading in nigeria, forex seminar, forex trading seminar, how to trade forex, trade forex, instaforex nigeria">
        <meta name="description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us.">
        <?php require_once 'layouts/head_meta.php'; ?>
        
        <meta property="og:site_name" content="Instaforex Nigeria" />
        <meta property="og:title" content="Instaforex Nigeria | Online Instant Forex Trading Services" />
        <meta property="og:description" content="Instaforex, a multiple award winning international forex broker is the leading Forex broker in Nigeria, open account and enjoy forex trading with us." />
        <meta property="og:image" content="images/instaforex-100bonus.jpg" />
        <meta property="og:url" content="https://instafxng.com/" />
        <meta property="og:type" content="website" />
        <style type="text/css">
            .sig {
                background-image: url('images/signals.jpg');
            }
            .trig{
                background-image: url('images/cross.jpg');
            }
        </style>


    </head>
    <body id="results">
        <?php require_once 'layouts/header.php'; ?>
        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">
                <?php require_once 'layouts/topnav.php'; ?>

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">
                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <div  class="item super-shadow page-top-section">
                        <div class="row">
                                <div class="col-sm-12 sig" style="height: 200px;" >
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h5>InstaFxNg Signals</h5>
                                        </div>
                                    <div class="col-sm-6">
                                    </div>
                                    <div class="col-sm-2">
                                        <button style="height: 200px; width: 150px;"; type="button" class="btn btn-default btn-lg trig ">Signal Strength <br> <h5>100%</h5></button>
                                    </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="section-tint super-shadow">
                        <h2 class="text-center">Intra-Day Signals For <?php echo date("F j, Y, g:i a")?></h2>

                        <?php foreach ($all_signals as $row) {?>
                            <script>
                                function SubmitFormData<?php echo $row['id']?>() {
                                    var id = <?php echo $row['id']?>;
                                    $.post("signals.php", { id: id });
                                }
                            </script>
                            <form method="post" id="myForm<?php echo $row['id']?>" role="form" enctype="multipart/form-data">
                                <input id="id" name="id" type="hidden" value="<?php echo $row['id']?>">
                        <div class=" section-tint super-shadow" style="padding: unset;<?php if($row['status'] == 1){ echo "background-color: #5ec149;";} ?>" data-toggle="modal" data-target="#viewsignal<?php echo $row['id']?>" onclick="SubmitFormData<?php echo $row['id']?>();" type="submit">
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-sm-3 center">
                                            <center><span id="trans_remark_author"><h5><?php echo $row['currency_pair']?></h5></span></center>
                                        </div>
                                        <div class="col-sm-5"></div>
                                        <div class="col-sm-4 right" style="align-content: right;">
                                            <span  id="trans_remark_date"><strong>Trigger Time:</strong> <?php echo datetime_to_text($row['signal_time'])?> </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-lg-12">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-3">
                                        <center><button type="button" class="btn btn-primary btn-sm bottom">BUY PRICE <?php echo $row['buy_price']?></button></center>
                                    </div>
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-3">
                                        <center><button type="button" class="btn btn-danger btn-sm bottom">SELL PRICE <?php echo $row['sell_price']?></button><center>
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>
                                <br>
                                    <marquee behavior="scroll" direction="left" scrollamount="5" class="col-lg-12">
                                        <?php echo $row['comment'];?>
                                    </marquee></span>
                            </div>
                            <br>
                        </div>
                            </form>
                            <!-- Modal -->
                            <div class="modal fade" id="viewsignal<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">This Trade is to trigger By <?php echo datetime_to_text($row['signal_time'])?> </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-primary btn-sm bottom">BUY PRICE <?php echo $row['buy_price']?></button>
                                                </div>
                                                <div class="col-sm-8">
                                                    <table class="table table-hover table-responsive table-striped">
                                                        <thead class="thead-light">
                                                            <th>Take Profit</th>
                                                            <th>Stop Loss</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo $row['buy_price_tp']?></td>
                                                            <td><?php echo $row['buy_price_sl']?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-danger btn-sm bottom">SELL PRICE <?php echo $row['sell_price']?></button>
                                                </div>
                                                <div class="col-sm-8">
                                                    <table class="table table-hover table-responsive table-striped">
                                                        <thead class="thead-light">
                                                        <th>Take Profit</th>
                                                        <th>Stop Loss</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td><?php echo $row['sell_price_tp']?></td>
                                                            <td><?php echo $row['sell_price_sl']?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <li class="media" style="font-size:small;">
                                                    <div class="media-body">
                                                        <div class="well">
                                                            <center>
                                                            <p class="media-comment left">
                                                                <?php echo $row['comment'];?>
                                                            </p>
                                                            <h5>
                                                                Kingsley Ifoga
                                                            </h5>
                                                            <ul class="media-date text-uppercase reviews list-inline right">
                                                                <small class="text-muted"><?php echo datetime_to_text($row['signal_time']); ?></small>
                                                            </ul>
                                                                <div class="sharethis-inline-share-buttons"></div>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php require 'layouts/pagination_links.php'; ?>
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