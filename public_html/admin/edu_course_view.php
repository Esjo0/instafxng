<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

$get_params = allowed_get_params(['id']);
$course_id_encrypted = $get_params['id'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$selected_course = $education_object->get_course_by_id($course_id);

if(empty($selected_course)) {
    redirect_to("edu_course.php"); // cannot find course or URL tampered
} else {
    $course_lessons = $education_object->get_course_lessons_id($course_id);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base target="_self">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Instaforex Nigeria | Admin - View Course</title>
        <meta name="title" content="Instaforex Nigeria | Admin - View Course" />
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
                            <h4><strong>VIEW COURSE</strong></h4>
                        </div>
                    </div>
                    
                    <div class="section-tint super-shadow">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php require_once 'layouts/feedback_message.php'; ?>
                                <p><a href="edu_course.php" class="btn btn-default" title="All Courses"><i class="fa fa-arrow-circle-left"></i> All Courses</a></p>
                                <p>View details of the selected course below</p>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- Display Course Details -->

                                                <p><a href="edu_course_new.php?x=edit&id=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Edit Course"><i class="fa fa-edit"></i> Edit Course</a></p>
                                                <table class="table table-responsive table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr><td>Author</td><td><?php echo $selected_course['admin_full_name']; ?></td></tr>
                                                        <tr><td>Course Code</td><td><?php echo $selected_course['course_code']; ?></td></tr>
                                                        <tr><td>Course Order</td><td><?php echo $selected_course['course_order']; ?></td></tr>
                                                        <tr><td>Title</td><td><?php echo $selected_course['title']; ?></td></tr>
                                                        <tr><td>Description</td><td><?php echo $selected_course['description']; ?></td></tr>
                                                        <tr><td>Course Cost (&#8358;)</td><td><?php echo $selected_course['course_cost']; ?></td></tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-sm-6">
                                                <!-- Display Course Metrics and button to create lesson -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <p class="text-right"><a href="edu_lesson_new.php?cid=<?php echo encrypt($course_id); ?>" class="btn btn-default" title="Create new lesson for this course">Create New Lesson <i class="fa fa-arrow-circle-right"></i></a></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="super-shadow metric-board">
                                                            <header class="text-center"><strong>Total Lesson</strong></header>
                                                            <article class="text-center">
                                                                <strong><?php echo $db_handle->numRows("SELECT edu_lesson_id FROM edu_lesson WHERE course_id = $course_id");?></strong>
                                                            </article>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <hr />
                                        <p>Below is the list of lessons in this course.</p>
                                        <!-- Display list of lessons under the course -->
                                        <table class="table table-responsive table-striped table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Author</th>
                                                <th>Title</th>
                                                <th>Order</th>
                                                <th>Exercise Count</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($course_lessons) && !empty($course_lessons)) { foreach ($course_lessons as $row) { ?>
                                                <tr>
                                                    <td><?php echo $row['admin_full_name']; ?></td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['lesson_order']; ?></td>
                                                    <td><?php echo $db_handle->numRows("SELECT edu_lesson_exercise_id FROM edu_lesson_exercise WHERE lesson_id = {$row['edu_lesson_id']}"); ?></td>
                                                    <td><?php echo status_edu_lesson($row['status']); ?></td>
                                                    <td class="nowrap">
                                                        <a title="View Lesson Details" class="btn btn-info" href="edu_lesson_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-eye"></i> </a>
                                                        <a title="Edit Lesson" class="btn btn-default" href="edu_lesson_new.php?cid=<?php echo encrypt($course_id); ?>&x=edit&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-edit"></i> </a>
                                                        <a title="Create Exercise" class="btn btn-success" href="edu_exercise_new.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-question-circle"></i> </a>
                                                    </td>
                                                </tr>
                                            <?php } } else { echo "<tr><td colspan='6' class='text-danger'><em>No lesson found for this course</em></td></tr>"; } ?>
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