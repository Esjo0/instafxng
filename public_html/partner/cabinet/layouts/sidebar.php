<!-- Main Body - Side Bar  -->
<div class="col-md-12">
    <div class="list-group" style="margin-bottom: 5px !important;">
        <a class="list-group-item" href="partner/cabinet/" title="" style=" background-color: #373739; color: #FFFFFF">
            <p class="text-capitalize text-center"><strong>Hello: <?php echo $_SESSION['partner_first_name'] . " " . $_SESSION['partner_last_name']; ?></strong></p>
        </a>
    </div>
</div>

<div class="col-md-12">
    <nav id="side-nav" class="navbar navbar-default" role="navigation">
         <!--Brand and toggle get grouped for better mobile display--> 
        <div class="navbar-header">
            <span class="visible-xs navbar-brand">Menu <i class="fa fa-fw fa-long-arrow-right"></i></span>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

         <!--Collect the nav links, forms, and other content for toggling--> 
        <div class="collapse navbar-collapse navbar-ex1-collapse left-navigation">
            <ul class="nav navbar-nav">
                <li><a href="partner/cabinet/" title="Partner Dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                <li><a href="partner/cabinet/referrals_listing.php" title="Partner Dashboard"><i class="fa fa-bars fa-fw"></i> View Referrals</a></li>
               
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> Accounts<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="partner/cabinet/ifxacc_view.php" title="Customer support">IFX Accounts</a></li>
                        <li><a href="partner/cabinet/bankacc_view.php" title="Instafxng Partners Program">Bank Accounts</a></li>
                    </ul>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> Transactions<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="partner/cabinet/fc_history.php" title="Financial Commission History">Financial Commission History</a></li>
                        <li><a href="partner/cabinet/tc_history.php" title="Trading Commission History">Trading Commission History</a></li>
                        <li><a href="partner/cabinet/withdraw_history.php" title="Withdrawal History">Withdrawal History</a></li>
                    </ul>
                </li>
                <li><a href="partner/cabinet/logout.php" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="col-md-12">
    <div class="nav-display super-shadow">
        <header><i class="fa fa-bars fa-fw"></i> Connect With Us</header>
        <article>
            <a href="https://facebook.com/InstaForexNigeria" target="_blank"><img src="images/Facebook.png"></a>
            <a href="https://twitter.com/Instafxng" target="_blank"><img src="images/Twitter.png"></a>
            <a href="https://linkedin.com/company/instaforex-ng" target="_blank"><img src="images/LinkedIn.png"></a>
        </article>
    </div>
</div>