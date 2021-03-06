<?php
// Prepare admin full name
if(isset($_SESSION['admin_first_name']) && isset($_SESSION['admin_last_name']))
{    $admin_full_name = $_SESSION['admin_first_name'] . " " . $_SESSION['admin_last_name'];}
else {    $admin_full_name = "";}
$my_pages_sidebar = $_SESSION['user_privilege'];
?>

<div class="col-md-12">
    <div class="list-group" style="margin-bottom: 5px !important;">
        <a class="list-group-item" href="profile_setting.php" title="Profile Settings" style=" background-color: #373739; color: #FFFFFF">
            <p class="text-capitalize text-center"><strong><?php echo $admin_full_name; ?></strong></p>
        </a>
    </div>
</div>

<div class="col-md-12">
    <nav id="side-nav" class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <span class="visible-xs navbar-brand">Menu <i class="fa fa-fw fa-long-arrow-right"></i></span>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse left-navigation">
            <ul class="nav navbar-nav">
                <li><a href="./" title="Admin Dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tags fa-fw"></i> Admin<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(1, $my_pages_sidebar)) { ?><li><a href="admin_add.php" title="Add New Admin Profile">Add Admin</a></li><?php } ?>
                        <?php if (in_array(2, $my_pages_sidebar)) { ?><li><a href="admin_view.php" title="View Admin Members">View Admin</a></li><?php } ?>
                        <?php if (in_array(3, $my_pages_sidebar)) { ?><li><a href="bulletin_add.php" title="Add Bulletin">Add Bulletin</a></li><?php } ?>
                        <?php if (in_array(4, $my_pages_sidebar)) { ?><li><a href="bulletin_view.php" title="Manage Bulletin">Manage Bulletin</a></li><?php } ?>
                        <?php if (in_array(5, $my_pages_sidebar)) { ?><li><a href="bulletin_centre.php" title="Bulletin Centre">Bulletin Centre</a></li><?php } ?>
                        <?php if (in_array(6, $my_pages_sidebar)) { ?><li><a href="snappy_help_add.php" title="Add Snappy Help">Add Snappy Help</a></li><?php } ?>
                        <?php if (in_array(7, $my_pages_sidebar)) { ?><li><a href="snappy_help_manage.php" title="Manage Snappy Help">Manage Snappy Help</a></li><?php } ?>
                        <?php if (in_array(1, $my_pages_sidebar)) { ?><li><a href="account_officers.php" title="Account Officers">Account Officers</a></li><?php } ?>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-fw"></i> Client Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(8, $my_pages_sidebar)) { ?><li><a href="client_add.php" title="Add Client">Add Client</a></li><?php } ?>
                        <?php if (in_array(9, $my_pages_sidebar)) { ?><li><a href="client_flag_account.php" title="Flag Account">Flag Account</a></li><?php } ?>
                        <?php if (in_array(10, $my_pages_sidebar)) { ?><li><a href="client_flag_view.php" title="View Flags">View Flags</a></li><?php } ?>
                        <?php if (in_array(11, $my_pages_sidebar)) { ?><li><a href="client_search.php" title="Search Clients">Search Clients</a></li><?php } ?>
                        <?php if (in_array(300, $my_pages_sidebar)) { ?><li><a href="deposit_confirm_ifx_account.php" title="Confirm IFX Account">Confirm IFX Account</a></li><?php } ?>
                        <?php if (in_array(12, $my_pages_sidebar)) { ?><li><a href="client_view.php" title="View Clients">View Clients</a></li><?php } ?>
                        <?php if (in_array(201, $my_pages_sidebar)) { ?><li><a href="client_delete.php" title="Delete Client">Delete Client</a></li><?php } ?>
                        <?php if (in_array(13, $my_pages_sidebar)) { ?><li><a href="client_moderate_account.php" title="Moderate IFX Account">Moderate IFX Account</a></li><?php } ?>
                        <?php if (in_array(14, $my_pages_sidebar)) { ?><li><a href="client_update_account.php" title="Update IFX Account">Update IFX Account</a></li><?php } ?>
                        <?php if (in_array(15, $my_pages_sidebar)) { ?><li><a href="client_doc_verify.php" title="Verify Documents">Verify Documents</a></li><?php } ?>
                        <?php if (in_array(202, $my_pages_sidebar)) { ?><li><a href="client_failed_sms_code.php" title="Failed SMS Code">Failed SMS Code</a></li><?php } ?>
                        <?php if (in_array(16, $my_pages_sidebar)) { ?><li><a href="client_bank_verify.php" title="Moderate Bank Account">Moderate Bank Account</a></li><?php } ?>
                        <?php if (in_array(259, $my_pages_sidebar)) { ?><li><a href="client_life.php" title="Download Client Information">Download Client Information</a></li><?php } ?>
                        <?php if (in_array(269, $my_pages_sidebar)) { ?><li><a href="client_retention_review.php" title="Client Retention Review">Client Retention Review</a></li><?php } ?>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-map fa-fw"></i> Client Category<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(17, $my_pages_sidebar)) { ?><li><a href="client_level_one.php" title="Level One Clients">Level 1 Clients</a></li><?php } ?>
                        <?php if (in_array(18, $my_pages_sidebar)) { ?><li><a href="client_level_two.php" title="Level Two Clients">Level 2 Clients</a></li><?php } ?>
                        <?php if (in_array(19, $my_pages_sidebar)) { ?><li><a href="client_level_three.php" title="Level Three Clients">Level 3 Clients</a></li><?php } ?>
                        <?php if (in_array(20, $my_pages_sidebar)) { ?><li><a href="client_unverified.php" title="Unverified Clients">Unverified Clients</a></li><?php } ?>
                        <?php if (in_array(21, $my_pages_sidebar)) { ?><li><a href="client_ilpr.php" title="ILPR Clients">View ILPR Clients</a></li><?php } ?>
                        <?php if (in_array(22, $my_pages_sidebar)) { ?><li><a href="client_non_ilpr.php" title="Non-ILPR Clients">View Non-ILPR Clients</a></li><?php } ?>
                        <?php if (in_array(12, $my_pages_sidebar)) { ?><li><a href="client_top_traders.php" title="View Top Traders">View Top Traders</a></li><?php } ?>
                        <?php if (in_array(244, $my_pages_sidebar)) { ?><li><a href="client_active.php" title="Active Trading Clients">Active Trading Clients</a></li><?php } ?>
                        <?php if (in_array(245, $my_pages_sidebar)) { ?><li><a href="client_inactive.php" title="Inactive Trading Clients">Inactive Trading Clients</a></li><?php } ?>
                        <?php if (in_array(245, $my_pages_sidebar)) { ?><li><a href="client_reactivated.php" title="Reactivated Trading Clients">Reactivated Trading Clients</a></li><?php } ?>
                        <?php if (in_array(12, $my_pages_sidebar)) { ?><li><a href="client_commission_17.php" title="Commission Clients 17">Commission Clients 17</a></li><?php } ?>
                        <?php if (in_array(275, $my_pages_sidebar)) { ?><li><a href="client_comm_platinum.php" title="Platinum Commission Clients">Platinum Commission Clients</a></li><?php } ?>
                        <?php if (in_array(276, $my_pages_sidebar)) { ?><li><a href="client_comm_gold.php" title="Gold Commission Clients">Gold Commission Clients</a></li><?php } ?>
                        <?php if (in_array(277, $my_pages_sidebar)) { ?><li><a href="client_comm_silver.php" title="Silver Commission Clients">Silver Commission Clients</a></li><?php } ?>
                        <?php if (in_array(278, $my_pages_sidebar)) { ?><li><a href="client_comm_bronze.php" title="Bronze Commission Clients">Bronze Commission Clients</a></li><?php } ?>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-euro"></i> Bonus Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(263, $my_pages_sidebar)) { ?><li><a href="bonus_accounts.php" title="Bonus Accounts">Active Bonus Accounts</a></li><?php } ?>
                        <?php if (in_array(264, $my_pages_sidebar)) { ?><li><a href="bonus_app_moderation.php" title="Moderate Bonus Applications">Moderate Bonus Applications</a></li><?php } ?>
                        <?php if (in_array(265, $my_pages_sidebar)) { ?><li><a href="bonus_list.php" title="Manage Bonus Packages">Manage Bonus Packages</a></li><?php } ?>
                        <?php if (in_array(266, $my_pages_sidebar)) { ?><li><a href="bonus_allocation.php" title="Process Bonus Allocation">Process Bonus Allocation</a></li><?php } ?>
                        <?php if (in_array(267, $my_pages_sidebar)) { ?><li><a href="bonus_defaulting_accounts.php" title="Review Defaulting Accounts">Review Defaulting Accounts</a></li><?php } ?>
                        <?php if (in_array(268, $my_pages_sidebar)) { ?><li><a href="bonus_successful_accounts.php" title="Review Successful Accounts">Review Successful Accounts</a></li><?php } ?>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-star-half-o fa-fw"></i> System Insights<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(23, $my_pages_sidebar)) { ?><li><a href="insight/last_month_funding.php" title="Last Month Funding">Last Month Funding</a></li><?php } ?>
                        <?php if (in_array(24, $my_pages_sidebar)) { ?><li><a href="insight/last_month_withdrawal.php" title="Last Month Withdrawal">Last Month Withdrawal</a></li><?php } ?>
                        <?php if (in_array(25, $my_pages_sidebar)) { ?><li><a href="insight/last_month_new_client.php" title="Last Month New Clients">Last Month New Clients</a></li><?php } ?>
                        <?php if (in_array(26, $my_pages_sidebar)) { ?><li><a href="insight/insight_training_campaign.php" title="Training Campaign">Training Campaign</a></li><?php } ?>
                        <?php if (in_array(26, $my_pages_sidebar)) { ?><li><a href="insight/insight_prospects.php" title="Prospects">Prospects</a></li><?php } ?>
                        <?php if (in_array(26, $my_pages_sidebar)) { ?><li><a href="insight/sales_client_interest.php" title="Sales - Client Interest">Sales - Client Interest</a></li><?php } ?>
                        <?php if (in_array(27, $my_pages_sidebar)) { ?><li><a href="insight/daily_funding.php" title="Daily Funding">Daily Funding</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tasks fa-fw"></i> Deposit Transactions<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(28, $my_pages_sidebar)) { ?><li><a href="deposit_add.php" title="Add Deposit">Deposit - Add</a></li><?php } ?>
                        <?php if (in_array(29, $my_pages_sidebar)) { ?><li><a href="deposit_search.php" title="Search Deposit Transactions">Deposit - Search</a></li><?php } ?>
                        <?php if (in_array(30, $my_pages_sidebar)) { ?><li><a href="deposit_pending.php" title="Pending Deposit">Deposit - Pending(All)</a></li><?php } ?>
                        <?php if (in_array(257, $my_pages_sidebar)) { ?><li><a href="deposit_pending_sorted.php" title="Pending Deposit">Deposit - Pending(Sorted)</a></li><?php } ?>
                        <?php if (in_array(31, $my_pages_sidebar)) { ?><li><a href="deposit_notified.php" title="Notified Deposit">Deposit - Notified</a></li><?php } ?>
                        <?php if (in_array(32, $my_pages_sidebar)) { ?><li><a href="deposit_confirmed.php" title="Confirmed Deposit">Deposit - Confirmed</a></li><?php } ?>
                        <?php if (in_array(234, $my_pages_sidebar)) { ?><li><a href="deposit_confirmed_view_only.php" title="Confirmed Deposit (View Only)">Deposit - Confirmed (View Only)</a></li><?php } ?>
                        <?php if (in_array(33, $my_pages_sidebar)) { ?><li><a href="deposit_completed.php" title="Completed Deposit">Deposit - Completed</a></li><?php } ?>
                        <?php if (in_array(34, $my_pages_sidebar)) { ?><li><a href="deposit_declined.php" title="Declined Deposit">Deposit - Declined</a></li><?php } ?>
                        <?php if (in_array(35, $my_pages_sidebar)) { ?><li><a href="deposit_failed.php" title="Failed Deposit">Deposit - Failed</a></li><?php } ?>
                        <?php if (in_array(36, $my_pages_sidebar)) { ?><li><a href="deposit_all.php" title="All Deposit Transactions">Deposit - All</a></li><?php } ?>
                        <?php if (in_array(37, $my_pages_sidebar)) { ?><li><a href="transaction_calculator.php" title="Transaction Calculator">Transaction Calculator</a></li><?php } ?>
                        <?php if (in_array(38, $my_pages_sidebar)) { ?><li><a href="deposit_reversal.php" title="Reverse Transaction">Deposit - Reversal</a></li><?php } ?>
                        <?php if (in_array(261, $my_pages_sidebar)) { ?><li><a href="locked_transactions.php"> Review Locked Transaction</a></li><?php } ?>
                        <?php if (in_array(281, $my_pages_sidebar)) { ?><li><a href="deposit_refund_initiated.php"> Deposit Refund - Initiated</a></li><?php } ?>
                        <?php if (in_array(282, $my_pages_sidebar)) { ?><li><a href="deposit_refund_pending.php"> Deposit Refund - Pending</a></li><?php } ?>
                        <?php if (in_array(283, $my_pages_sidebar)) { ?><li><a href="deposit_refund_approve.php"> Deposit Refund - Approve</a></li><?php } ?>
                        <?php if (in_array(284, $my_pages_sidebar)) { ?><li><a href="deposit_refund_completed.php"> Deposit Refund - Completed</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> Withdrawal Transactions<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(40, $my_pages_sidebar)) { ?><li><a href="withdrawal_search.php" title="Search Withdrawal">Withdrawal - Search</a></li><?php } ?>
                        <?php if (in_array(41, $my_pages_sidebar)) { ?><li><a href="withdrawal_initiated.php" title="Initiated Withdrawal">Withdrawal - Initiated</a></li><?php } ?>
                        <?php if (in_array(42, $my_pages_sidebar)) { ?><li><a href="withdrawal_confirmed.php" title="Confirmed Withdrawal">Withdrawal - Confirmed</a></li><?php } ?>
                        <?php if (in_array(235, $my_pages_sidebar)) { ?><li><a href="withdrawal_confirmed_view_only.php" title="Confirmed Withdrawal (View Only)">Withdrawal - Confirmed (View Only)</a></li><?php } ?>
                        <?php if (in_array(43, $my_pages_sidebar)) { ?><li><a href="withdrawal_ifx_debited.php" title="IFX Debited Withdrawal">Withdrawal - IFX Debited</a></li><?php } ?>
                        <?php if (in_array(44, $my_pages_sidebar)) { ?><li><a href="withdrawal_completed.php" title="Completed Withdrawal">Withdrawal - Completed</a></li><?php } ?>
                        <?php if (in_array(45, $my_pages_sidebar)) { ?><li><a href="withdrawal_declined.php" title="Declined/Failed Withdrawal">Withdrawal - Declined/Failed</a></li><?php } ?>
                        <?php if (in_array(46, $my_pages_sidebar)) { ?><li><a href="withdrawal_all.php" title="All Withdrawal Transactions">Withdrawal - All</a></li><?php } ?>
                        <?php if (in_array(279, $my_pages_sidebar)) { ?><li><a href="withdrawal_reversal.php" title="Withdrawal Reversal">Withdrawal - Reversal</a></li><?php } ?>
                        <?php if (in_array(47, $my_pages_sidebar)) { ?><li><a href="transaction_calculator.php" title="Transaction Calculator">Transaction Calculator</a></li><?php } ?>
                        <?php if (in_array(261, $my_pages_sidebar)) { ?><li><a href="locked_transactions.php"> Review Locked Transaction</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-binoculars fa-fw"></i> Compliance<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(285, $my_pages_sidebar)) { ?><li><a href="compliance/first_time_transaction_initiated.php">First Time Transaction - Initiated</a></li><?php } ?>
                        <?php if (in_array(286, $my_pages_sidebar)) { ?><li><a href="compliance/first_time_transaction_completed.php">First Time Transaction - Reviewed</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cutlery fa-fw"></i> Events<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(48, $my_pages_sidebar)) { ?><li><a href="dinner/new_reg.php" title="New Registration">New Dinner 2016 Reg</a></li><?php } ?>
                        <?php if (in_array(49, $my_pages_sidebar)) { ?><li><a href="dinner/all_reg.php" title="">All Dinner 2016 Reg</a></li><?php } ?>
                        <?php if (in_array(50, $my_pages_sidebar)) { ?><li><a href="event/lekki_office_warming_all.php" title="">All Lekki Office Warming Reg</a></li><?php } ?>
                        <?php if (in_array(50, $my_pages_sidebar)) { ?><li><a href="event/pencil_unbroken.php" title="Pencil Unbroken Registration">Pencil Unbroken Reg</a></li><?php } ?>
                        <?php if (in_array(211, $my_pages_sidebar)) { ?><li><a href="independence_quiz_results.php" title="Independence Quiz Results">2017 Independence Quiz Results</a></li><?php } ?>
                        <?php if (in_array(211, $my_pages_sidebar)) { ?><li><a href="q2_promo_results.php" title="Q2 Promo Results">2018 Q2 Promo Results</a></li><?php } ?>
                        <?php if (in_array(228, $my_pages_sidebar)) { ?><li><a href="dinner_2017_new_reg.php" title="New Dinner 2017 Reg">New Dinner 2017 Reg</a></li><?php } ?>
                        <?php if (in_array(229, $my_pages_sidebar)) { ?><li><a href="dinner_2017_all_reg.php" title="All Dinner 2017 Reg">All Dinner 2017 Reg</a></li><?php } ?>
                        <?php if (in_array(230, $my_pages_sidebar)) { ?><li><a href="dinner_2017_signin.php" title="Dinner Sign In">Dinner Sign In</a></li><?php } ?>

                        <?php //if (in_array(230, $my_pages_sidebar)) { ?><li><a href="rma_lfc.php" title="Predictions">Predictions</a></li><?php //} ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-gears fa-fw"></i> Project Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(209, $my_pages_sidebar)) { ?><li><a href="project_management_all_projects.php" title="All Projects">All Projects</a></li><?php } ?>
                        <?php if (in_array(203, $my_pages_sidebar)) { ?><li><a href="project_management_projects.php" title="Projects">Projects</a></li><?php } ?>

                        <?php if (in_array(209, $my_pages_sidebar)) { ?><li><a href="project_management_all_archived_projects.php" title="All Projects">All Archived Projects</a></li><?php } ?>
                        <?php if (in_array(203, $my_pages_sidebar)) { ?><li><a href="project_management_archived_projects.php" title="Projects">Archived Projects</a></li><?php } ?>
                        <!--                        --><?php //if (in_array(204, $my_pages_sidebar)) { ?><!--<li><a href="project_management_project_report.php" title="Project Reports">Project Reports</a></li>--><?php //} ?>
                        <?php /*if (in_array(205, $my_pages_sidebar)) { */?><!--<li><a href="project_management_confirmation_requests.php" title="Confirmation Requests">Confirmation Requests</a></li>--><?php /*} */?>
                        <?php if (in_array(210, $my_pages_sidebar)) { ?><li><a href="project_management_all_messages.php" title="All Messaging Boards">All Messaging Boards</a></li><?php } ?>

                        <?php //if (in_array(206, $my_pages_sidebar)) { ?><!--<li><a href="project_management_messages.php" title="Messaging Board">Messaging Board</a></li>--><?php //} ?>
                        <?php if (in_array(207, $my_pages_sidebar)) { ?><li><a href="project_management_reminder_add.php" title="Add New Reminder" >Add New Reminder</a></li><?php } ?>
                        <?php if (in_array(208, $my_pages_sidebar)) { ?><li><a href="project_management_reminder_manage.php" title="Manage Reminders">Manage Reminders</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-suitcase fa-fw"></i> Partner Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(51, $my_pages_sidebar)) { ?><li><a href="partner_new.php">New Partners</a></li><?php } ?>
                        <?php if (in_array(51, $my_pages_sidebar)) { ?><li><a href="partner_view.php">View Partners</a></li><?php } ?>
                        <?php if (in_array(52, $my_pages_sidebar)) { ?><li><a href="partner_credentials.php">Approve Credentials</a></li><?php } ?>
                        <?php if (in_array(55, $my_pages_sidebar)) { ?><li><a href="partner_pending_payout.php">Pending Payout</a></li><?php } ?>
                        <?php if (in_array(56, $my_pages_sidebar)) { ?><li><a href="partner_payout_history.php">Payout History</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gift fa-fw"></i> Rewards<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(57, $my_pages_sidebar)) { ?><li><a href="val_contest.php">Val Contest 2017</a></li><?php } ?>
                        <?php if (in_array(57, $my_pages_sidebar)) { ?><li><a href="upload_commission.php">Upload Trading Commission</a></li><?php } ?>
                        <?php if (in_array(58, $my_pages_sidebar)) { ?><li><a href="commission_upload_log.php">Commission Upload Log</a></li><?php } ?>
                        <?php if (in_array(59, $my_pages_sidebar)) { ?><li><a href="commission_view.php">View Commissions</a></li><?php } ?>
                        <?php if (in_array(60, $my_pages_sidebar)) { ?><li><a href="rewards_report.php">Reports</a></li><?php } ?>
                        <?php if (in_array(61, $my_pages_sidebar)) { ?><li><a href="loyalty_rank_archive.php">Loyalty Rank Archive</a></li><?php } ?>
                        <?php if (in_array(62, $my_pages_sidebar)) { ?><li><a href="loyalty_point_claimed.php">Loyalty Point Claimed</a></li><?php } ?>
                        <?php if (in_array(233, $my_pages_sidebar)) { ?><li><a href="client_expired_points.php">Expired Loyalty Point</a></li><?php } ?>
                        <?php if (in_array(262, $my_pages_sidebar)) { ?><li><a href="loyalty_current_rank.php">Loyalty Current Rank</a></li><?php } ?>
                        <?php if (in_array(272, $my_pages_sidebar)) { ?><li><a href="independence_contest.php">Independence Contest 2018</a></li><?php } ?>
                        <?php if (in_array(272, $my_pages_sidebar)) { ?><li><a href="the_splurge.php">Black Friday 2018</a></li><?php } ?>
                        <?php if (in_array(272, $my_pages_sidebar)) { ?><li><a href="black_friday_splurge_participants.php">Black Friday - Participants</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book fa-fw"></i> Education Service<b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <?php if (in_array(63, $my_pages_sidebar)) { ?><li><a href="edu_course.php">All Courses</a></li><?php } ?>
                        <?php if (in_array(64, $my_pages_sidebar)) { ?><li><a href="edu_support_ticket.php">Course Messages</a></li><?php } ?>
                        <?php if (in_array(65, $my_pages_sidebar)) { ?><li><a href="edu_student.php">All Students</a></li><?php } ?>
                        <?php if (in_array(66, $my_pages_sidebar)) { ?><li><a href="edu_student_category_0.php">Category 0 Students</a></li><?php } ?>
                        <?php if (in_array(66, $my_pages_sidebar)) { ?><li><a href="edu_student_category_1.php">Category 1 Students</a></li><?php } ?>
                        <?php if (in_array(236, $my_pages_sidebar)) { ?><li><a href="edu_student_category_2.php">Category 2 Students</a></li><?php } ?>
                        <?php if (in_array(237, $my_pages_sidebar)) { ?><li><a href="edu_student_category_3.php">Category 3 Students</a></li><?php } ?>
                        <?php if (in_array(238, $my_pages_sidebar)) { ?><li><a href="edu_student_category_4.php">Category 4 Students</a></li><?php } ?>
                        <li class="divider"></li>
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_free_training_reg.php">Training Campaign - New Reg</a></li><?php } ?>
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_free_training.php">Training Campaign</a></li><?php } ?>
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_client_training_funded.php">Training Campaign Funded</a></li><?php } ?>
                        <?php if (in_array(74, $my_pages_sidebar)) { ?><li><a href="edu_forum_reg.php">Forum Registration</a></li><?php } ?>
                        <?php if (in_array(247, $my_pages_sidebar)) { ?><li><a href="traders_forum_schedule.php">Schedule Traders Forum</a></li><?php } ?>
                        <?php if (in_array(280, $my_pages_sidebar)) { ?><li><a href="training_schedule.php">Training Schedule</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book fa-fw"></i> Education Transactions<b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <?php if (in_array(67, $my_pages_sidebar)) { ?><li><a href="edu_deposit_initiated.php">Deposit Initiated</a></li><?php } ?>
                        <?php if (in_array(68, $my_pages_sidebar)) { ?><li><a href="edu_deposit_notified.php">Deposit Notified</a></li><?php } ?>
                        <?php if (in_array(69, $my_pages_sidebar)) { ?><li><a href="edu_deposit_completed.php">Deposit Completed</a></li><?php } ?>
                        <?php if (in_array(70, $my_pages_sidebar)) { ?><li><a href="edu_deposit_declined.php">Deposit Declined</a></li><?php } ?>
                        <?php if (in_array(71, $my_pages_sidebar)) { ?><li><a href="edu_deposit_failed.php">Deposit Failed</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-newspaper-o fa-fw"></i> Article Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(75, $my_pages_sidebar)) { ?><li><a href="article_add.php">Add Article</a></li><?php } ?>
                        <?php if (in_array(76, $my_pages_sidebar)) { ?><li><a href="article_manage.php">Manage Article</a></li><?php } ?>
                        <?php if (in_array(75, $my_pages_sidebar)) { ?><li><a href="recent_comments.php">Recent Comments</a></li><?php } ?>
                        <?php if (in_array(76, $my_pages_sidebar)) { ?><li><a href="article_comments_history.php">Comments History</a></li><?php } ?>
                        <?php if (in_array(76, $my_pages_sidebar)) { ?><li><a href="list_of_visitors.php">List Of Visitors</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear fa-fw"></i> System<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(79, $my_pages_sidebar)) { ?><li><a href="system_message.php">System Messages</a></li><?php } ?>
                        <?php if (in_array(239, $my_pages_sidebar)) { ?><li><a href="system_activity_logs.php"> System Activity Logs</a></li><?php } ?>
                        <?php if (in_array(243, $my_pages_sidebar)) { ?><li><a href="system_ad_section.php"> Front-End Advert Section</a></li><?php } ?>
                        <?php if (in_array(258, $my_pages_sidebar)) { ?><li><a href="sms_records.php"> SMS Records</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="referral_links.php" ><i class="glyphicon glyphicon-link"></i> Instaforex Foreign Links</a>
                </li>
                <?php /*if (in_array(79, $my_pages_sidebar)) { */?><!--<li><a href="system_message.php" title="System Messages"><i class="fa fa-envelope fa-fw"></i> System Messages</a></li>--><?php /*} */?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bookmark fa-fw"></i> Campaign<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(80, $my_pages_sidebar)) { ?><li><a href="campaign_solo_group_new.php">New Campaign Solo Group</a></li><?php } ?>
                        <?php if (in_array(81, $my_pages_sidebar)) { ?><li><a href="campaign_solo_group_all.php">All Campaign Solo Group</a></li><?php } ?>
                        <?php if (in_array(82, $my_pages_sidebar)) { ?><li><a href="campaign_solo_view.php">New Solo Campaign</a></li><?php } ?>
                        <?php if (in_array(83, $my_pages_sidebar)) { ?><li><a href="campaign_solo_all.php">All Solo Campaign</a></li><?php } ?>
                        <div class="divider"></div>
                        <!-- 84 -->
                        <?php if (in_array(85, $my_pages_sidebar)) { ?><li><a href="campaign_new_category.php">New Category</a></li><?php } ?>
                        <?php if (in_array(86, $my_pages_sidebar)) { ?><li><a href="campaign_all_category.php">All Categories</a></li><?php } ?>
                        <?php if (in_array(87, $my_pages_sidebar)) { ?><li><a href="campaign_sms.php">Compose SMS</a></li><?php } ?>
                        <?php if (in_array(232, $my_pages_sidebar)) { ?><li><a href="campaign_sms_single.php">Compose Single SMS</a></li><?php } ?>
                        <?php if (in_array(88, $my_pages_sidebar)) { ?><li><a href="campaign_email.php">Compose Email</a></li><?php } ?>
                        <?php if (in_array(87, $my_pages_sidebar)) { ?><li><a href="_campaign_push_single.php">Compose Push Notification</a></li><?php } ?>
                        <?php if (in_array(89, $my_pages_sidebar)) { ?><li><a href="campaign_email_view.php">Email Campaign</a></li><?php } ?>
                        <?php if (in_array(90, $my_pages_sidebar)) { ?><li><a href="campaign_sms_view.php">SMS Campaign</a></li><?php } ?>
                        <?php if (in_array(91, $my_pages_sidebar)) { ?><li><a href="campaign_sales.php">Sales Management</a></li><?php } ?>
                        <?php if (in_array(270, $my_pages_sidebar)) { ?><li><a href="notification_manage.php">Manage Notification</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-fw"></i> Campaign Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(255, $my_pages_sidebar)) { ?><li><a href="campaign_leads.php">Campaign Leads</a></li><?php } ?>
                        <?php if (in_array(256, $my_pages_sidebar)) { ?><li><a href="campaign_analytics.php">Campaign Analytics</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-graduation-cap fa-fw"></i> Careers<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(92, $my_pages_sidebar)) { ?><li><a href="career_new_job.php">New Job</a></li><?php } ?>
                        <?php if (in_array(93, $my_pages_sidebar)) { ?><li><a href="career_all_job.php">All Jobs</a></li><?php } ?>
                        <?php if (in_array(94, $my_pages_sidebar)) { ?><li><a href="career_all_applications.php">Application - All</a></li><?php } ?>
                        <?php if (in_array(95, $my_pages_sidebar)) { ?><li><a href="career_app_submit.php">Application - Submitted</a></li><?php } ?>
                        <?php if (in_array(96, $my_pages_sidebar)) { ?><li><a href="career_app_review.php">Application - Review</a></li><?php } ?>
                        <?php if (in_array(97, $my_pages_sidebar)) { ?><li><a href="career_app_interview.php">Application - Interview</a></li><?php } ?>
                        <?php if (in_array(98, $my_pages_sidebar)) { ?><li><a href="career_app_employ.php">Application - Employed</a></li><?php } ?>
                        <?php if (in_array(98, $my_pages_sidebar)) { ?><li><a href="career_app_reject.php">Application - Rejected</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear fa-fw"></i> System Settings<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(100, $my_pages_sidebar)) { ?><li><a href="settings_rates_log_view.php" title="View Exchange Rates Log">View Exchange Rates Log</a></li><?php } ?>
                        <?php if (in_array(100, $my_pages_sidebar)) { ?><li><a href="settings_rates_log.php" title="Log Exchange Rate">Log Exchange Rate</a></li><?php } ?>
                        <?php if (in_array(101, $my_pages_sidebar)) { ?><li><a href="settings_rates.php" title="Rates Settings">Rates Settings</a></li><?php } ?>
                        <?php if (in_array(102, $my_pages_sidebar)) { ?><li><a href="settings_schedules.php" title="Schedules Settings">Schedules Settings</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-line-chart fa-fw"></i> Performance Reports<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(103, $my_pages_sidebar)) { ?><li><a href="active_client.php" title="Active Clients">Active Clients</a></li><?php } ?>
                        <?php if (in_array(104, $my_pages_sidebar)) { ?><li><a href="report_service_charge.php" title="Service Charge Report">Service Charges</a></li><?php } ?>
                        <?php if (in_array(105, $my_pages_sidebar)) { ?><li><a href="report_vat_charge.php" title="VAT Charge Report">VAT Charges</a></li><?php } ?>
                        <?php if (in_array(106, $my_pages_sidebar)) { ?><li><a href="report_saldo.php" title="Saldo Reports">Saldo Reports</a></li><?php } ?>
                        <?php if (in_array(107, $my_pages_sidebar)) { ?><li><a href="report_commission.php" title="Commission Reports">Commission Reports</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-square fa-fw"></i> Signal Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(77, $my_pages_sidebar)) { ?><li><a href="signal_daily.php">Update Daily Signal</a></li><?php } ?>
                        <?php if (in_array(78, $my_pages_sidebar)) { ?><li><a href="signal_review.php">Signal Analysis</a></li><?php } ?>
                        <?php if (in_array(78, $my_pages_sidebar)) { ?><li><a href="signal_users.php">Signal Users</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-fw"></i> Prospect<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(110, $my_pages_sidebar)) { ?><li><a href="prospect_source.php">Manage Prospect Source</a></li><?php } ?>
                        <?php if (in_array(111, $my_pages_sidebar)) { ?><li><a href="prospect_add.php">Add New Prospect</a></li><?php } ?>
                        <?php if (in_array(112, $my_pages_sidebar)) { ?><li><a href="prospect_manage.php">Manage Prospects</a></li><?php } ?>
                        <?php if (in_array(112, $my_pages_sidebar)) { ?><li><a href="prospect_manage_funded.php">Manage Prospects - Funded</a></li><?php } ?>
                        <?php if (in_array(112, $my_pages_sidebar)) { ?><li><a href="prospect_manage_training.php">Manage Prospects - Training</a></li><?php } ?>
                        <?php if (in_array(246, $my_pages_sidebar)) { ?><li><a href="prospect_ilpr_manage.php">Manage ILPR Prospects</a></li><?php } ?>
                        <div class="divider"></div>
                        <?php if (in_array(248, $my_pages_sidebar)) { ?><li><a href="prospect_fb_leads.php">Manage Facebook Leads</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-expand fa-fw"></i> Customer Care<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(214, $my_pages_sidebar)) { ?><li><a href="customer_care_log_add.php">Add New Log</a></li><?php } ?>
                        <?php if (in_array(215, $my_pages_sidebar)) { ?><li><a href="customer_care_admin_log_manage.php">Manage Logs</a></li><?php } ?>
                        <?php if (in_array(216, $my_pages_sidebar)) { ?><li><a href="customer_care_all_log_manage.php">Manage All Logs</a></li><?php } ?>
                        <?php if (in_array(249, $my_pages_sidebar)) { ?><li><a href="operations_log.php">Operations Issue Log</a></li><?php } ?>
                        <?php if (in_array(250, $my_pages_sidebar)) { ?><li><a href="operations_log_archive.php">Closed Operations Issues</a></li><?php } ?>
                        <?php if (in_array(273, $my_pages_sidebar)) { ?><li><a href="walk_in_client_add.php">Add Walk-In Client</a></li><?php } ?>
                        <?php if (in_array(274, $my_pages_sidebar)) { ?><li><a href="walk_in_client_list.php">All Walk-In Clients</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-envelope fa-fw"></i> Support Mails<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(217, $my_pages_sidebar)) { ?><li><a href="support_email_compose.php" title="Compose Email">Compose Email</a></li><?php } ?>
                        <?php if (in_array(218, $my_pages_sidebar)) { ?><li><a href="support_email_inbox.php" title="Inbox">Inbox</a></li><?php } ?>
                        <?php if (in_array(219, $my_pages_sidebar)) { ?><li><a href="support_email_sent_box.php" title="Sent Box">Sent Box</a></li><?php } ?>
                        <?php if (in_array(220, $my_pages_sidebar)) { ?><li><a href="support_email_assigned.php" title="Inbox">Assigned Emails</a></li><?php } ?>
                        <?php if (in_array(221, $my_pages_sidebar)) { ?><li><a href="support_email_drafts.php" title="Drafts">Drafts</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-money fa-fw"></i> Requisition Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(222, $my_pages_sidebar)) { ?><li><a href="accounting_system_requisition_form.php" title="Requisition Form">Requisition Form</a></li><?php } ?>
                        <?php if (in_array(222, $my_pages_sidebar)) { ?><li><a href="accounting_system_requisition_orders.php" title="Requisition Orders">Requisition Orders</a></li><?php } ?>
                        <?php if (in_array(223, $my_pages_sidebar)) { ?><li><a href="accounting_system_confirmation_requests.php" title="Confirmation Requests">Confirmation Requests</a></li><?php } ?>
                        <?php if (in_array(224, $my_pages_sidebar)) { ?><li><a href="accounting_system_cashiers_desk.php" title="Cashiers Desk">Cashiers Desk</a></li><?php } ?>
                        <?php if (in_array(225, $my_pages_sidebar)) { ?><li><a href="accounting_system_req_reports.php" title="Requisition Report">Requisition Report</a></li><?php } ?>
                        <?php if (in_array(226, $my_pages_sidebar)) { ?><li><a href="accounting_system_settings.php" title="Requisition Report">System Settings</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-fw"></i> HR Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(231, $my_pages_sidebar)) { ?><li><a href="hr_attendance_system_logs.php" title="Attendance Logs">Attendance Logs</a></li><?php } ?>
                        <?php if (in_array(253, $my_pages_sidebar)) { ?><li><a href="rms.php" title="Reports Management">Reports Management</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-institution fa-fw"></i> Facility Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(240, $my_pages_sidebar)) { ?><li><a href="facility_manager.php" title="Inventory">Inventory</a></li><?php } ?>
                        <?php if (in_array(241, $my_pages_sidebar)) { ?><li><a href="facility_admin.php" title="Facility_Admin">Facility Admin</a></li><?php } ?>
                        <?php if (in_array(242, $my_pages_sidebar)) { ?><li><a href="facility_user.php" title="Facility_User">Facility User</a></li><?php } ?>
                    </ul>
                </li>
                <li><a href="logout.php" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
            </ul>
        </div>
    </nav>
</div>