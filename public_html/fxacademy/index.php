<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {

    // Check whether this client is following link in an email to get to course messages
    $get_params = allowed_get_params(['se', 'sid', 'c']);

    $control_code_encrypted = $get_params['c'];
    $control_code = decrypt(str_replace(" ", "+", $control_code_encrypted));
    $control_code = preg_replace("/[^A-Za-z0-9 ]/", '', $control_code);

    if(!empty($get_params['se']) && !empty($get_params['sid']) && $control_code == "1") {

        $student_email = $get_params['se'];

        $support_id_encrypted = $get_params['sid'];

        $client_operation = new clientOperation();
        $user_code = $client_operation->get_user_by_email($student_email);

        if(empty($user_code) || !isset($user_code)) { redirect_to("register.php?id=$student_email"); }

        $user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);

        if($user_ifx_details) {
            $found_user = array(
                'user_code' => $user_ifx_details['client_user_code'],
                'status' => $user_ifx_details['client_status'],
                'first_name' => $user_ifx_details['client_first_name'],
                'last_name' => $user_ifx_details['client_last_name'],
                'email' => $user_ifx_details['client_email']
            );
            $session_client->login($found_user);

            redirect_to("support_message.php?id=" . $support_id_encrypted);

        }
    } else {
        redirect_to("login.php");
    }
} else {

    // TODO: Refactor this

    // Check whether this client is following link in an email to get to course messages
    $get_params = allowed_get_params(['se', 'sid', 'c']);

    $control_code_encrypted = $get_params['c'];
    $control_code = decrypt(str_replace(" ", "+", $control_code_encrypted));
    $control_code = preg_replace("/[^A-Za-z0-9 ]/", '', $control_code);

    if(!empty($get_params['se']) && !empty($get_params['sid']) && $control_code == "1") {

        $student_email = $get_params['se'];

        $support_id_encrypted = $get_params['sid'];

        $client_operation = new clientOperation();
        $user_code = $client_operation->get_user_by_email($student_email);

        if(empty($user_code) || !isset($user_code)) { redirect_to("register.php?id=$student_email"); }

        $user_ifx_details = $client_operation->get_user_by_code($user_code['user_code']);

        if($user_ifx_details) {
            $found_user = array(
                'user_code' => $user_ifx_details['client_user_code'],
                'status' => $user_ifx_details['client_status'],
                'first_name' => $user_ifx_details['client_first_name'],
                'last_name' => $user_ifx_details['client_last_name'],
                'email' => $user_ifx_details['client_email']
            );
            $session_client->login($found_user);

            redirect_to("support_message.php?id=" . $support_id_encrypted);

        }
    }
}

$get_course_library = $education_object->get_courses_attempted($_SESSION['client_unique_code']);
$get_learning_position = $education_object->get_learning_position($_SESSION['client_unique_code']);

if($get_learning_position) {
    // determine the next lesson
    $all_lessons = $education_object->get_active_lessons_by_id($get_learning_position['course_id']);

    $lesson_position = 0;
    foreach($all_lessons AS $row) {
        if($row['edu_lesson_id'] == $get_learning_position['lesson_id']) { $stop_lesson_position = $lesson_position; }
        $lesson_position++;
    }

    $next_lesson_position = $stop_lesson_position + 1;
    $next_lesson = $all_lessons[$next_lesson_position];

    if($next_lesson) {
        // Get the lesson
        $go_to_next_lesson = true;

    } else {
        // Jump to the next course
        $all_courses = $education_object->get_all_active_course();

        $course_position = 0;
        foreach($all_courses AS $row) {
            if($row['edu_lesson_id'] == $get_learning_position['course_id']) { $stop_course_position = $course_position; }
            $course_position++;
        }

        $next_course_position = $stop_course_position + 1;
        $next_course = $all_courses[$next_course_position];

        if($next_course) {
            // Get the lesson
            $first_lesson_course = $education_object->get_first_lesson_in_course($next_course['edu_course_id']);
            $go_to_next_course = true;
        } else {
            $course_completed = true;
        }

    }

} else {
    $first_time_user = true;
    $first_time_course = $education_object->get_first_school_course();
}

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
                            <div class="col-md-12">
                                <p><strong>Welcome <?php echo $_SESSION['client_first_name']; ?>,</strong></p>
                                <p>This is your dashboard where you can check your learning progress, latest message from your instructor
                                and other quick information about your study at our Forex Trading School.</p>
                                <p>Learning Forex Trading is easy and fun, you would be happy to discover new and exciting ways to
                                make money from the Forex Market and become financially free.</p>
                                <hr />

                                <?php if($first_time_user) { ?>
                                <h4>Let's get started</h4>
                                <p><?php echo $_SESSION['client_first_name']; ?>, in your study plan, the title of your first course is "<strong><?php echo $first_time_course['title']; ?></strong>".
                                This is where it all begins. Are you ready?</p>
                                <p><a title="Click to start learning" href="fxacademy/course_view.php?id=<?php echo encrypt($first_time_course['edu_course_id']); ?>" class="btn btn-primary btn-lg">Start</a></p>
                                <hr />
                                <?php } ?>

                                <?php if($go_to_next_lesson) { ?>
                                <h4>Well done!</h4>
                                <p><?php echo $_SESSION['client_first_name']; ?>, you are making a good progress already. In your study plan, the title of your next lesson is "<strong><?php echo $next_lesson['title']; ?></strong>".</p>
                                <p><a title="Continue learning" href="fxacademy/lesson_view.php?cid=<?php echo encrypt($next_lesson['course_id']); ?>&lid=<?php echo encrypt($next_lesson['edu_lesson_id']); ?>" class="btn btn-primary btn-lg">Continue</a></p>
                                <hr />
                                <?php } ?>

                                <?php if($go_to_next_course) { ?>
                                <h4>Bravo!</h4>
                                <p><?php echo $_SESSION['client_first_name']; ?>, your progress is encouraging. In your study plan, the title of your next course is "<strong><?php echo $first_lesson_course['course_title']; ?></strong>".</p>
                                <p><a title="Continue learning" href="fxacademy/course_view.php?id=<?php echo encrypt($first_lesson_course['course_id']); ?>" class="btn btn-primary btn-lg">Continue</a></p>
                                <hr />
                                <?php } ?>

                                <?php if($get_course_library) { ?>
                                <h4>Course Library</h4>
                                <p>Below is the list of courses you have enrolled for.</p>
                                <ul class="list-group">
                                    <?php foreach($get_course_library AS $row) { ?>
                                        <li class="list-group-item list-group-item-warning"><a title="Click to view course again" href="fxacademy/course_view.php?id=<?php echo encrypt($row['edu_course_id']); ?>"><?php echo $row['title']; ?></a></li>
                                    <?php } ?>
                                </ul>
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