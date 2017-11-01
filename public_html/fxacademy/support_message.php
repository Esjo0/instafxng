<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
//    redirect_to("login.php");
}

if (isset($_POST['submit_reply'])) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }
    extract($_POST);

    $support_id = decrypt(str_replace(" ", "+", $support_id));
    $support_id = preg_replace("/[^A-Za-z0-9 ]/", '', $support_id);

    $question_reply = $education_object->set_lesson_support_reply('1', $support_id, $comment_reply, $_SESSION['client_unique_code'], '1');

    if($question_reply) {
        $message_success = "You have successfully submitted a reply to this support thread.";
    } else {
        $message_error = "There seems to be a problem somewhere, your reply could not be saved. Please try again.";
    }
}

$get_params = allowed_get_params(['id']);
$support_request_encrypted = $get_params['id'];
$support_request_code = decrypt(str_replace(" ", "+", $support_request_encrypted));
$support_request_code = preg_replace("/[^A-Za-z0-9 ]/", '', $support_request_code);

if(!empty($support_request_code)) {
    $selected_support = $education_object->get_support_request_by_code($support_request_code);

    if(!empty($selected_support)) {
        $selected_responses = $education_object->get_support_answers_by_id($selected_support['user_edu_support_request_id']);
    }
 }

$all_support_request = $education_object->get_all_support_request_by_id($_SESSION['client_unique_code']);
array_sort_by_column_desc($all_support_request, 'last_reply_date');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria</title>
        <meta name="title" content="" />
        <meta name="keywords" content="">
        <meta name="description" content="">
        <?php require_once 'layouts/head_meta.php'; ?>
    </head>
    <body>
        <?php require_once 'layouts/header.php'; ?>

        <!-- Main Body: The is the main content area of the web site, contains a side bar  -->
        <div id="main-body" class="container-fluid">
            <div class="row no-gutter">

                <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
                <div id="main-body-content-area" class="col-md-12">

                    <!-- Unique Page Content Starts Here
                    ================================================== -->
                    <?php require_once 'layouts/navigation.php'; ?>

                    <div id="main-container" class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Support Messages</h3>
                                <hr />
                                <p>Whenever you request for support on any of the lessons, you will receive
                                all responses here. Click on your support request on the left to read the thread
                                and also post a new reply.</p>
                                <p>When an admin has responded to your question, the status will be closed.</p>
                            </div>

                            <div class="col-sm-5" style="max-height: 900px; overflow: auto; border: 0;">

                                <div class="list-group">
                                <?php if($all_support_request) {
                                    foreach($all_support_request as $row) { ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="<?php if($row['support_request_code'] == $support_request_code) { echo 'transaction-remarks-1'; } else { echo 'transaction-remarks'; } ?>">
                                                    <span id="trans_remark"><?php echo substr(trim($row['request']), 0, 150); ?>...
                                                        <a href="fxacademy/support_message.php?id=<?php echo encrypt($row['support_request_code']); ?>">View Replies</a>
                                                        <br />
                                                        <strong>Status: <?php if($row['status'] == '1') { echo 'Open'; } else { echo 'Closed'; } ?></strong>
                                                        <hr /></span>
                                                    <?php
                                                    if(!is_null($row['last_reply_date'])) {
                                                        if($row['status'] == '2') {
                                                            echo "<em>Last reply by Admin on " . datetime_to_text($row['last_reply_date']) . "</em>";
                                                        } else {
                                                            echo "<em>You replied last on " . datetime_to_text($row['last_reply_date']) . "</em>";
                                                        }
                                                    } else {
                                                        echo "<em>No reply yet</em>";
                                                    }

                                                    ?>
                                                    <span id="trans_remark">

                                                    </span>
                                                    <span id="trans_remark_date"><?php echo datetime_to_text($row['created']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                <?php } } else { ?>
                                    <div class="alert alert-warning">
                                        <p>You do not have any support request.</p>
                                    </div>
                                <?php } ?>
                                </div>

                                <hr />

                            </div>

                            <div class="col-sm-7" style="max-height: 900px; overflow: auto; border: 0;">

                                <?php if(!empty($selected_support)) { ?>
                                    <p>Question:</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="transaction-remarks-1">
                                                <span id="trans_remark_author"><strong>You</strong></span>
                                                <span id="trans_remark"><?php echo $selected_support['request']; ?><hr /></span>
                                                <span id="trans_remark">
                                                    <strong>Course Title:</strong> <?php echo $selected_support['course_title']; ?><br />
                                                    <strong>Lesson Title:</strong> <?php echo $selected_support['lesson_title']; ?>
                                                </span>
                                                <span id="trans_remark_date"><?php echo datetime_to_text($selected_support['created']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />

                                    <p>Responses:</p>
                                    <table class="table table-responsive table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($selected_responses)) {
                                                foreach($selected_responses as $row) { ?>
                                                            <?php if($row['category'] == '1') { $full_name = $education_object->get_client_detail_by_code($row['author']); ?>
                                                                <tr>
                                                                    <td><img src="images/support_reply_customer.png" class="img-responsive center-block" /></td>
                                                                    <td>
                                                                        <span style="color: #4EA0AE !important; font-weight: bold;">
                                                                            <?php echo $full_name['full_name']; ?>
                                                                        </span><br />
                                                                        <span style="font-size: 0.8em !important; color: #4EA0AE !important; font-weight: bold;">
                                                                            <?php echo datetime_to_text($row['created']); ?>
                                                                        </span>
                                                                        <br /><hr />
                                                                        <?php echo $row['response']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>

                                                            <?php if($row['category'] == '2') { $full_name = $education_object->get_admin_detail_by_code($row['author']); ?>
                                                                <tr>
                                                                    <td><img src="images/support_reply_staff.png" class="img-responsive center-block" /></td>
                                                                    <td>
                                                                        <span style="color: #4EA0AE !important; font-weight: bold;">
                                                                            <?php echo $full_name['admin_first_name']; ?>
                                                                        </span><br />
                                                                        <span style="font-size: 0.8em !important; color: #4EA0AE !important; font-weight: bold;">
                                                                            <?php echo datetime_to_text($row['created']); ?>
                                                                        </span>
                                                                        <br /><hr />
                                                                        <?php echo $row['response']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                                <?php } } else { ?>
                                                <p class="text-danger"><em>There are no responses to your support request yet.</em></p>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <hr />
                                    <form class="form-horizontal" role="form" method="post" action="">
                                        <input type="hidden" name="support_id" value="<?php echo encrypt($selected_support['user_edu_support_request_id']); ?>" />
                                        <div class="form-group text-center">
                                            <label for="question">Post a reply:</label>
                                            <div class="col-sm-12"><textarea name="comment_reply" class="form-control" rows="5" id="question"></textarea></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12"><input name="submit_reply" type="submit" class="btn btn-success" value="Submit Reply" /></div>
                                        </div class="form-group">
                                    </form>

                                <?php } ?>

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