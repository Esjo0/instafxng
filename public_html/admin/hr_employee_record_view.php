<?php
require_once("../init/initialize_admin.php");
require_once("../../app_assets/hr_managment_system_config.php");
if (!$session_admin->is_logged_in())
{
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$employee_code_encrypted = $get_params['id'];
if(is_null($employee_code_encrypted) || empty($employee_code_encrypted))
{
    redirect_to("./"); // page cannot display anything without the id
}
$employee_code = decrypt(str_replace(" ", "+", $employee_code_encrypted));
$employee_code = preg_replace("/[^A-Za-z0-9 ]/", '', $employee_code);
$employee_details = $obj_hr_management->get_record_details($employee_code);
if(is_null($employee_details) || empty($employee_details))
{
    redirect_to("./"); // page cannot display anything without the id
}
//extract($employee_details);



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - Employee Details</title>
        <meta name="title" content="Instaforex Nigeria | Admin - Employee Details" />
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
                            <h4><strong>VIEW EMPLOYEE DETAILS</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="pull-left"><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default" title="Go Back"><i class="fa fa-arrow-circle-left"></i> Go Back</a></p>
                                        <p class="pull-right"><a href="hr_employee_new_record.php?x=edit&id=<?php echo encrypt($employee_details['employee_code']); ?>" class="btn btn-info" title="Edit Details">Edit <i class="fa fa-arrow-circle-right"></i></a></p>
                                    </div>
                                </div>
                                        <!------------- Contact Section --->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <center><h5>Employee Information</h5></center>
                                        <p><b>Station: </b><?php echo $office_stations[$employee_details['station']];?></p>
                                        <p><b>Department: </b><?php echo $department[$employee_details['dept']];?></p>
                                        <p><b>Employee Type: </b><?php echo $e_type[$employee_details['e_type']];?></p>
                                        <p><b>Employee Category: </b><?php echo $e_cat[$employee_details['e_cat']];?></p>
                                        <p><b>Designation / Job Title: </b><?php echo $employee_details['j_title'];?></p>
                                        <br/>
                                        <center><h5>Employee Personal Information</h5></center>
                                        <p><b>Salutation: </b><?php echo $salutation[$employee_details['title']] ;?></p>
                                        <p><b>Full Name: </b><?php echo $employee_details['f_name']." ".$employee_details['m_name']." ".$employee_details['l_name'] ;?></p>
                                        <p><b>Date Of Birth: </b><?php echo date_to_text($employee_details['d_o_b']); ?></p>
                                        <p><b>Gender: </b><?php echo $employee_details['gender']; ?></p>
                                        <p><b>Blood Group: </b><?php echo $employee_details['b_group']; ?></p>
                                        <p><b>Marital Status: </b><?php echo $employee_details['m_stat']; ?></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12 text-center" style="margin-bottom: 4px !important">
                                            <?php if(!empty($employee_details['img_url'])) { ?>
                                            <a href="<?php echo $employee_details['img_url']; ?>" title="click to enlarge" target="_blank">
                                                <img src="<?php echo $employee_details['img_url']; ?>" width="120px" height="120px"/>
                                            </a>
                                            <?php } else { ?>
                                            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                                            <?php } ?>
                                            <br/>
                                            <br/>
                                            <!--<a href="" data-toggle="modal" data-target="#myModalCard" class="btn btn-default" style="margin-top: 2px !important">View</a>-->
                                        </div>
                                        <br/>
                                        <center><h5>Employee Additional Information</h5></center>
                                        <p><b>Join Date: </b><?php echo date_to_text($employee_details['j_date']); ?></p>
                                        <p><b>Tax Identification Number (TIN): </b><?php echo $employee_details['tin']; ?></p>
                                        <p><b>Identification Card Number: </b><?php echo $employee_details['id_num']; ?></p>
                                        <p><b>Identification Card Expiration Date: </b><?php echo date_to_text($employee_details['id_ex_date']); ?></p>
                                        <p><b>Designation / Job Title: </b><?php echo $employee_details['j_title'];?></p>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <center><h5>Contact Information</h5></center>
                                        <p><b>Address: </b><?php echo $employee_details['address']; ?></p>
                                        <p><b>City: </b><?php echo $employee_details['city']; ?></p>
                                        <p><b>State: </b><?php echo $employee_details['state']; ?></p>
                                        <p><b>Country: </b><?php echo $employee_details['country']; ?></p>
                                        <p><b>Personal Phone Number: </b><?php echo $employee_details['p_phone']; ?></p>
                                        <p><b>Office Phone Number: </b><?php echo $employee_details['o_phone']; ?></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <center><h5>Emergency Contact</h5></center>
                                        <p><b>Contact Person: </b><?php echo $employee_details['emergency_contact_name']; ?></p>
                                        <p><b>Relationship: </b><?php echo $employee_details['emergency_contact_rel']; ?></p>
                                        <p><b>Phone Number: </b><?php echo $employee_details['emergency_contact_phone']; ?></p>
                                    </div>
                                </div>

                                <?php
                                    $query = "SELECT * FROM hr_employee_records_notes WHERE record_id = '".$employee_details['record_id']."'";
                                    $notes = $db_handle->fetchAssoc($db_handle->runQuery($query));
                                    if(isset($notes) && !is_null($notes) && !empty($notes))
                                    {
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h5>Admin Notes</h5>
                                                <div style="height: auto; overflow-y: scroll;">
                                                    <?php foreach ($notes as $row){ ?>
                                                        <div class="transaction-remarks">
                                                            <span id="trans_remark_author"><?php echo $admin_object->get_admin_name_by_code($row['admin_code']); ?></span>
                                                            <span id="trans_remark"><?php echo $row['notes'];?></span>
                                                            <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
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