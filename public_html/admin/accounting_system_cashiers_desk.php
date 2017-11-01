<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

if(isset($_POST['paid']))
{
    $req_order_code = $_POST['req_order_code'];
    $query = "UPDATE accounting_system_req_order SET payment_status = '2' WHERE req_order_code = '$req_order_code' LIMIT 1 ";
    $result = $db_handle->runQuery($query);
    if($result)
    {
        $message_success = "Operation Successful";
    }
    else
    {
        $message_error = "Operation Failed";
    }
}

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
        <script type="text/javascript">
            var XMLHttpRequestObject = false;

            if (window.XMLHttpRequest)
            {
                XMLHttpRequestObject = new XMLHttpRequest();
            }
            else if (window.ActiveXObject)
            {
                XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
            }
            function getRandomInt(min, max)
            {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
            function RandomString(strlen)
            {
                const chars = "abcdefghijklmnopqrstuvwxyz0123456789";
                var result = "";
                for (var i=0; i<strlen; i++) {
                    result += chars[getRandomInt(0,35)];
                }
                return result;
            }
            function search()
            {

                if(XMLHttpRequestObject)
                {
                    XMLHttpRequestObject.open("POST", "accounting_system_cashiers_desk_server.php");
                    XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                    XMLHttpRequestObject.onreadystatechange = function()
                    {
                        if (XMLHttpRequestObject.readyState == 4 &&
                            XMLHttpRequestObject.status == 200)
                        {
                            var returnedData = XMLHttpRequestObject.responseText;
                            var messageDiv = document.getElementById('messageDiv');
                            messageDiv.innerHTML = returnedData;
                        }
                    };
                    var cash_out_code = document.getElementById('cash_out_code').value;
                    //window.alert("req_order=" + req_order + "&req_order_code=" + req_order_code + "&req_order_total=" + req_order_total);

                    XMLHttpRequestObject.send("cash_out_code=" + cash_out_code);
                    //document.getElementById('cash_out_code').innerHTML = "";
                }

                return false;
            }

        </script>
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
                            <h4><strong>REQUISITION FORM</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="col-sm-12">
                                    <form id="requisition_form" data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                        <p>Enter a cash out code below.</p>
                                        <div class="form-group">
                                            <!--<label class="control-label col-sm-3" for="full_name">Customer's Name:</label>-->
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input name="cash_out_code" type="text" id="cash_out_code" placeholder="Enter A Cash Out Code Here..." class="form-control" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button onclick="search()" type="button" class="btn btn-success"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div  class="col-sm-12">
                                    <div id="messageDiv">

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