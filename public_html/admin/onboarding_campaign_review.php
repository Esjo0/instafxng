<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$_SESSION['display_view'] = true;
if(isset($_POST['view_all'])){
    unset($_SESSION['onboarding_campaign_review_query']);
}

    if(isset($_POST['search'])) {
        $from_date = $db_handle->sanitizePost($_POST['from_date']);
        $to_date = $db_handle->sanitizePost($_POST['to_date']);
        $campaign_id = $db_handle->sanitizePost($_POST['campaign_id']);
        $filter = $db_handle->sanitizePost($_POST['filter']);

        if($filter == 1){

            $query = "SELECT MAX(ec.course_order), CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
c.email, c.created, u.user_code, u.email, u.first_name, u.last_name, u.phone
FROM onboarding_campaign_leads AS c INNER JOIN user AS u ON u.email = c.email
INNER JOIN user_edu_exercise_log AS ueel ON ueel.user_code = u.user_code
INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id WHERE c.campaign_id = '$campaign_id'
AND (STR_TO_DATE(c.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
AND ec.course_order = 1 ORDER BY c.created  ";
            $_SESSION['onboarding_campaign_review_query'] = $query;
            $_SESSION['display_view'] = false;

        }else if($filter == 2){

            $query = "SELECT MAX(ec.course_order), CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name,
c.email, c.created, u.user_code, u.email, u.first_name, u.last_name, u.phone
FROM onboarding_campaign_leads AS c INNER JOIN user AS u ON u.email = c.email
INNER JOIN user_edu_exercise_log AS ueel ON ueel.user_code = u.user_code
INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id WHERE c.campaign_id = '$campaign_id'
AND (STR_TO_DATE(c.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date')
AND ec.course_order = 2 ORDER BY c.created ";
            $_SESSION['onboarding_campaign_review_query'] = $query;
            $_SESSION['display_view'] = false;
        }else if($filter == 3){

            $query = "SELECT ts.status, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, c.email, c.created, u.user_code, u.email, u.first_name, u.last_name, u.phone FROM onboarding_campaign_leads AS c
INNER JOIN user AS u ON u.email = c.email
              INNER JOIN training_schedule_students AS ts ON ts.user_code = u.user_code
WHERE c.campaign_id = '$campaign_id' AND (STR_TO_DATE(c.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ts.status = '5'
 ORDER BY c.created ";
            $_SESSION['onboarding_campaign_review_query'] = $query;
            $_SESSION['display_view'] = false;

        }else{
            $query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, c.email, c.created, u.user_code, u.email,
u.first_name, u.last_name, u.phone FROM onboarding_campaign_leads AS c
INNER JOIN user AS u ON c.email = u.email WHERE c.campaign_id = '$campaign_id'
AND (STR_TO_DATE(c.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') ORDER BY c.created";
            $_SESSION['onboarding_campaign_review_query'] = $query;
            $_SESSION['display_view'] = false;
        }


    }


    if(empty($_SESSION['onboarding_campaign_review_query'])){
        $query = "SELECT CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name, c.email, c.created , u.user_code, u.email, u.first_name, u.last_name, u.phone FROM onboarding_campaign_leads AS c INNER JOIN user AS u ON u.email = c.email ORDER BY c.created ";
        $_SESSION['onboarding_campaign_review_query'] = $query;
        $_SESSION['display_view'] = true;
    }

$query = $_SESSION['onboarding_campaign_review_query'];

    $numrows = $db_handle->numRows($query);

    $rowsperpage = 20;

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
    $query .= ' LIMIT ' . $offset . ',' . $rowsperpage;
    $result = $db_handle->runQuery($query);
    $campaign_leads = $db_handle->fetchAssoc($result);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Inactive Trading Clients</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Inactive Trading Clients" />
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
                            <h4><strong>ON-BOARDING CAMPAIGN CLIENTS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>

                                <p>Pick a date range below to see <strong>On-Boarding Campaign Clients</strong>.</p>

                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="from_date">From:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="from_date" type="text" class="form-control" id="datetimepicker" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="to_date">To:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <div class="input-group date">
                                                <input name="to_date" type="text" class="form-control" id="datetimepicker2" required>
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="search_text">Select Campaign:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select type="text" name="campaign_id" class="form-control " id="location" required>
                                                <?php
                                                $query = "SELECT * FROM onboarding_campaign";
                                                $result = $db_handle->runQuery($query);
                                                $result = $db_handle->fetchAssoc($result);
                                                foreach ($result as $row_loc) {
                                                    extract($row_loc)
                                                    ?>
                                                    <option
                                                        value="<?php echo $campaign_id; ?>"><?php echo $title; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" for="search_text">Filter:</label>
                                        <div class="col-sm-9 col-lg-5">
                                            <select type="text" name="filter" class="form-control " id="location">
                                                <option ></option>
                                                    <option value="1">FMM</option>
                                                <option value="2">F0C</option>
                                                <option value="3">1-2-1</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9"><input name="search" type="submit" class="btn btn-success" value="Search" /></div>
                                    </div>
                                    <script type="text/javascript">
                                        $(function () {
                                            $('#datetimepicker, #datetimepicker2').datetimepicker({
                                                format: 'YYYY-MM-DD'
                                            });
                                        });
                                    </script>
                                </form>


                                    <?php if(isset($numrows)) { ?>
                                    <p>
                                        <?php if($_SESSION['display_view'] == true){?>
                                        All Onboarding campaign leads.<br>
                                        <?php }else{?>
                                        Showing results from <?php echo date_to_text($from_date); ?> to <?php echo date_to_text($to_date); ?><br />
                                        <?php }?>
                                        <strong>Result Found: </strong><?php echo number_format($numrows); ?>
                                        <form class="form-horizontal pull-right" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                                            <div class="form-group">
                                                <div class="col-sm-12 "><input name="view_all" type="submit" class="btn btn-md btn-success" value="View All" /></div>
                                            </div>
                                        </form>
                                        </p>
                                <?php } ?>

                                <?php if(isset($campaign_leads) && !empty($campaign_leads)) { require 'layouts/pagination_links.php'; } ?>

                                <table class="table table-responsive table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($campaign_leads) && !empty($campaign_leads)) { foreach ($campaign_leads as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                            <td nowrap="nowrap">
                                                <a target="_blank" title="Comment" class="btn btn-xs btn-success" href="sales_contact_view.php?x=<?php echo dec_enc('encrypt', $row['user_code']); ?>&r=<?php echo 'onboarding_campaign_review'; ?>&c=<?php echo dec_enc('encrypt', 'ON-BOARDING CAMPAIGN'); ?>&pg=<?php echo $currentpage; ?>"><i class="glyphicon glyphicon-comment icon-white"></i> </a>
                                                <a target="_blank" title="View" class="btn btn-xs btn-info" href="client_detail.php?id=<?php echo dec_enc('encrypt', $row['user_code']); ?>"><i class="glyphicon glyphicon-eye-open icon-white"></i> </a>
                                            </td>
                                        </tr>
                                    <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No results to display</em></td></tr>"; } ?>
                                    </tbody>
                                </table>
                                
                                <?php if(isset($campaign_leads) && !empty($campaign_leads)) { ?>
                                <div class="tool-footer text-right">
                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if(isset($campaign_leads) && !empty($campaign_leads)) { require 'layouts/pagination_links.php'; } ?>
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