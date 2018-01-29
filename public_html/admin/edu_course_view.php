<?php
require_once("../init/initialize_admin.php");
if (!$session_admin->is_logged_in()) {
    redirect_to("login.php");
}

if (isset($_POST['delete_lesson'])) {

    foreach($_POST as $key => $value) {
        $_POST[$key] = $db_handle->sanitizePost(trim($value));
    }

    extract($_POST);

    $education_object->delete_lesson($lesson_id);
}

$get_params = allowed_get_params(['id']);
$course_id_encrypted = $get_params['id'];
$course_id = decrypt(str_replace(" ", "+", $course_id_encrypted));
$course_id = preg_replace("/[^A-Za-z0-9 ]/", '', $course_id);

$selected_course = $education_object->get_course_by_id($course_id);

if(empty($selected_course)) {
} else
    {
    $course_lessons = $education_object->get_active_lessons_by_id($course_id);
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

                                                    <tbody>
                                                        <tr><td><b>Author</b></td><td><?php echo $selected_course['admin_full_name']; ?></td></tr>
                                                        <tr><td><b>Course Code</b></td><td><?php echo $selected_course['course_code']; ?></td></tr>
                                                        <tr><td><b>Course Order</b></td><td><?php echo $selected_course['course_order']; ?></td></tr>
                                                        <tr><td><b>Title</b></td><td><?php echo $selected_course['title']; ?></td></tr>
                                                        <tr><td><b>Description</b></td><td><?php echo $selected_course['description']; ?></td></tr>
                                                        <tr><td><b>Course Cost (&#8358;)</b></td><td><?php echo $selected_course['course_cost']; ?></td></tr>
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
                                                <th>&nbsp;</th>
                                                <th>Author</th>
                                                <th>Title</th>
                                                <th>Order</th>
                                                <th>Exercise Count</th>
                                                <th>Rating</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($course_lessons) && !empty($course_lessons)) { foreach ($course_lessons as $row)
                                            {?>
                                                <tr>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">Action <span class="caret"></span></a>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                                                <li role="presentation"><a role="menuitem" tabindex="-1" title="View Lesson Details" href="edu_lesson_view.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-eye fa-fw"></i> View Lesson</a></li>
                                                                <li role="presentation"><a role="menuitem" tabindex="-1" title="Edit Lesson" href="edu_lesson_new.php?cid=<?php echo encrypt($course_id); ?>&x=edit&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-edit fa-fw"></i> Edit Lesson</a></li>
                                                                <li role="presentation"><a role="menuitem" tabindex="-1" title="Create Exercise" href="edu_exercise_new.php?cid=<?php echo encrypt($course_id); ?>&lid=<?php echo encrypt($row['edu_lesson_id']); ?>"><i class="fa fa-question-circle fa-fw"></i> Create Exercise</a></li>
                                                                <li role="presentation">
                                                                    <a role="menuitem" tabindex="-1" data-toggle="modal" data-target="#delete-lesson-confirm<?php echo $row['edu_lesson_id']; ?>" title="Delete Lesson" href="#"><i class="fa fa-trash fa-fw"></i> Delete Lesson</a>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <div id="delete-lesson-confirm<?php echo $row['edu_lesson_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" data-dismiss="modal" aria-hidden="true"
                                                                                class="close">&times;</button>
                                                                        <h4 class="modal-title">Delete Lesson Confirmation</h4></div>
                                                                    <div class="modal-body">
                                                                        <p>Do you want to delete this lesson? This action cannot be reversed.</p>
                                                                        <p>All exercises associated with the lesson will also be deleted.</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form class="form-horizontal" role="form" method="post" action="">
                                                                            <input type="hidden" name="lesson_id" value="<?php echo $row['edu_lesson_id']; ?>" />
                                                                            <input name="delete_lesson" type="submit" class="btn btn-danger" value="Delete Lesson">
                                                                            <button type="submit" name="decline" data-dismiss="modal" class="btn btn-default">Cancel</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $row['admin_full_name']; ?></td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php echo $row['lesson_order']; ?></td>
                                                    <td><?php echo $db_handle->numRows("SELECT edu_lesson_exercise_id FROM edu_lesson_exercise WHERE lesson_id = {$row['edu_lesson_id']}"); ?></td>
                                                    <?php
                                                    $query = "SELECT * FROM edu_lesson_rating WHERE lesson_id = {$row['edu_lesson_id']} AND course_id = $course_id ";
                                                    $result = $db_handle->runQuery($query);
                                                    $result = $db_handle->fetchAssoc($result);
                                                    $rating = array();
                                                    foreach ($result as $row1)
                                                    {
                                                        $rating[] = $row1['rating'];
                                                    }
                                                    $rating = modes_of_array($rating);
                                                    $ratings = array_sum($rating);
                                                    $num_of_items = count($rating);
                                                    $average_rating = $ratings / $num_of_items;
                                                    ?>
                                                    <td><?php echo lesson_rating(ceil($average_rating)); ?></td>
                                                    <td><?php echo status_edu_lesson($row['status']); ?></td>

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