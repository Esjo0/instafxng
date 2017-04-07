<?php
require_once '../init/initialize_client.php';
$thisPage = "";

if (!$session_client->is_logged_in()) {
    redirect_to("login.php");
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
                                <p>Welcome <?php echo $_SESSION['client_first_name']; ?>,</p>
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

                                <?php if($course_completed) { ?>
                                <h4>Congratulations!</h4>
                                <p><?php echo $_SESSION['client_first_name']; ?>, you have completed all the available courses in the
                                <strong>Forex Profit Academy</strong></p>
                                <p>You can now comfortably take live trades in the Forex Market and make as much profit as you desire.
                                At this point, you qualify for our 100% education bonus* for funding your account.</p>
                                <p><a class="btn btn-success btn-lg" href="https://instafxng.com/deposit.php">Fund Account - Get Bonus Now</a></p>
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


<!--                                <h4>Certification</h4>-->
<!--                                <p>Well, you are learning Forex Trading to make more money, buy hey! You would also-->
<!--                                earn our esteemed Forex Trading certificates for each course you complete.</p>-->
<!--                                <p>See your progress for the current course below.</p>-->
<!--                                <div id="custom-progress-bar">-->
<!--                                    <div class="progress" style="margin-bottom: 0 !important">-->
<!--                                        <div class="progress-bar progress-bar-striped active" role="progressbar"-->
<!--                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">-->
<!--                                            40%-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
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