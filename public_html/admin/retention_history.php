<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {redirect_to("login.php");}

$periods = array(
    'M1' => array('start' => '2016-10-01', 'end' => '2016-10-31', 'title' => 'Month1 October 2016'),
    'M2' => array('start' => '2016-11-01', 'end' => '2016-11-31', 'title' => 'Month2 November 2016'),
    'M3' => array('start' => '2016-12-01', 'end' => '2016-12-31', 'title' => 'Month3 December 2016'),
    'M4' => array('start' => '2017-01-01', 'end' => '2017-01-31', 'title' => 'Month4 January 2017'),
    'M5' => array('start' => '2017-02-01', 'end' => '2017-02-31', 'title' => 'Month5 February 2017'),
    'M6' => array('start' => '2017-03-01', 'end' => '2017-03-31', 'title' => 'Month6 March 2017'),
    'M7' => array('start' => '2017-04-01', 'end' => '2017-04-31', 'title' => 'Month7 April 2017'),
    'M8' => array('start' => '2017-05-01', 'end' => '2017-05-31', 'title' => 'Month8 May 2017'),
    'M9' => array('start' => '2017-06-01', 'end' => '2017-06-31', 'title' => 'Month9 June 2017'),
    'M10' => array('start' => '2017-07-01', 'end' => '2017-07-31', 'title' => 'Month10 July 2017'),
    'M11' => array('start' => '2017-08-01', 'end' => '2017-08-31', 'title' => 'Month11 August 2017'),
    'M12' => array('start' => '2017-09-01', 'end' => '2017-09-31', 'title' => 'Month12 September 2017'),
    'M13' => array('start' => '2017-10-01', 'end' => '2017-10-31', 'title' => 'Month13 October 2017'),
    'M14' => array('start' => '2017-11-01', 'end' => '2017-11-31', 'title' => 'Month14 November 2017'),
    'M15' => array('start' => '2017-12-01', 'end' => '2017-12-31', 'title' => 'Month15 December 2017'),
    'M16' => array('start' => '2018-01-01', 'end' => '2018-01-31', 'title' => 'Month16 January 2018'),
    'M17' => array('start' => '2018-02-01', 'end' => '2018-02-31', 'title' => 'Month17 February 2018'),
    'M18' => array('start' => '2018-03-01', 'end' => '2018-03-31', 'title' => 'Month18 March 2018'),
    'M19' => array('start' => '2018-04-01', 'end' => '2018-04-31', 'title' => 'Month19 April 2018'),
    'M20' => array('start' => '2018-05-01', 'end' => '2018-05-31', 'title' => 'Month20 May 2018'),
    'M21' => array('start' => '2018-06-01', 'end' => '2018-06-31', 'title' => 'Month21 June 2018'),
    'M22' => array('start' => '2018-07-01', 'end' => '2018-07-31', 'title' => 'Month22 July 2018'),
    'Q1' => array('start' => '2016-10-01', 'end' => '2016-12-31', 'title' => 'Fourth Quarter (October 2016 - December 2016)'),
    'Q2' => array('start' => '2017-01-01', 'end' => '2017-03-31', 'title' => 'First Quarter (January 2017 - March 2017)'),
    'Q3' => array('start' => '2017-04-01', 'end' => '2017-06-31', 'title' => 'Second Quarter (April 2017 - June 2017)'),
    'Q4' => array('start' => '2017-07-01', 'end' => '2017-09-31', 'title' => 'Third Quarter (July 2017 - September 2017)'),
    'Q5' => array('start' => '2017-10-01', 'end' => '2017-12-31', 'title' => 'Fourth Quarter (October 2017 - December 2017)'),
    'Q6' => array('start' => '2018-01-01', 'end' => '2018-03-31', 'title' => 'First Quarter (January 2018 - March 2018)'),
    'Q7' => array('start' => '2018-04-01', 'end' => '2018-06-31', 'title' => 'Second Quarter (April 2018 - June 2018)'),
    'H1' => array('start' => '2016-10-01', 'end' => '2017-03-31', 'title' => 'First Half (October 2016 - March 2017)'),
    'H2' => array('start' => '2017-04-01', 'end' => '2017-09-31', 'title' => 'Second Half (April 2017 - September 2017)'),
    'H3' => array('start' => '2017-10-01', 'end' => '2018-03-31', 'title' => 'Third Quarter (October 2018 - March 2018)'),
    'Y1' => array('start' => '2016-10-01', 'end' => '2017-09-31', 'title' => 'Year1 (October 2016 - September 2017)'),
);

function process ($SESSION2){
    global $db_handle;
    $feedback = array();
    $num_days = count(date_range($SESSION2['start'], $SESSION2['end']));
    $SESSION1['start'] = date('Y-m-d', strtotime('-'.$num_days.' days', strtotime($SESSION2['start'])));
    $SESSION1['end'] = date('Y-m-d', strtotime('-1 day', strtotime($SESSION2['start'])));

    //RETENTION PERCENTAGE
    $query1 = "SELECT td.ifx_acct_no AS acc_no
FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
WHERE
  (STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION1['start']}' AND '{$SESSION1['end']}')
AND
  (td.ifx_acct_no IN (
    SELECT _td.ifx_acct_no FROM trading_commission AS _td
      INNER JOIN user_ifxaccount AS _ui ON _td.ifx_acct_no = _ui.ifx_acct_no
      WHERE STR_TO_DATE(_td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION2['start']}' AND '{$SESSION2['end']}')
  ) GROUP BY td.ifx_acct_no;";
    $query2 = "SELECT td.ifx_acct_no FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION2['start']}' AND '{$SESSION2['end']}'
GROUP BY td.ifx_acct_no; ";
    $retained_accounts = $db_handle->numRows($query1);
    $current_accounts = $db_handle->numRows($query2);
    $feedback['retention_percentage'] = number_format(($retained_accounts / $current_accounts) * 100, 2)."%";

    //COMMISSIONS
    $query4 = "SELECT SUM(td.commission) AS total_commissions
              FROM trading_commission AS td
              INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
              WHERE (STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION2['start']}' AND '{$SESSION2['end']}')
              AND (ui.ifx_acct_no IN (
                SELECT _ui.ifx_acct_no FROM user_ifxaccount AS _ui
                INNER JOIN trading_commission AS _td ON _ui.ifx_acct_no = _td.ifx_acct_no 
                WHERE STR_TO_DATE(_td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION1['start']}' AND '{$SESSION1['end']}')
              );";
    $commissions = $db_handle->fetchAssoc($db_handle->runQuery($query4))[0]['total_commissions'];
    $feedback['commissions_retained_accounts'] = '&dollar;'.number_format($commissions, 2);

    //PERCENTAGE OF COMMISSIONS
    $query5 = "SELECT SUM(td.commission) AS total_commissions
              FROM trading_commission AS td
              INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no
              WHERE (STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION2['start']}' AND '{$SESSION2['end']}')";
    $all_commissions = $db_handle->fetchAssoc($db_handle->runQuery($query5))[0]['total_commissions'];
    $feedback['commissions_percentage'] = number_format(($commissions / $all_commissions) * 100, 2).'%';
    $feedback['total_commissions'] = '&dollar;'.number_format($all_commissions, 2);

    //ACCOUNTS RETAINED
    $query3 = "SELECT td.ifx_acct_no FROM trading_commission AS td
INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no 
WHERE (STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION1['start']}' AND '{$SESSION1['end']}') 
AND (td.ifx_acct_no IN (
  SELECT _td.ifx_acct_no FROM trading_commission AS _td
  INNER JOIN user_ifxaccount AS _ui ON _td.ifx_acct_no = _ui.ifx_acct_no
  WHERE STR_TO_DATE(_td.date_earned, '%Y-%m-%d') BETWEEN '{$SESSION2['start']}' AND '{$SESSION2['end']}' )
) GROUP BY td.ifx_acct_no ";
    $feedback['accounts_retained'] = number_format($db_handle->numRows($query3));

    //PERIOD
    $feedback['period'] = date_to_text($SESSION2['start'])." to ".date_to_text($SESSION2['end']);
    return $feedback;
}

if(isset($_POST['filter_value'])){
    $filter_category = $periods[$_POST['filter_value']]['title'];
    $output = process($periods[$_POST['filter_value']]);
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Client Retention History</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Client Retention History" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script>
            function filter(value, heading) {
                document.getElementById('filter_display').value = heading;
                document.getElementById('filter_value').value = value;
                document.getElementById('filter_trigger').click();
            }
        </script>
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
                    <h4><strong>CLIENT RETENTION HISTORY</strong></h4>
                </div>
            </div>

            <div class="section-tint super-shadow">
                <div class="row">
                    <div class="col-sm-12">
                        <p>Select a period below.</p>
                        <div class="row">
                            <div class="col-sm-6">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI; ?>">
                                    <div class="input-group input-group-sm">
                                        <input value="<?php echo $filter_category; ?>" id="filter_display" readonly type="text" name="filter_val" class="form-control">
                                        <div class="input-group-btn input-group-select">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span class="concept">Filter</span>  <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php foreach ($periods as $key => $period){ ?>
                                                    <li><a onclick="filter('<?php echo $key; ?>', '<?php echo $period['title']; ?>')" href="javascript:void(0);"><?php echo $period['title']; ?></a></li>
                                                <?php } ?>
                                                <!--<li><a onclick="filter('all', 'All Unverified Clients')" href="javascript:void(0);">All Unverified Clients</a></li>
                                                <li><a onclick="filter('ilpr', 'Clients With ILPR Accounts')" href="javascript:void(0);">Clients With ILPR Accounts</a></li>
                                                <li><a onclick="filter('nonilpr', 'Clients Without ILPR Accounts')" href="javascript:void(0);">Clients Without ILPR Accounts</a></li>
                                                <li><a onclick="filter('training', 'Training Clients')" href="javascript:void(0);">Training Clients</a></li>-->
                                            </ul>
                                            <input id="filter_trigger" style="display: none" name="filter" type="submit">
                                            <input id="filter_value" name="filter_value" type="hidden">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>





                        <?php if(isset($output) && !empty($output)): ?>
                        <h5>Retention History For <?php echo $periods[$_POST['filter_value']]['title'] ?></h5>
                        <table class="table table-responsive table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Retention Percentage</th>
                                    <th>Accounts Retained</th>
                                    <th>Total Commissions</th>
                                    <th>Commissions From <br/>Retained Accounts</th>
                                    <th>Percentage Commissions <br/>From Retained Accounts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b><?php echo $periods[$_POST['filter_value']]['title'] ?></b>  <br/>(<?php echo $output['period'] ?>)</td>
                                    <td><?php echo $output['retention_percentage'] ?></td>
                                    <td><?php echo $output['accounts_retained'] ?></td>
                                    <td><?php echo $output['total_commissions'] ?></td>
                                    <td><?php echo $output['commissions_retained_accounts'] ?></td>
                                    <td><?php echo $output['commissions_percentage'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>