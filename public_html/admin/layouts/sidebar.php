<?php
// Prepare admin full name
if(isset($_SESSION['admin_first_name']) && isset($_SESSION['admin_last_name'])) {
    $admin_full_name = $_SESSION['admin_first_name'] . " " . $_SESSION['admin_last_name'];
} else {
    $admin_full_name = "";
}

$my_pages_sidebar = $admin_object->get_privileges($_SESSION['admin_unique_code']);
$my_pages_sidebar = explode(",", $my_pages_sidebar['allowed_pages']);

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
                        <?php if (in_array(12, $my_pages_sidebar)) { ?><li><a href="client_view.php" title="View Clients">View Clients</a></li><?php } ?>
                        <?php if (in_array(201, $my_pages_sidebar)) { ?><li><a href="client_delete.php" title="Delete Client">Delete Client</a></li><?php } ?>
                        <?php if (in_array(13, $my_pages_sidebar)) { ?><li><a href="client_moderate_account.php" title="Moderate IFX Account">Moderate IFX Account</a></li><?php } ?>
                        <?php if (in_array(14, $my_pages_sidebar)) { ?><li><a href="client_update_account.php" title="Update IFX Account">Update IFX Account</a></li><?php } ?>
                        <?php if (in_array(15, $my_pages_sidebar)) { ?><li><a href="client_doc_verify.php" title="Verify Documents">Verify Documents</a></li><?php } ?>
                        <?php if (in_array(202, $my_pages_sidebar)) { ?><li><a href="client_failed_sms_code.php" title="Failed SMS Code">Failed SMS Code</a></li><?php } ?>
                        <?php if (in_array(16, $my_pages_sidebar)) { ?><li><a href="client_bank_verify.php" title="Moderate Bank Account">Moderate Bank Account</a></li><?php } ?>
                        <?php if (in_array(17, $my_pages_sidebar)) { ?><li><a href="client_level_one.php" title="Level One Clients">Level 1 Clients</a></li><?php } ?>
                        <?php if (in_array(18, $my_pages_sidebar)) { ?><li><a href="client_level_two.php" title="Level Two Clients">Level 2 Clients</a></li><?php } ?>
                        <?php if (in_array(19, $my_pages_sidebar)) { ?><li><a href="client_level_three.php" title="Level Three Clients">Level 3 Clients</a></li><?php } ?>
                        <?php if (in_array(20, $my_pages_sidebar)) { ?><li><a href="client_unverified.php" title="Unverified Clients">Unverified Clients</a></li><?php } ?>
                        <?php if (in_array(21, $my_pages_sidebar)) { ?><li><a href="client_ilpr.php" title="ILPR Clients">View ILPR Clients</a></li><?php } ?>
                        <?php if (in_array(22, $my_pages_sidebar)) { ?><li><a href="client_non_ilpr.php" title="Non-ILPR Clients">View Non-ILPR Clients</a></li><?php } ?>
                        <?php if (in_array(12, $my_pages_sidebar)) { ?><li><a href="client_top_traders.php" title="View Top Traders">View Top Traders</a></li><?php } ?>
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
                        <?php if (in_array(30, $my_pages_sidebar)) { ?><li><a href="deposit_pending.php" title="Pending Deposit">Deposit - Pending</a></li><?php } ?>
                        <?php if (in_array(31, $my_pages_sidebar)) { ?><li><a href="deposit_notified.php" title="Notified Deposit">Deposit - Notified</a></li><?php } ?>
                        <?php if (in_array(32, $my_pages_sidebar)) { ?><li><a href="deposit_confirmed.php" title="Confirmed Deposit">Deposit - Confirmed</a></li><?php } ?>
                        <?php if (in_array(33, $my_pages_sidebar)) { ?><li><a href="deposit_completed.php" title="Completed Deposit">Deposit - Completed</a></li><?php } ?>
                        <?php if (in_array(34, $my_pages_sidebar)) { ?><li><a href="deposit_declined.php" title="Declined Deposit">Deposit - Declined</a></li><?php } ?>
                        <?php if (in_array(35, $my_pages_sidebar)) { ?><li><a href="deposit_failed.php" title="Failed Deposit">Deposit - Failed</a></li><?php } ?>
                        <?php if (in_array(36, $my_pages_sidebar)) { ?><li><a href="deposit_all.php" title="All Deposit Transactions">Deposit - All</a></li><?php } ?>
                        <?php if (in_array(37, $my_pages_sidebar)) { ?><li><a href="transaction_calculator.php" title="Transaction Calculator">Transaction Calculator</a></li><?php } ?>
                        <?php if (in_array(38, $my_pages_sidebar)) { ?><li><a href="deposit_reversal.php" title="Reverse Transaction">Deposit - Reversal</a></li><?php } ?>
<!--                        --><?php //if (in_array(39, $my_pages_sidebar)) { ?><!--<li><a href="deposit_all_bonus.php" title="All Bonus Accounts">Deposit - All Bonus</a></li>--><?php //} ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars fa-fw"></i> Withdrawal Transactions<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(40, $my_pages_sidebar)) { ?><li><a href="withdrawal_search.php" title="Search Withdrawal">Withdrawal - Search</a></li><?php } ?>
                        <?php if (in_array(41, $my_pages_sidebar)) { ?><li><a href="withdrawal_initiated.php" title="Initiated Withdrawal">Withdrawal - Initiated</a></li><?php } ?>
                        <?php if (in_array(42, $my_pages_sidebar)) { ?><li><a href="withdrawal_confirmed.php" title="Confirmed Withdrawal">Withdrawal - Confirmed</a></li><?php } ?>
                        <?php if (in_array(43, $my_pages_sidebar)) { ?><li><a href="withdrawal_ifx_debited.php" title="IFX Debited Withdrawal">Withdrawal - IFX Debited</a></li><?php } ?>
                        <?php if (in_array(44, $my_pages_sidebar)) { ?><li><a href="withdrawal_completed.php" title="Completed Withdrawal">Withdrawal - Completed</a></li><?php } ?>
                        <?php if (in_array(45, $my_pages_sidebar)) { ?><li><a href="withdrawal_declined.php" title="Declined/Failed Withdrawal">Withdrawal - Declined/Failed</a></li><?php } ?>
                        <?php if (in_array(46, $my_pages_sidebar)) { ?><li><a href="withdrawal_all.php" title="All Withdrawal Transactions">Withdrawal - All</a></li><?php } ?>
                        <?php if (in_array(47, $my_pages_sidebar)) { ?><li><a href="transaction_calculator.php" title="Transaction Calculator">Transaction Calculator</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cutlery fa-fw"></i> Events<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(48, $my_pages_sidebar)) { ?><li><a href="dinner/new_reg.php" title="New Registration">New Dinner 2016 Reg</a></li><?php } ?>
                        <?php if (in_array(49, $my_pages_sidebar)) { ?><li><a href="dinner/all_reg.php" title="">All Dinner 2016 Reg</a></li><?php } ?>
                        <?php if (in_array(50, $my_pages_sidebar)) { ?><li><a href="event/lekki_office_warming_all.php" title="">All Lekki Office Warming Reg</a></li><?php } ?>
                        <?php if (in_array(50, $my_pages_sidebar)) { ?><li><a href="event/pencil_unbroken.php" title="Pencil Unbroken Registration">Pencil Unbroken Reg</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-gears fa-fw"></i> Project Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php //if (in_array(48, $my_pages_sidebar)) { ?><li><a href="project_management_projects.php" title="Projects">Projects</a></li><?php //} ?>
                        <?php //if (in_array(48, $my_pages_sidebar)) { ?><li><a href="project_management_project_report.php" title="Assigned Tasks">Project Reports</a></li><?php //} ?>
                        <?php //if (in_array(48, $my_pages_sidebar)) { ?><li><a href="project_management_confirmation_requests.php" title="Confirmation Requests">Confirmation Requests</a></li><?php //} ?>
                        <?php //if (in_array(48, $my_pages_sidebar)) { ?><li><a href="project_management_messages.php" title="Messaging Board">Messaging Board</a></li><?php //} ?>
                        <?php //if (in_array(77, $my_pages_sidebar)) { ?><li><a href="project_management_reminder_add.php">Add New Reminder</a></li><?php //} ?>
                        <?php //if (in_array(78, $my_pages_sidebar)) { ?><li><a href="project_management_reminder_manage.php">Manage Reminders</a></li><?php //} ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" fa fa-envelope fa-fw"></i> Support Mails<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php //if (in_array(48, $my_pages_sidebar)) { ?><li><a href="support_email_compose.php" title="Compose Email">Compose Email</a></li><?php //} ?>
                        <?php //if (in_array(49, $my_pages_sidebar)) { ?><li><a href="support_email_inbox.php" title="Inbox">Inbox</a></li><?php //} ?>
                        <?php //if (in_array(50, $my_pages_sidebar)) { ?><li><a href="support_email_sent_box.php" title="Sent Box">Sent Box</a></li><?php //} ?>
                        <?php //if (in_array(49, $my_pages_sidebar)) { ?><li><a href="support_email_assigned.php" title="Inbox">Assigned Emails</a></li><?php //} ?>
                        <?php //if (in_array(50, $my_pages_sidebar)) { ?><li><a href="support_email_drafts.php" title="Drafts">Drafts</a></li><?php //} ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-suitcase fa-fw"></i> Partner Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(51, $my_pages_sidebar)) { ?><li><a href="partner_view.php">View Partners</a></li><?php } ?>
                        <?php if (in_array(52, $my_pages_sidebar)) { ?><li><a href="partner_update.php">Update Partners</a></li><?php } ?>
                        <?php if (in_array(53, $my_pages_sidebar)) { ?><li><a href="paypartners.php">Pay Partners</a></li><?php } ?>
                        <?php if (in_array(54, $my_pages_sidebar)) { ?><li><a href="partner_set_commission.php">Set Commission Rate</a></li><?php } ?>
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
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book fa-fw"></i> Education Service<b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <?php if (in_array(63, $my_pages_sidebar)) { ?><li><a href="edu_course.php">All Courses</a></li><?php } ?>
                        <?php if (in_array(64, $my_pages_sidebar)) { ?><li><a href="edu_support_ticket.php">Course Messages</a></li><?php } ?>
                        <?php if (in_array(65, $my_pages_sidebar)) { ?><li><a href="edu_payment.php">Student Payments</a></li><?php } ?>
                        <?php if (in_array(66, $my_pages_sidebar)) { ?><li><a href="edu_deposit.php">Student Deposits</a></li><?php } ?>
                        <?php if (in_array(63, $my_pages_sidebar)) { ?><li><a href="edu_student.php">All Students</a></li><?php } ?>
                        <li class="divider"></li>
                        <!-- // 66 - 72 -->
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_free_training_reg.php">Training Campaign - New Reg</a></li><?php } ?>
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_free_training.php">Training Campaign</a></li><?php } ?>
                        <?php if (in_array(73, $my_pages_sidebar)) { ?><li><a href="edu_client_training_funded.php">Training Campaign Funded</a></li><?php } ?>
                        <?php if (in_array(74, $my_pages_sidebar)) { ?><li><a href="edu_forum_reg.php">Forum Registration</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-newspaper-o fa-fw"></i> Article Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(75, $my_pages_sidebar)) { ?><li><a href="article_add.php">Add Article</a></li><?php } ?>
                        <?php if (in_array(76, $my_pages_sidebar)) { ?><li><a href="article_manage.php">Manage Article</a></li><?php } ?>
                        <?php if (in_array(75, $my_pages_sidebar)) { ?><li><a href="recent_comments.php">Recent Comments</a></li><?php } ?>
                        <?php if (in_array(76, $my_pages_sidebar)) { ?><li><a href="list_of_visitors.php">List Of Visitors</a></li><?php } ?>
                    </ul>
                </li>
                <?php if (in_array(79, $my_pages_sidebar)) { ?><li><a href="system_message.php" title="System Messages"><i class="fa fa-envelope fa-fw"></i> System Messages</a></li><?php } ?>
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
                        <?php if (in_array(88, $my_pages_sidebar)) { ?><li><a href="campaign_email.php">Compose Email</a></li><?php } ?>
                        <?php if (in_array(89, $my_pages_sidebar)) { ?><li><a href="campaign_email_view.php">Email Campaign</a></li><?php } ?>
                        <?php if (in_array(90, $my_pages_sidebar)) { ?><li><a href="campaign_sms_view.php">SMS Campaign</a></li><?php } ?>
                        <?php if (in_array(91, $my_pages_sidebar)) { ?><li><a href="campaign_sales.php">Sales Management</a></li><?php } ?>
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
<!--                        --><?php //if (in_array(108, $my_pages_sidebar)) { ?><!--<li><a href="report_funding.php" title="Funding Reports">Funding Reports</a></li>--><?php //} ?>
<!--                        --><?php //if (in_array(109, $my_pages_sidebar)) { ?><!--<li><a href="report_withdrawal.php" title="Withdrawal Reports">Withdrawal Reports</a></li>--><?php //} ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-square fa-fw"></i> Signal Management<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(77, $my_pages_sidebar)) { ?><li><a href="signal_daily.php">Update Daily Signal</a></li><?php } ?>
                        <?php if (in_array(78, $my_pages_sidebar)) { ?><li><a href="signal_review.php">Weekly Market Analysis</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users fa-fw"></i> Prospect<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(110, $my_pages_sidebar)) { ?><li><a href="prospect_source.php">Manage Prospect Source</a></li><?php } ?>
                        <?php if (in_array(111, $my_pages_sidebar)) { ?><li><a href="prospect_add.php">Add New Prospect</a></li><?php } ?>
                        <?php if (in_array(112, $my_pages_sidebar)) { ?><li><a href="prospect_manage.php">Manage Prospects</a></li><?php } ?>
                        <?php if (in_array(112, $my_pages_sidebar)) { ?><li><a href="prospect_manage_funded.php">Manage Prospects - Funded</a></li><?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-clock-o fa-fw"></i> Reminders<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if (in_array(77, $my_pages_sidebar)) { ?><li><a href="reminder_add.php">Add New Reminder</a></li><?php } ?>
                        <?php if (in_array(78, $my_pages_sidebar)) { ?><li><a href="reminder_manage.php">Manage Reminders</a></li><?php } ?>
                    </ul>
                </li>
                
<!--                <li class="dropdown">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-expand fa-fw"></i> Customer Care<b class="caret"></b></a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        --><?php ////if (in_array(110, $my_pages_sidebar)) { ?><!--<li><a href="customer_care_log_add.php">Add New Log</a></li>--><?php ////} ?>
<!--                        --><?php ////if (in_array(112, $my_pages_sidebar)) { ?><!--<li><a href="customer_care_admin_log_manage.php">Manage Logs</a></li>--><?php ////} ?>
<!--                        --><?php ////if (in_array(112, $my_pages_sidebar)) { ?><!--<li><a href="customer_care_all_log_manage.php">Manage All Logs</a></li>--><?php ////} ?>
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="dropdown">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-paperclip fa-fw"></i> Surveys &amp; Feedbacks<b class="caret"></b></a>-->
<!--                    <ul class="dropdown-menu">-->
<!--                        --><?php //if (in_array(113, $my_pages_sidebar)) { ?><!--<li><a href="survey/new_survey.php">New Survey</a></li>--><?php //} ?>
<!--                        --><?php //if (in_array(114, $my_pages_sidebar)) { ?><!--<li><a href="survey/survey_feedback.php">Survey Feedbacks</a></li>--><?php //} ?>
<!--                        --><?php //if (in_array(115, $my_pages_sidebar)) { ?><!--<li><a href="survey/manage_survey.php">Manage Surveys</a></li>--><?php //} ?>
<!--                        --><?php //if (in_array(116, $my_pages_sidebar)) { ?><!--<li><a href="survey/testimonials.php">Testimonials</a></li>--><?php //} ?>
<!--                    </ul>-->
<!--                </li>-->
                <li><a href="logout.php" title="Log Out"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
            </ul>
        </div>
    </nav>
</div>