<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$query = "SELECT * FROM signal_intraday WHERE status = 1";
$triggered = $db_handle->numRows($query);

$query = "SELECT views FROM signal_views WHERE id = 'page_views' ";
$result = $db_handle->runQuery($query);
$views = $db_handle->fetchAssoc($result);
foreach($views AS $view){extract($view);}

$admin_code = $_SESSION['admin_unique_code'];
if(isset($_POST['create'])){
   $pair = $db_handle->sanitizePost($_POST['pair']);
   $query = "INSERT INTO signal_symbol(symbol) VALUE('$pair')";
   $result2 =$db_handle->runQuery($query);
    if($result2) {
        $message_success = "You have successfully created a new Currency Symbol";
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['trigger'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $query = "UPDATE signal_intraday SET status = '1' WHERE id = '$id'";
    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "Signal Triggered Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }
}

if(isset($_POST['signal'])){
    $symbol = $db_handle->sanitizePost($_POST['symbol']);
    $buy_price = $db_handle->sanitizePost($_POST['buy_price']);
    $buy_price_tp = $db_handle->sanitizePost($_POST['buy_price_tp']);
    $buy_price_sl = $db_handle->sanitizePost($_POST['buy_price_sl']);
    $sell_price = $db_handle->sanitizePost($_POST['sell_price']);
    $sell_price_tp = $db_handle->sanitizePost($_POST['sell_price_tp']);
    $sell_price_sl = $db_handle->sanitizePost($_POST['sell_price_sl']);
    $signal_time = $db_handle->sanitizePost($_POST['signal_time']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $query ="INSERT INTO signal_intraday(currency_pair, buy_price, buy_price_tp, buy_price_sl, sell_price, sell_price_tp, sell_price_sl, signal_time, comment, admin, status)
              VALUE('$symbol','$buy_price', '$buy_price_tp', '$buy_price_sl', '$sell_price', '$sell_price_tp', '$sell_price_sl', '$signal_time', '$comment', '$admin_code', '0')";
    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "Signal Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }

}

if(isset($_POST['update'])){
    $id = $db_handle->sanitizePost($_POST['id']);
    $symbol = $db_handle->sanitizePost($_POST['symbol']);
    $buy_price = $db_handle->sanitizePost($_POST['buy_price']);
    $buy_price_tp = $db_handle->sanitizePost($_POST['buy_price_tp']);
    $buy_price_sl = $db_handle->sanitizePost($_POST['buy_price_sl']);
    $sell_price = $db_handle->sanitizePost($_POST['sell_price']);
    $sell_price_tp = $db_handle->sanitizePost($_POST['sell_price_tp']);
    $sell_price_sl = $db_handle->sanitizePost($_POST['sell_price_sl']);
    $signal_time = $db_handle->sanitizePost($_POST['signal_time']);
    $comment = $db_handle->sanitizePost($_POST['comment']);
    $status = $db_handle->sanitizePost($_POST['status']);
    $query = "UPDATE signal_intraday SET currency_pair = '$symbol',buy_price = '$buy_price', buy_price_tp = '$buy_price_tp',buy_price_sl = '$buy_price_sl',sell_price = '$sell_price', sell_price_tp = '$sell_price_tp',sell_price_sl = '$sell_price_sl', signal_time = '$signal_time',comment = '$comment',admin = '$admin_code',status = '$status' WHERE id = '$id'";
    $result =$db_handle->runQuery($query);
    if($result) {
        $message_success = "Signal Updated Successfully created for ".datetime_to_text($signal_time);
    } else {
        $message_error = "Something went wrong. Please try again.";
    }

}

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
        <title>Instaforex Nigeria | Signal Daily</title>
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
                    <div class="row">
                        <div class="col-sm-12 text-danger">
                            <h4><strong>UPLOAD INTRA-DAY SIGNALS</strong></h4>
                        </div>
                    </div>
<div class="row">
    <div class="col-md-8">
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Modify the form below to update the daily signal. Choose the date you are posting the signal for.</p>
                                <br />
                                <div class="col-md-12 order-md-1">
                                    <form class="needs-validation" role="form" method="post" action="">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location">Currency Pair </label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class="input-group date">
                                                    <select name="symbol" class="form-control" id="location">
                                                        <?php
                                                        $query = "SELECT * FROM signal_symbol ";
                                                        $result = $db_handle->runQuery($query);
                                                        $result = $db_handle->fetchAssoc($result);
                                                        foreach ($result as $row)
                                                        {
                                                            extract($row)
                                                            ?>
                                                            <option value="<?php echo $symbol;?>"><?php echo $symbol;?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="input-group-addon"><span class="fa fa-gg"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                        <hr/>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label class="control-label col-sm-3" for="">BUY PRICE </label>
                                                    <div class="col-sm-3"><input name="buy_price" type="text" class="form-control" placeholder="Price"  required/></div>
                                                    <div class="col-sm-3"><input name="buy_price_tp" type="text" class="form-control" placeholder="TP" required/></div>
                                                    <div class="col-sm-3"><input name="buy_price_sl" type="text" class="form-control" placeholder="SL" required/></div>
                                                </div>
                                            </div>
                                        </div>
<br/>
                                        <div class="form-group">

                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <label class="control-label col-sm-3" for="">SELL PRICE </label>
                                                    <div class="col-sm-3"><input name="sell_price" type="text" value="<?php if(isset($eur_usd_sell_price)) { echo $eur_usd_sell_price; } ?>" class="form-control" placeholder="Price"  required/></div>
                                                    <div class="col-sm-3"><input name="sell_price_tp" type="text" value="<?php if(isset($eur_usd_sell_tp)) { echo $eur_usd_sell_tp; } ?>" class="form-control" placeholder="TP" required/></div>
                                                    <div class="col-sm-3"><input name="sell_price_sl" type="text" value="<?php if(isset($eur_usd_sell_sl)) { echo $eur_usd_sell_sl; } ?>" class="form-control" placeholder="SL" required/></div>
                                                </div>
                                                <hr/><br/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="pay_date">Signal Date/Hour:</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class='input-group date' id='datetimepicker'>
                                                    <input name="signal_time" type="text" class="form-control" id='datetimepicker2' required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('#datetimepicker2').datetimepicker({
                                                        format: 'YYYY-MM-DD h:m:s'
                                                    });
                                                });
                                            </script>
                                        </div>

                                        <div class="form-group">
                                            <center><label class="col-sm-12" for="exampleFormControlTextarea1">Comment</label></center>
                                            <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                        </div>

                                        <br>
                                        <hr class="mb-4">
                                        <center><button name="signal" class="btn btn-success btn-sm bottom" type="submit"> Upload Signals</button><center>
                                    </form>
                                </div>
                            </div>
                            </div>
                        </div>
</div>
                    <div class="col-md-4">
                        <div class="col-md-12 order-md-2 mb-4 section-tint super-shadow">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Todays signals</span>
                                <span class="badge badge-secondary badge-pill"><?php echo $numrows?></span>
                                <span class="pull-right"><button type="button" title="View all signals" class="btn btn-success right"><i class="fa fa-history fa-fw"></i></button></span>
                            </h4>
                            <ul class="list-group mb-3">
                                <?php foreach ($all_signals as $row) {?>
                                <li class="list-group-item d-flex justify-content-between lh-condensed" data-toggle="modal" data-target="#editsignal<?php echo $row['id']?>">
                                    <div>
                                        <h6 class="my-0"><?php echo datetime_to_text($row['signal_time']); ?></h6>
                                        <small class="text-muted"><?php echo $row['currency_pair']?></small>
                                    </div>
                                    <span class="text-muted">
                                        BUY PRICE: <?php echo $row['buy_price'];?> <br> SELL PRICE: <?php echo $row['sell_price'];?> <button class="btn btn-default"><i class="fa fa-eye fa-fw"></i> <?php echo $row['views']?></button></span>
                                    </li>
                                    <div class="modal fade" id="editsignal<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">This Trade is to trigger By <?php echo datetime_to_text($row['signal_time']);?> </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <center><?php if($row['status'] == 0){?><form method="post" action=""><input name="id" type="hidden" value="<?php echo $row['id'];?>"><button name="trigger" type="submit" class="btn btn-success">Trigger</button></form><?php }?></center>

                                                    <form  role="form" method="post" action="">
                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="location">Currency Pair </label>
                                                                <div class="col-sm-9 col-lg-5">
                                                                    <div class="input-group date">
                                                                        <select name="symbol" class="form-control" id="location">
                                                                            <?php
                                                                            $query = "SELECT * FROM signal_symbol ";
                                                                            $result = $db_handle->runQuery($query);
                                                                            $result = $db_handle->fetchAssoc($result);
                                                                            foreach ($result as $row2)
                                                                            {
                                                                                extract($row2)
                                                                                ?>
                                                                                <option value="<?php echo $symbol;?>"><?php echo $symbol;?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                        <span class="input-group-addon"><span class="fa fa-gg"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <br/>
                                                            <hr/>
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="row">
                                                                        <label class="control-label col-sm-3" for="">BUY PRICE </label>
                                                                        <div class="col-sm-3"><input name="buy_price" type="text" class="form-control" value="<?php echo $row['buy_price'];?>"  required/></div>
                                                                        <div class="col-sm-3"><input name="buy_price_tp" type="text" class="form-control" value=" <?php echo $row['buy_price_tp'];?>" required/></div>
                                                                        <div class="col-sm-3"><input name="buy_price_sl" type="text" class="form-control" value=" <?php echo $row['buy_price_sl'];?>" required/></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <div class="form-group">

                                                                <div class="col-sm-12">
                                                                    <div class="row">
                                                                        <label class="control-label col-sm-3" for="">SELL PRICE </label>
                                                                        <div class="col-sm-3"><input name="sell_price" type="text" value="<?php echo $row['sell_price'];?>" class="form-control"   required/></div>
                                                                        <div class="col-sm-3"><input name="sell_price_tp" type="text" value="<?php echo $row['sell_price_tp'];?>" class="form-control"  required/></div>
                                                                        <div class="col-sm-3"><input name="sell_price_sl" type="text" value="<?php echo $row['sell_price_sl'];?>" class="form-control"  required/></div>
                                                                    </div>
                                                                    <hr/><br/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-sm-3" for="pay_date">Signal Date/Hour:</label>
                                                                <div class="col-sm-9 col-lg-5">
                                                                    <div class='input-group date' id='datetimepicker'>
                                                                        <input name="signal_time" type="text" class="form-control" id='datetimepicker3' value="<?php echo $row['signal_time'];?>" required>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                                <script type="text/javascript">
                                                                    $(function () {
                                                                        $('#datetimepicker3').datetimepicker({
                                                                            format: 'YYYY-MM-DD h:m:s'
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>

                                                            <div class="form-group">
                                                                <center><label class="col-sm-12" for="exampleFormControlTextarea1">Comment</label></center>
                                                                <input name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3" value="<?php echo $row['comment'];?>"/>
                                                            </div>
                                                            <input name="id" type="hidden" class="form-control" value="<?php echo $row['id'];?>" required>
                                                            <input name="status" type="hidden" class="form-control" value="<?php echo $row['status'];?>" required>
                                                            <br>
                                                            <hr class="mb-4">
                                                            <center><button name="update" class="btn btn-success btn-sm bottom" type="submit">Submit Changes</button><center>
                                                        </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <?php require 'layouts/pagination_links.php'; ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Signals</span>
                                    <strong><?php echo $numrows;?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Triggered Signals</span>
                                    <strong><?php echo $triggered;?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total Overall Signals Views</span>
                                    <strong><i class="fa fa-eye fa-fw"></i><?php echo $views;?></strong>
                                </li>
                            </ul>

                            <form role="form" method="post" action="">
                                <center>
                                <p>Create Currency Pair</p>
                                <div class="input-group">
                                    <input name="pair" type="text" class="form-control" placeholder="Enter Currency Pair Symbol" required>
                                </div>
                                    <button name="create" type="submit" class="btn btn-secondary"><strong>Create</strong></button>
                                </center>
                            </form>
                    </div>
</div>
                    </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>

            </div>
        </div>
        <?php require_once 'layouts/footer.php'; ?>

        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>