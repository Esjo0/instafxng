<?php

$user_code = $_SESSION['client_unique_code'];
$initiated_trans = $education_object->get_initiated_trans_by_code($user_code);

?>

<div class="section-tint-red super-shadow">
    <div class="row">
        <div class="col-sm-9 text-center">
            <h4 id="header-h4" class="white-text">Forex Profit Academy</h4>
        </div>
        <div class="col-sm-3 text-center">
            <i id="special-i" class="fa fa-line-chart fa-fw white-text"></i>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-sm-12">
            <nav class="navbar navbar-default" style="margin-bottom: 0;">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <span class="visible-xs navbar-brand">Menu <i class="fa fa-fw fa-long-arrow-right"></i></span>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="fxacademy/index.php" title="Dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                            <li><a href="fxacademy/support_message.php" title=""><i class="fa fa-envelope fa-fw"></i> Messages</a></li>
                            <?php if($initiated_trans) { ?>
                            <li><a href="fxacademy/pay_notify.php" title=""><i class="fa fa-envelope fa-fw"></i> Notification</a></li>
                            <?php } ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="fxacademy/logout.php" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>