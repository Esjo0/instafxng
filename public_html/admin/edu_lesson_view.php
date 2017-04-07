<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['x', 'cid', 'lid', 'eid']);
$course_id_encrypted = $get_params['cid'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$course_lesson_id_encrypted = $get_params['lid'];
$course_lesson_id = decrypt(str_replace(" ", "+", $course_lesson_id_encrypted));
$course_lesson_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_lesson_id);


$selected_course = $education_object->get_course_by_id($course_id);

if(empty($selected_course)) {
    redirect_to("edu_course.php"); // cannot find course or URL tampered
}

$selected_lesson = $education_object->get_single_course_lesson_id($course_lesson_id);

if(empty($selected_lesson)) {
    $back_url = "edu_course_view.php?id=" . encrypt($course_id);
    redirect_to($back_url); // cannot find lesson or URL tampered
} else {
    $lesson_exercises = $education_object->get_lessons_exercises_id($course_lesson_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Lesson</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Lesson" />
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
                            <h4><strong>VIEW LESSON</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="edu_course_view.php?id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="All Courses"><i class="fa fa-arrow-circle-left"></i> Go Back To Course</a></p>
                                <p><strong><span class="text-danger">Course Title:</span> <?php echo $selected_course['title']; ?></strong></p>
                                <p><strong><span class="text-danger">Lesson:</span> <?php echo $selected_lesson['title']; ?></strong></p>


                                <p>View details of the selected lesson below, modify exercise associated with the lesson</p>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <hr /><br />
                                        <!-- Display list of lessons under the course -->
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Author</th>
                                                <th>Question</th>
                                                <th>Option A</th>
                                                <th>Option B</th>
                                                <th>Option C</th>
                                                <th>Option D</th>
                                                <th>Right Option</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($lesson_exercises) && !empty($lesson_exercises)) { foreach ($lesson_exercises as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['admin_full_name']; ?></td>
                                                    <td><?php echo $row['question']; ?></td>
                                                    <td><?php echo $row['option_a']; ?></td>
                                                    <td><?php echo $row['option_b']; ?></td>
                                                    <td><?php echo $row['option_c']; ?></td>
                                                    <td><?php echo $row['option_d']; ?></td>
                                                    <td><?php echo $row['right_option']; ?></td>
                                                    <td class="nowrap">
                                                        <a title="Edit Exercise" class="btn btn-default" href="edu_exercise_new.php?eid=<?php echo encrypt($row['edu_lesson_exercise_id']); ?>&cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($course_lesson_id); ?>&x=edit"><i class="fa fa-edit"></i> </a>
                                                    </td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='8' class='text-danger'><em>No exercise found for this lesson</em></td></tr>"; } ?>
                                            </tbody>
                                        </table>

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