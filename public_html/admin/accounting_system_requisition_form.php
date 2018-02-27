<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}
$query = "SELECT * FROM accounting_system_office_locations ";
$result = $db_handle->runQuery($query);
$locations = $db_handle->fetchAssoc($result);
$author_code = $_SESSION['admin_unique_code'];
if(isset($_POST['send_order']))
{
    $office_location = $db_handle->sanitizePost($_POST['office_location']);

    $req_order = $db_handle->sanitizePost($_POST['req_order']);
    $req_order_code = $db_handle->sanitizePost($_POST['req_order_code']);
    $req_order_total = $db_handle->sanitizePost($_POST['req_order_total']);

    $query = "INSERT INTO accounting_system_req_order (author_code, req_order_code, req_order, req_order_total, location) 
          VALUES ('$author_code', '$req_order_code', '$req_order', '$req_order_total', '$office_location')";
    $result = $db_handle->runQuery($query);
}
if(isset($_POST['process_refund']))
{
    $req_order_total = $db_handle->sanitizePost(trim($_POST['req_order_total']));
    $req_order_code = $db_handle->sanitizePost(trim($_POST['req_order_code']));
    $order_total_spent = $db_handle->sanitizePost(trim($_POST['order_total_spent']));

    if($order_total_spent > $req_order_total)
    {
        $message_error = "Operation Failed, Please Crosscheck The Amount You Want To Refund.";
    }
    $query = "INSERT INTO accounting_system_refunds (req_order_code, actual_spent) VALUES ('$req_order_code', '$order_total_spent') ";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "Your refund would be processed shortly.";
    }
}
$query = "SELECT 
          payment_status,
          accounting_system_req_order.req_order_total AS req_order_total,
          accounting_system_req_order.req_order_code AS req_order_code, 
          accounting_system_req_order.req_order AS req_order, 
          accounting_system_req_order.created AS created, 
          accounting_system_req_order.status AS status,
          accounting_system_req_order.comments AS comments,
          CONCAT(admin.first_name, SPACE(1), admin.last_name) AS author_name
          FROM admin, accounting_system_req_order 
          WHERE accounting_system_req_order.author_code = '$author_code'
          AND admin.admin_code = '$author_code'
          ORDER BY accounting_system_req_order.created DESC ";
$numrows = $db_handle->numRows($query);
$rowsperpage = 20;
$totalpages = ceil($numrows / $rowsperpage);
if (isset($_GET['pg']) && is_numeric($_GET['pg'])) {    $currentpage = (int) $_GET['pg'];} else {    $currentpage = 1;}
if ($currentpage > $totalpages) { $currentpage = $totalpages; }
if ($currentpage < 1) { $currentpage = 1; }
$prespagelow = $currentpage * $rowsperpage - $rowsperpage + 1;
$prespagehigh = $currentpage * $rowsperpage;
if($prespagehigh > $numrows) { $prespagehigh = $numrows; }
$offset = ($currentpage - 1) * $rowsperpage;
$query .= 'LIMIT ' . $offset . ',' . $rowsperpage;
$result = $db_handle->runQuery($query);
$projects = $db_handle->fetchAssoc($result);
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
        <style>
            div#preloader
            {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 999;
                width: 100%;
                height: 100%;
                overflow: hidden;
                background: #ffffff url('../images/Spinner.gif') no-repeat center center;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {

                // site preloader -- also uncomment the div in the header and the css style for #preloader
                $(window).load(function(){
                    $('#preloader').fadeOut('slow',function(){$(this).remove();});
                });

            });
        </script>
        <script src="../js/class_accounting_system.js"></script>
    </head>
    <body>
    <div id="preloader"></div>
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
                            <h4><strong>REQUISITION FORM</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div id="messageDiv"></div>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <p>Fill the form below to add a new item to your requisition order.</p>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input name="item_name" type="text" id="item_name" placeholder="Item Description" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <input  name="no_of_items" type="number" id="no_of_items" placeholder="No Of Items"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">₦</span>
                                                            <input onblur="acc_system.get_total('no_of_items', 'unit_price', 'item_total_price')"  name="unit_price" type="text" id="unit_price" placeholder="Unit Cost"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">₦</span>
                                                            <input onfocus="acc_system.get_total('no_of_items', 'unit_price', 'item_total_price')"  name="item_total_price" type="text" id="item_total_price" placeholder="Total Cost"  class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button onclick="acc_system.AddToList()" type="button" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  class="col-sm-12">
                                    <div id="order_list">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>No Of Items</th>
                                                <th>Unit Cost</th>
                                                <th>Total Cost</th>
                                            </tr>
                                            </thead>
                                            <tbody id="requisitionOrder">
                                            </tbody>
                                        </table>
                                        <p class="pull-right">
                                            Order Total : <b>₦</b><b id="requisition_total">0</b>
                                        </p>
                                        <br/>
                                    </div>
                                    <p><b>Select Location:</b></p>
                                    <?php if(isset($locations) && !empty($locations))
                                    {
                                    foreach ($locations as $row)
                                    { ?>
                                    <form id="location_list">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <div class="radio-inline">
                                                    <label for="">
                                                        <input id="location" type="radio" name="office_location" value="<?php echo $row['location_id']; ?>"  required/>
                                                        <?php echo strtoupper($row['location']); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php }}else{ echo "No results to display..."; } ?>
                                        </div>
                                    </form>
                                    <div class="form-group">
                                        <div class="col-sm-offset-10 col-sm-3">
                                            <button id="s_o_btn" onclick="acc_system.send_order_new_function('office_location', '<?php echo $_SESSION['admin_unique_code']?>', document.getElementById('requisition_total').innerHTML)"  name="send_order" type="button" class="btn btn-success "><i class="glyphicon glyphicon-send"></i> Submit Order</button>
                                        </div>
                                    </div>
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