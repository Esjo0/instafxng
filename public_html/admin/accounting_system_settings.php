<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$admin_code = $_SESSION['admin_unique_code'];

if(isset($_POST['add_location']))
{
$location = $db_handle->sanitizePost(trim($_POST['location']));
//validate that its not a duplicate
$query = "SELECT * FROM accounting_system_office_locations WHERE location LIKE '%$location%' ";
$result = $db_handle->runQuery($query);
$result = $db_handle->affectedRows();
if($result > 0)
{
    $message_error = "This Office Location Already Exists";
}
else
{
    $query = "INSERT INTO accounting_system_office_locations (location, admin_code) VALUES ('$location', '$admin_code')";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "New Office Location Added";
    }
    else
    {
        $message_error = "Operation Failed";
    }
}

}

$query = "SELECT * FROM accounting_system_office_locations ";
$result = $db_handle->runQuery($query);
$locations = $db_handle->fetchAssoc($result);



if(isset($_POST['add_budget']))
{
    $month = $db_handle->sanitizePost(trim($_POST['month']));
    $year = $db_handle->sanitizePost(trim($_POST['year']));
    $amount = $db_handle->sanitizePost(trim($_POST['amount']));
    //$current_m_y = date('F Y');
    $budget_m_y = $month." ".$year;
    //validate that its not a duplicate
    $query = "SELECT * FROM accounting_system_budgets WHERE month_year LIKE '%$budget_m_y%' ";
    $result = $db_handle->runQuery($query);
    $result = $db_handle->affectedRows();
    if($result > 0)
    {
        $message_error = "There Is A Budget For This Month Already.";
    }
    else
    {
        $query = "INSERT INTO accounting_system_budgets (month_year, amount, admin_code) VALUES ('$budget_m_y', '$amount', '$admin_code')";
        $result = $db_handle->runQuery($query);
        if($result)
        {
            $message_success = "New Monthly Budget Added";
        }
        else
        {
            $message_error = "Operation Failed";
        }
    }


}

$query = "SELECT * FROM accounting_system_budgets ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$budgets = $db_handle->fetchAssoc($result);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Accounting System</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Accounting System" />
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
                            <h4><strong>ACCOUNTING SYSTEM SETTINGS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <fieldset>
                                            <legend><b>Office Locations</b></legend>
                                            <p>Add a new office location.</p>
                                            <div class="form-group">
                                                <!--<label class="control-label col-sm-3" for="full_name">Customer's Name:</label>-->
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <input name="location" type="text" id="location" placeholder="Enter location title or name here..." class="form-control" required>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button name="add_location" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>Office locations.</p>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Location</th>
                                                    <th>Created </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(isset($locations) && !empty($locations))
                                                {
                                                    foreach ($locations as $row)
                                                    { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo strtoupper($row['location']); ?>
                                                            </td>
                                                            <td><?php echo datetime_to_text2($row['created']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                { echo "<tr><td colspan='2'>No results to display...</td></tr>"; } ?>
                                                </tbody>
                                            </table>
                                            <?php if(isset($locations) && !empty($locations)) { ?>
                                                <div class="tool-footer text-right">
                                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                                </div>
                                            <?php } ?>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12">
                                    <form id="budget_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <fieldset>
                                            <legend><b>Budgets</b></legend>
                                            <p>Create new budget.</p>
                                            <div class="form-group">
                                                <!--<label class="control-label col-sm-3" for="full_name">Customer's Name:</label>-->
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <?php
                                                            echo '<select class="form-control" name="month">';
                                                            echo '<option>Select Month</option>';
                                                            for($i = 1; $i <= 12; $i++)
                                                            {
                                                                $i = str_pad($i, 2, 0, STR_PAD_LEFT);
                                                                echo "<option value='".date('F', mktime(0, 0, 0, $i, 10))."'>".date('F', mktime(0, 0, 0, $i, 10))."</option>";
                                                            }
                                                            echo '</select>';
                                                            ?>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <?php
                                                            echo '<select class="form-control" name="year">';
                                                            echo '<option>Select Year</option>';
                                                            for($i = date('Y'); $i <= date('Y', strtotime('100 years')); $i++)
                                                            {
                                                                echo "<option value='$i'>$i</option>";
                                                            }
                                                            echo '</select>';
                                                            ?>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">₦</span>
                                                                <input  name="amount" type="text" id="amount" placeholder="Amount"  class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button name="add_budget" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>Previous Budgets</p>
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Month And Year</th>
                                                    <th>Budget</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(isset($budgets) && !empty($budgets))
                                                {
                                                    foreach ($budgets as $row)
                                                    { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo strtoupper($row['month_year']); ?>
                                                            </td>
                                                            <td>₦<?php echo $row['amount']; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                { echo "<tr><td colspan='2'>No results to display...</td></tr>"; } ?>
                                                </tbody>
                                            </table>
                                            <?php if(isset($budgets) && !empty($budgets)) { ?>
                                                <div class="tool-footer text-right">
                                                    <p class="pull-left">Showing <?php echo $prespagelow . " to " . $prespagehigh . " of " . $numrows; ?> entries</p>
                                                </div>
                                            <?php } ?>
                                            <?php if(isset($budgets) && !empty($budgets)) { include 'layouts/pagination_links.php'; } ?>
                                        </fieldset>
                                    </form>
                                </div>
                                <br/>
                                <br/>
                                <div class="col-sm-12">

                                </div>
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