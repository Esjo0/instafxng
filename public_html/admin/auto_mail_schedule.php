<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}
$today = date('Y-m-d');



//TODO: Refactor and make it dynamic
if (isset($_POST['mail'])){
    $no_of_mails = $db_handle->sanitizepost($_POST['no_of_mails']);
    $interval = $db_handle->sanitizepost($_POST['interval']);
    if($no_of_mails >= 1){
        $day_value = $interval;
        $query_1 = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = $day_value) AND ueel.lesson_id = 1 AND uefp.user_code IS NULL AND u.user_code NOT IN
                (
                    SELECT u.user_code
                    FROM user_edu_exercise_log AS ueel
                    INNER JOIN user AS u ON ueel.user_code = u.user_code
                    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                    LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                    WHERE ueel.lesson_id IN (2, 3, 4, 5) AND uefp.user_code IS NULL
                )
                GROUP BY ueel.user_code ORDER BY u.academy_signup DESC, u.last_name ASC";
        $mail = $db_handle->sanitizePost($_POST['mail_1']);
        $get_mail_1 =
        <<<MAIL
        $mail
MAIL;
        $my_subject_1 = $db_handle->sanitizePost($_POST['subject_1']);
        $send_message_1 = "student_auto_mail_send(\"$query_1\", \"$my_subject_1\", \"$get_mail_1\")";
        $txt = "$"."send_message_1a = ".$send_message_1.";";
        $myfile = file_put_contents('schedule.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        fclose($myfile);

    }

    //two mails
    if($no_of_mails >= 2){
        $day_value = $interval * 2;
        $query_1 = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = $day_value) AND ueel.lesson_id = 1 AND uefp.user_code IS NULL AND u.user_code NOT IN
                (
                    SELECT u.user_code
                    FROM user_edu_exercise_log AS ueel
                    INNER JOIN user AS u ON ueel.user_code = u.user_code
                    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                    LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                    WHERE ueel.lesson_id IN (2, 3, 4, 5) AND uefp.user_code IS NULL
                )
                GROUP BY ueel.user_code ORDER BY u.academy_signup DESC, u.last_name ASC";
        $mail = $db_handle->sanitizePost($_POST['mail_2']);
        $get_mail_1 =
            <<<MAIL
        $mail
MAIL;
        $my_subject_1 = $db_handle->sanitizePost($_POST['subject_2']);
        $send_message_1 = "student_auto_mail_send(\"$query_1\", \"$my_subject_1\", \"$get_mail_1\")";
        $txt = "$"."send_message_1b = ".$send_message_1.";";
        $myfile = file_put_contents('schedule.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        fclose($myfile);

    }
    //two mails
    if($no_of_mails >= 3){
        $day_value = $interval * 3;
        $query_1 = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = $day_value) AND ueel.lesson_id = 1 AND uefp.user_code IS NULL AND u.user_code NOT IN
                (
                    SELECT u.user_code
                    FROM user_edu_exercise_log AS ueel
                    INNER JOIN user AS u ON ueel.user_code = u.user_code
                    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                    LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                    WHERE ueel.lesson_id IN (2, 3, 4, 5) AND uefp.user_code IS NULL
                )
                GROUP BY ueel.user_code ORDER BY u.academy_signup DESC, u.last_name ASC";
        $mail = $db_handle->sanitizePost($_POST['mail_3']);
        $get_mail_1 =
            <<<MAIL
        $mail
MAIL;
        $my_subject_1 = $db_handle->sanitizePost($_POST['subject_3']);
        $send_message_1 = "student_auto_mail_send(\"$query_1\", \"$my_subject_1\", \"$get_mail_1\")";
        $txt = "$"."send_message_1c = ".$send_message_1.";";
        $myfile = file_put_contents('schedule.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        fclose($myfile);

    }
    //two mails
    if($no_of_mails >= 4){
        $day_value = $interval * 4;
        $query_1 = "SELECT u.first_name, u.email
                FROM user_edu_exercise_log AS ueel
                INNER JOIN user AS u ON ueel.user_code = u.user_code
                INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                WHERE (DATEDIFF('$today', STR_TO_DATE(ueel.created, '%Y-%m-%d')) = $day_value) AND ueel.lesson_id = 1 AND uefp.user_code IS NULL AND u.user_code NOT IN
                (
                    SELECT u.user_code
                    FROM user_edu_exercise_log AS ueel
                    INNER JOIN user AS u ON ueel.user_code = u.user_code
                    INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id
                    INNER JOIN admin AS a ON ao.admin_code = a.admin_code
                    LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code
                    WHERE ueel.lesson_id IN (2, 3, 4, 5) AND uefp.user_code IS NULL
                )
                GROUP BY ueel.user_code ORDER BY u.academy_signup DESC, u.last_name ASC";
        $mail = $db_handle->sanitizePost($_POST['mail_4']);
        $get_mail_1 =
            <<<MAIL
        $mail
MAIL;
        $my_subject_1 = $db_handle->sanitizePost($_POST['subject_4']);
        $send_message_1 = "student_auto_mail_send(\"$query_1\", \"$my_subject_1\", \"$get_mail_1\")";
        $txt = "$"."send_message_1d = ".$send_message_1.";";
        $myfile = file_put_contents('schedule.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
        fclose($myfile);

    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Signal Daily</title>
        <meta name="title" content="Instaforex Nigeria | Admin" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <?php require_once 'layouts/head_meta.php'; ?>
        <script src="tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
            tinyMCE.init({
                selector: "textarea#content",
                height: 500,
                theme: "modern",
                relative_urls: false,
                remove_script_host: false,
                convert_urls: true,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
                ],
                toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2: "| responsivefilemanager print preview media | forecolor backcolor emoticons",
                image_advtab: true,
                external_filemanager_path: "../filemanager/",
                filemanager_title: "Instafxng Filemanager",
//                external_plugins: { "filemanager" : "../filemanager/plugin.min.js"}

            });
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
                            <h4><strong>System Generated mails</strong></h4>
                        </div>
                    </div>

                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p>Carefully Schedule system generated mails</p>
                                <br/>
                                <div class="col-md-12 order-md-1">
                                    <form class="needs-validation" role="form" method="post" action="">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location"></label>
                                            <div class="col-sm-6 col-lg-5">
                                                <div class="input-group date">
                                                    <input name="comment" class="form-control" id="exampleFormControlTextarea1" placeholder="Schedule Title"/>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location">Select Category </label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class="input-group date">
                                                    <select name="query" class="form-control" id="location">
                                                        <?php
                                                        $query = "SELECT * FROM signal_symbol ";
                                                        $result = $db_handle->runQuery($query);
                                                        $result = $db_handle->fetchAssoc($result);
                                                        foreach ($result as $row)
                                                        {
                                                            extract($row)
                                                            ?>
                                                            <option value="<?php echo $symbol;?>"><?php echo $symbol;?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="input-group-addon"><span class="fa fa-group"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location">Number of mails</label>
                                            <div class="col-sm-9 col-lg-5">
                                                <div class="input-group date">
                                                    <select name="no_of_mails" id="no_of_mails" class="form-control" onchange="displayOptions()">
                                                        <option value=""></option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                    <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="location">Interval</label>
                                            <div class="col-sm-6 col-lg-5">
                                                <div class="input-group date">
                                                    <input type="number" name="interval" class="form-control"  placeholder="Interval"/>
                                                    <span class="input-group-addon">Days</span>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br/>
                                <div class="form-group" id="mail1" style="display:none;">
                                    <label class="control-label col-sm-2" for="content">Mail 1 Subject:</label>
                                    <div class="col-sm-10">
                                    <input type="text" name="subject_1" class="form-control" />
                                    </div>
                                    </br>
                                    <label class="control-label col-sm-2" for="content">Mail 1:</label>
                                        <div class="col-sm-10">
                                        <textarea name="mail_1" id="content" rows="3" class="form-control">
                                            <?php $myfile = fopen("..\mail_templates\Template2.html", "r") or die("Unable to open file!");
                                            echo fread($myfile,filesize("..\mail_templates\Template2.html"));
                                            fclose($myfile);  ?>
                                        </textarea></div>
                                    <p>.</p>
                                </div>

                                <br>
                                </br>
                                <div class="form-group" id="mail2" style="display:none;">
                                    <label class="control-label col-sm-2" for="content">Mail 2 Subject:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="subject_2" class="form-control" />
                                    </div>
                                    </br>
                                    <label class="control-label col-sm-2" for="content">Mail 2:</label>
                                    <div class="col-sm-10">
                                        <textarea name="mail_2" id="content" rows="3" class="form-control">
                                            <?php $myfile = fopen("..\mail_templates\Template2.html", "r") or die("Unable to open file!");
                                            echo fread($myfile,filesize("..\mail_templates\Template2.html"));
                                            fclose($myfile);  ?>
                                        </textarea></div>
                                    <p>.</p>
                                </div>

                                <br>
                                </br>
                                <div class="form-group" id="mail3" style="display:none;">
                                    <label class="control-label col-sm-2" for="content">Mail 3 Subject:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="subject_3" class="form-control" />
                                    </div>
                                    </br>
                                    <label class="control-label col-sm-2" for="content">Mail 3:</label>
                                    <div class="col-sm-10">
                                        <textarea name="mail_3" id="content" rows="3" class="form-control">
                                            <?php $myfile = fopen("..\mail_templates\Template2.html", "r") or die("Unable to open file!");
                                            echo fread($myfile,filesize("..\mail_templates\Template2.html"));
                                            fclose($myfile);  ?>
                                        </textarea></div>
                                    <p>.</p>
                                </div>

                                <br>
                            </br>
                                <div class="form-group" id="mail4" style="display:none;">
                                    <label class="control-label col-sm-2" for="content">Mail 4 Subject:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="subject_4" class="form-control" />
                                    </div>
                                    </br>
                                    <label class="control-label col-sm-2" for="content">Mail 4:</label>
                                    <div class="col-sm-10">
                                        <textarea name="mail_4" id="content" rows="3" class="form-control">
                                            <?php $myfile = fopen("..\mail_templates\Template2.html", "r") or die("Unable to open file!");
                                            echo fread($myfile,filesize("..\mail_templates\Template2.html"));
                                            fclose($myfile);  ?>
                                        </textarea></div>
                                </div>
                                        <hr class="mb-4">
                                <center><button name="mail" class="btn btn-success btn-sm bottom" type="submit">Submit Schedule</button></center>
                                    </form>
                                </div>
                        </div>
                    </div>

                </div>

                    <!-- Unique Page Content Ends Here
                    ================================================== -->

                </div>

            </div>
        <?php require_once 'layouts/footer.php'; ?>
<script>
    function displayOptions() {
        var options = $("#no_of_mails").val();
        console.log(options);
        if(options == 1){
            document.getElementById("mail1").style.display = "block";
            document.getElementById("mail2").style.display = "none";
            document.getElementById("mail3").style.display = "none";
            document.getElementById("mail4").style.display = "none";
            }
        else if(options == 2){
            document.getElementById("mail1").style.display = "block";
            document.getElementById("mail2").style.display = "block";
            document.getElementById("mail3").style.display = "none";
            document.getElementById("mail4").style.display = "none";
            }
        else if(options == 3){
            document.getElementById("mail1").style.display = "block";
            document.getElementById("mail2").style.display = "block";
            document.getElementById("mail3").style.display = "block";
            document.getElementById("mail4").style.display = "none"
        }

    else if(options == 4){
            document.getElementById("mail1").style.display = "block";
            document.getElementById("mail2").style.display = "block";
            document.getElementById("mail3").style.display = "block";
            document.getElementById("mail4").style.display = "block";
        }
        else{
            document.getElementById("mail1").style.display = "none";
            document.getElementById("mail2").style.display = "none";
            document.getElementById("mail3").style.display = "none";
            document.getElementById("mail4").style.display = "none";
        }
    }
</script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
        <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    </body>
</html>

