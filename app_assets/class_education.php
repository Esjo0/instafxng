<?php

class Education {

    // Log course deposit attempt
    public function log_course_deposit($user_code, $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit, $client_name, $client_email) {
        global $db_handle;
        global $system_object;

        $query = "INSERT INTO user_edu_deposits (user_code, trans_id, course_id, amount, stamp_duty,
            gateway_charge, pay_method, deposit_origin) VALUES ('$user_code', '$trans_id', $course_id, $course_cost, $stamp_duty, $card_processing, '$pay_type', '$origin_of_deposit')";

        $db_handle->runQuery($query);

        $total_payment = $course_cost + $stamp_duty + $card_processing;
        $total_payment = number_format($total_payment, 2, ".", ",");

        // Send order invoice to client email address
        $subject = "Forex Profit Optimizer Course Order Invoice - " . $trans_id;
        $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $client_name,</p>

            <p>NOTE: This is a CONFIDENTIAL Document. Information herein should
            never be shared with anyone.</p>

            <p>THIS INVOICE IS VALID ONLY FOR 24 HOURS. IF PAYMENT IS NOT MADE BY THEN,
            YOU MUST SUBMIT ANOTHER ORDER.</p>

            <p>====================</p>

            <p>Your Transaction ID for the Forex Profit Optimizer Course is [$trans_id]</p>

            <p>The details of your order are as follows:</p>

            <p>Payment for Forex Profit Optimizer Course: (N $total_payment)</p>

            <p>To complete your order, please make your payment as follows:</p>

            <p>Pay (N $total_payment) into our account listed below.</p>

            <p style="color: red">NOTE: Kindly make sure you pay into the account stated below.</p>

            <ol>
                <li>Any Branch of Guaranty Trust Bank (GTB)<br />
                Account Name: Instant Web-Net Technologies Ltd<br />
                Account Number: 0174516696
                </li>
                <li>After making the payment, visit <a href='https://instafxng.com/fxacademy'>https://instafxng.com/fxacademy</a> and click on NOTIFICATION.</li>
                <li>Fill in the column as stated on the page.</li>
            </ol>

            <p>Upon receipt of payment, you will be granted access to the Forex Profit Optimizer Course</p>

            <p>NOTE:</p>
            <ul>
                <li>Third party payments are not allowed.</li>
                <li>When making payment through internet banking platform, fill in your transaction ID $trans_id
                in the REMARK column.</li>
                <li>You will only be given access to the Forex Profit Optimizer course after you have completed
                <a href='https://instafxng.com/fxacademy'>payment notification</a> as advised in (2) above.</li>
            </ul>


            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        $system_object->send_email($subject, $body, $client_email, $client_name);


    }

    // Confirm if client has paid for a particular course
    public function confirm_course_payment($user_code, $course_id) {
        global $db_handle;

        $query = "SELECT * FROM user_edu_fee_payment WHERE user_code = '$user_code' AND course_id = $course_id LIMIT 1";
        return $db_handle->numRows($query) > 0 ? true : false;
    }

    // Get the lesson that the client stopped at
    public function get_learning_position($user_code) {
        global $db_handle;

        $query = "SELECT ueel.lesson_id, el.course_id, el.title AS lesson_title, ec.title AS course_title
              FROM user_edu_exercise_log AS ueel
              INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE ueel.user_code = '$user_code' ORDER BY ueel.created DESC LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get the next lesson position using course id and lesson id
    public function get_next_lesson($course_id, $course_lesson_id) {
        global $db_handle;

        $next_lesson_url = "";
        $next_lesson_name = "";

        $query = "SELECT el.edu_lesson_id
              FROM edu_lesson AS el
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE el.course_id = $course_id AND el.status = '2' ORDER BY el.lesson_order ASC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $selected_lessons = array();
        foreach ($fetched_data AS $key => $value) {
            $selected_lessons[] = $value['edu_lesson_id'];
        }

        $lesson_count = count($selected_lessons);
        $lesson_position = array_search($course_lesson_id, $selected_lessons);

        if(($lesson_position + 1) < $lesson_count) {
            $next_lesson_key = $lesson_position + 1;

            $next_lesson_url = "fxacademy/lesson_view.php?cid=" . encrypt($course_id) . "&lid=" . encrypt($selected_lessons[$next_lesson_key]);
            $next_lesson_name = "Next Lesson";
        } else {
            // Go to Course Level
            $query = "SELECT edu_course_id FROM edu_course WHERE status = '2' ORDER BY course_order ASC";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);

            $selected_courses = array();
            foreach ($fetched_data AS $key => $value) {
                $selected_courses[] = $value['edu_course_id'];
            }

            $course_count = count($selected_courses);
            $course_position = array_search($course_id, $selected_courses);

            if(($course_position + 1) < $course_count) {
                $next_course_key = $course_position + 1;

                $next_lesson_url = "fxacademy/course_view.php?id=" . encrypt($selected_courses[$next_course_key]);
                $next_lesson_name = "Next Course";
            } else {
                $next_lesson_url = "fxacademy/completion_cert.php";
                $next_lesson_name = "Course Completion";
            }
        }

        $next_lesson = array(
            'next_lesson_url' => $next_lesson_url,
            'next_lesson_name' => $next_lesson_name
        );

        return $next_lesson ? $next_lesson : false;
    }

    // Get the previous lesson position using course id and lesson id
    /**
     * @param $course_id
     * @param $course_lesson_id
     * @return array|bool
     */
    public function get_previous_lesson($course_id, $course_lesson_id) {
        global $db_handle;

        $previous_lesson_url = "";
        $previous_lesson_name = "";

        $query = "SELECT el.edu_lesson_id
              FROM edu_lesson AS el
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE el.course_id = $course_id AND el.status = '2' ORDER BY el.lesson_order ASC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        $selected_lessons = array();
        foreach ($fetched_data AS $key => $value) {
            $selected_lessons[] = $value['edu_lesson_id'];
        }

        $lesson_count = count($selected_lessons);
        $lesson_position = array_search($course_lesson_id, $selected_lessons);
        if(($lesson_position - 1) < $lesson_count) {
            if($selected_lessons[$lesson_position - 1]){
                $previous_lesson_key = $lesson_position - 1;
                $previous_lesson_url = "fxacademy/lesson_view.php?cid=" . encrypt($course_id) . "&lid=" . encrypt($selected_lessons[$previous_lesson_key]);
                $previous_lesson_name = "Previous Lesson";
            }

        }
        else {
            $previous_lesson_url = "";
            $previous_lesson_name = "";
            // Go to Course Level
            $query = "SELECT edu_course_id FROM edu_course WHERE status = '2' ORDER BY course_order ASC";
            $result = $db_handle->runQuery($query);
            $fetched_data = $db_handle->fetchAssoc($result);

            $selected_courses = array();
            foreach ($fetched_data AS $key => $value) {
                $selected_courses[] = $value['edu_course_id'];
            }

            $course_count = count($selected_courses);
            $course_position = array_search($course_id, $selected_courses);

            if(($course_position - 1) < $course_count) {
                if($selected_courses[$course_position - 1]) {
                    $previous_course_key = $course_position - 1;

                    $previous_lesson_url = "fxacademy/course_view.php?id=" . encrypt($selected_courses[$previous_course_key]);
                    $previous_lesson_name = "Previous Course";
                }
            } else {
                $previous_lesson_url = "fxacademy/completion_cert.php";
                $previous_lesson_name = "Course Completion";
            }
        }

        $previous_lesson = array(
            'previous_lesson_url' => $previous_lesson_url,
            'previous_lesson_name' => $previous_lesson_name
        );

        return $previous_lesson ? $previous_lesson : false;
    }

    // Get all the courses attempted by this client
    public function get_courses_attempted($user_code) {
        global $db_handle;

        $query = "SELECT ec.edu_course_id, ec.title
              FROM user_edu_exercise_log AS ueel
              INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE ueel.user_code = '$user_code' GROUP BY ec.edu_course_id ORDER BY ec.course_code ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // get first school course
    public function get_first_school_course() {
        global $db_handle;

        $query = "SELECT * FROM edu_course WHERE status = '2' ORDER BY course_order ASC LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // get first lesson in a course
    public function get_first_lesson_in_course($course_id) {
        global $db_handle;

        $query = "SELECT ec.title AS course_title, el.course_id
                FROM edu_lesson AS el
                INNER JOIN edu_course AS ec ON ec.edu_course_id = el.course_id
                WHERE el.course_id = $course_id AND el.status = '2' ORDER BY lesson_order ASC LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get course that a lesson belongs to
    public function get_course_by_lesson_id($lesson_id) {
        global $db_handle;

        $query = "SELECT ec.edu_course_id, ec.course_code, ec.course_cost, ec.title
              FROM edu_course AS ec
              INNER JOIN edu_lesson AS el ON ec.edu_course_id = el.course_id
              WHERE el.edu_lesson_id = $lesson_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Create new course
    public function create_new_course($c_course_no, $c_code, $c_order, $c_title, $c_describe, $c_cost, $c_status, $admin_code) {
        global $db_handle;

        if(!empty($c_course_no)) {
            $query = "UPDATE edu_course SET course_code = '$c_code', course_order = $c_order, title = '$c_title', description = '$c_describe', course_cost = $c_cost, status = '$c_status'
                WHERE edu_course_id = $c_course_no LIMIT 1";
        } else {
            $query = "INSERT INTO edu_course (admin_code, course_code, course_order, title, description, course_cost, status)
            VALUES ('$admin_code', '$c_code', $c_order, '$c_title', '$c_describe', $c_cost, '$c_status')";

        }

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get course by id
    public function get_course_by_id($course_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.first_name, SPACE(1), a.last_name) AS admin_full_name, ec.edu_course_id,
            ec.course_code, ec.course_order, ec.title, ec.description, ec.difficulty_level,
            ec.display_image, ec.intro_video_url, ec.time_required, ec.course_fee, ec.course_cost, ec.status, ec.created
            FROM edu_course AS ec
            INNER JOIN admin AS a ON a.admin_code = ec.admin_code
            WHERE edu_course_id = $course_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get course by id
    public function get_active_course_by_id($course_id) {
        global $db_handle;

        $query = "SELECT ec.edu_course_id, ec.course_code, ec.course_order, ec.title, ec.description, ec.difficulty_level,
            ec.display_image, ec.intro_video_url, ec.time_required, ec.course_fee, ec.course_cost, ec.status, ec.created
            FROM edu_course AS ec
            INNER JOIN admin AS a ON a.admin_code = ec.admin_code
            WHERE edu_course_id = $course_id AND ec.status = '2' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get all active courses
    public function get_all_active_course() {
        global $db_handle;

        $query = "SELECT * FROM edu_course WHERE status = '2' ORDER BY course_order ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data ? $fetched_data : false;
    }

    // Create new lesson
    public function create_new_lesson($l_lesson_no, $course_id, $l_order, $l_title, $l_content, $l_status, $admin_code) {
        global $db_handle;

        if(!empty($l_lesson_no)) {
            $query = "UPDATE edu_lesson SET lesson_order = $l_order, title = '$l_title', content = '$l_content', status = '$l_status'
                WHERE edu_lesson_id = $l_lesson_no LIMIT 1";
        } else {
            $query = "INSERT INTO edu_lesson (admin_code, course_id, lesson_order, title, content, status)
                VALUES ('$admin_code', $course_id, $l_order, '$l_title', '$l_content', '$l_status')";
        }

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    /**
     * Delete a particular lesson, associated exercises will also be deleted
     * @param $lesson_id
     * @return bool
     */
    public function delete_lesson($lesson_id) {
        global $db_handle;

        //Delete associated exercises
        $db_handle->runQuery("DELETE FROM edu_lesson_exercise WHERE lesson_id = $lesson_id LIMIT 1");

        //Delete lesson
        $db_handle->runQuery("DELETE FROM edu_lesson WHERE edu_lesson_id = $lesson_id LIMIT 1");

        return true;
    }

    // Get lessons belonging to a course by id
    public function get_course_lessons_id($course_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.first_name, SPACE(1), a.last_name) AS admin_full_name, el.edu_lesson_id, el.course_id,
            el.lesson_order, el.title, el.content, el.time_required, el.status, el.created
            FROM edu_lesson AS el
            INNER JOIN edu_course AS ec ON ec.edu_course_id = el.course_id
            INNER JOIN admin AS a ON a.admin_code = el.admin_code
            WHERE el.course_id = $course_id ORDER BY el.lesson_order ASC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get lessons belonging to a course by id
    public function get_active_lessons_by_id($course_id)
    {
        global $db_handle;

        $query = "SELECT CONCAT(a.first_name, SPACE(1), a.last_name) AS admin_full_name, el.edu_lesson_id, el.course_id, el.admin_code,
            el.lesson_order, el.title, el.content, el.time_required, el.status, el.created
            FROM edu_lesson AS el
            INNER JOIN edu_course AS ec ON ec.edu_course_id = el.course_id
            INNER JOIN admin AS a ON a.admin_code = el.admin_code
            WHERE el.course_id = $course_id ORDER BY el.lesson_order ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Check if client has taken assessment for the selected lesson
    public function taken_lesson_assessment($lesson_id, $user_code) {
        global $db_handle;

        $query = "SELECT * FROM user_edu_exercise_log WHERE user_code = '$user_code' AND lesson_id = $lesson_id LIMIT 1";
        return $db_handle->numRows($query) > 0 ? true : false;
    }

    // Get exercises belonging to a lesson by id
    public function get_lessons_exercises_id($course_lesson_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.first_name, SPACE(1), a.last_name) AS admin_full_name, elc.edu_lesson_exercise_id,
            elc.lesson_id, elc.question, elc.option_a, elc.option_b, elc.option_c, elc.option_d, elc.right_option
            FROM edu_lesson_exercise AS elc
            INNER JOIN admin AS a ON a.admin_code = elc.admin_code
            WHERE elc.lesson_id = $course_lesson_id ORDER BY elc.created ASC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get Single Lesson By ID
    public function get_single_course_lesson_id($lesson_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.first_name, SPACE(1), a.last_name) AS admin_full_name, el.edu_lesson_id, el.course_id,
            el.lesson_order, el.title, el.content, el.time_required, el.status, el.created
            FROM edu_lesson AS el
            INNER JOIN edu_course AS ec ON ec.edu_course_id = el.course_id
            INNER JOIN admin AS a ON a.admin_code = el.admin_code
            WHERE el.edu_lesson_id = $lesson_id LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get All Exercises Associated with a particular lesson
    public function get_all_exercise_by_lesson_id($lesson_id) {
        global $db_handle;

        $query = "SELECT * FROM edu_lesson_exercise WHERE lesson_id = $lesson_id";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get Exercise by ID
    public function get_single_exercise_by_id($exercise_id) {
        global $db_handle;

        $query = "SELECT * FROM edu_lesson_exercise WHERE edu_lesson_exercise_id = $exercise_id LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Save New Exercise
    public function create_new_exercise($l_question_no, $course_lesson_id, $l_question, $l_option_a, $l_option_b, $l_option_c, $l_option_d, $right_ans, $admin_code) {
        global $db_handle;

        if(!empty($l_question_no)) {
            $query = "UPDATE edu_lesson_exercise SET question = '$l_question', option_a = '$l_option_a', option_b = '$l_option_b', option_c = '$l_option_c', option_d = '$l_option_d', right_option = '$right_ans'
                WHERE edu_lesson_exercise_id = $l_question_no LIMIT 1";
        } else {
            $query = "INSERT INTO edu_lesson_exercise (admin_code, lesson_id, question, option_a, option_b, option_c, option_d, right_option)
            VALUES ('$admin_code', '$course_lesson_id', '$l_question', '$l_option_a', '$l_option_b', '$l_option_c', '$l_option_d', '$right_ans')";

        }

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Confirm if the client have passed through this assessment before
    public function confirm_second_time_assessment($lesson_id, $user_code) {
        global $db_handle;

        $query = "SELECT * FROM user_edu_exercise_log WHERE lesson_id = $lesson_id AND user_code = '$user_code'";
        return $db_handle->numRows($query) > 0 ? true : false;
    }

    public function set_assessment_result($question_answered, $lesson_id, $user_code) {
        global $db_handle;

        $correct = 0;
        $assessment_result = array();

        foreach($question_answered AS $key => $value) {
            $query = "SELECT right_option FROM edu_lesson_exercise WHERE edu_lesson_exercise_id = $key LIMIT 1";
            $result = $db_handle->runQuery($query);

            $exercise_id = $key;

            if($db_handle->numOfRows($result) > 0) {
                $fetched_data = $db_handle->fetchAssoc($result);
                $right_option = $fetched_data[0]['right_option'];

                $query = "INSERT INTO user_edu_exercise_log (user_code, lesson_id, exercise_id, answer)
                    VALUES ('$user_code', $lesson_id, $exercise_id, '$value')";
                $db_handle->runQuery($query);

                $assessment_result[] = array(
                    'insert_id' => $db_handle->insertedId(),
                    'user_answer' => $value,
                    'right_option' => $right_option,
                    'lesson_id' => $lesson_id,
                    'exercise_id' => $key
                );

                if($value == $right_option) { $correct++; }
            }
        }

        return array('correct' => $correct, 'result' => $assessment_result);
    }

    //
    public function get_assessment_result($lesson_id, $user_code) {
        global $db_handle;

        $correct = 0;
        $assessment_result = array();

        $query = "SELECT user_edu_exercise_log_id, lesson_id, exercise_id, answer FROM user_edu_exercise_log WHERE lesson_id = $lesson_id AND user_code = '$user_code'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);


        foreach($fetched_data AS $row) {
            $exercise_id = $row['exercise_id'];
            $query = "SELECT right_option FROM edu_lesson_exercise WHERE edu_lesson_exercise_id = $exercise_id LIMIT 1";
            $result = $db_handle->runQuery($query);

            if($db_handle->numOfRows($result) > 0) {
                $fetched_data = $db_handle->fetchAssoc($result);
                $right_option = $fetched_data[0]['right_option'];

                $assessment_result[] = array (
                    'insert_id' => $row['user_edu_exercise_log_id'],
                    'user_answer' => $row['answer'],
                    'right_option' => $right_option,
                    'lesson_id' => $lesson_id,
                    'exercise_id' => $exercise_id
                );

                if($row['answer'] == $right_option) { $correct++; }
            }
        }

        return array('correct' => $correct, 'result' => $assessment_result);
    }

    // Set support request for lessons
    public function set_lesson_support_request($course_id, $course_lesson_id, $comment, $client_code, $client_first_name, $client_email) {
        global $db_handle;
        global $system_object;

        support_request_code:
        $request_code = rand_string(20);
        if($db_handle->numRows("SELECT support_request_code FROM user_edu_support_request WHERE support_request_code = '$request_code'") > 0) { goto support_request_code; };

        $query = "INSERT INTO user_edu_support_request (support_request_code, user_code, lesson_id, course_id, request)
            VALUES ('$request_code', '$client_code', $course_lesson_id, $course_id, '$comment')";
        $db_handle->runQuery($query);

        // Send acknowledgement mail to clients when they submit a support request
        $subject = "FX Academy Support Request Acknowledgment";
        $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $client_first_name,</p>

            <p>Your support request has been submitted successfully, your Instructor will respond soon.</p>

            <p>You will receive an email when there is a new reply to your request.</p>


            <br /><br />
            <p>Best Regards,<br/>Curry</p>
            <p>Instafxng FX Academy,<br />
                www.instafxng.com/fxacademy</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        $system_object->send_email($subject, $message, $client_email, $client_first_name);

        return $db_handle->affectedRows() > 0 ? $query : false;
    }

    // Get all support request by id
    public function get_all_support_request_by_id($user_code) {
        global $db_handle;

        $query = "SELECT uesr.user_edu_support_request_id AS support_request_id, uesr.support_request_code, uesr.request, uesr.status, uesr.created, ec.title AS course_title,
              el.title AS lesson_title
              FROM user_edu_support_request AS uesr
              INNER JOIN edu_course AS ec ON ec.edu_course_id = uesr.course_id
              INNER JOIN edu_lesson AS el ON el.edu_lesson_id = uesr.lesson_id
              WHERE user_code = '$user_code'
              ORDER BY uesr.created DESC, uesr.status ASC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        foreach($fetched_data as $key => $value) {
            $support_request_id = $fetched_data[$key]['support_request_id'];
            $query = "SELECT * FROM user_edu_support_answer WHERE request_id = $support_request_id ORDER BY created DESC LIMIT 1";
            $result = $db_handle->runQuery($query);
            $selected_answer = $db_handle->fetchAssoc($result);
            $selected_answer = $selected_answer[0];

            if($selected_answer) {
                $fetched_data[$key]['last_reply_date'] = $selected_answer['created'];
                $fetched_data[$key]['last_reply_author'] = $selected_answer['author'];
            } else {
                $fetched_data[$key]['last_reply_date'] = null;
            }
        }

        return $fetched_data;
    }

    // Get one support request by id
    public function get_support_request_by_code($support_request_code) {
        global $db_handle;

        $query = "SELECT uesr.user_edu_support_request_id, CONCAT(u.first_name, SPACE(1), u.last_name) AS full_name,
              uesr.support_request_code, uesr.lesson_id, uesr.course_id, uesr.request, ec.title AS course_title,
              el.title AS lesson_title, uesr.created, u.email, u.phone
              FROM user_edu_support_request AS uesr
              INNER JOIN user AS u ON u.user_code = uesr.user_code
              INNER JOIN edu_course AS ec ON ec.edu_course_id = uesr.course_id
              INNER JOIN edu_lesson AS el ON el.edu_lesson_id = uesr.lesson_id
              WHERE support_request_code = '$support_request_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // Get all answers to a support request using the support request id
    public function get_support_answers_by_id($support_id) {
        global $db_handle;

        $query = "SELECT * FROM user_edu_support_answer WHERE request_id = $support_id ORDER BY created ASC";
        $result = $db_handle->runQuery($query);

        return $db_handle->numOfRows($result) > 0 ? $db_handle->fetchAssoc($result) : false;
    }

    // Mail academy login detials
    public function mail_academy_login_details($client_first_name, $client_email) {
        global $system_object;

        $subject = "$client_first_name, your Journey to Consistent Income Starts Here";
        $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $client_first_name,</p>
            <p>A very warm welcome to you. The first step on the journey to making consistent income from Forex trading is getting adequate knowledge and I’m glad yours has begun.</p>
            <p>Tighten your seat belt as this is going to be an amazing journey and I can only giggle right now as I know that when you are done, you will be armed with enough knowledge to conquer the world, in this case, take all that you deserve from life instead of just settling with what life has to offer. </p>
            <li>
                <ul>Be sure to give this training your 100% attention and thoroughly go through each lesson without forgetting to attempt all the test exercises.</ul>
                <ul>Don’t hesitate to use any of the message box to ask a question when you need more clarity on something or you have a hard time understanding a particular lesson and you will be swiftly responded to.</ul>
                <ul>Rest assured that I am fully committed to holding your hands even as I guide you through all the lessons of this course.</ul>
            </li>
            <p>$client_first_name, brace up for you are about to start one heck of an amazing journey that leads you to getting all that you deserve from life.</p>
            <p>It’s a Big Welcome once again from me to you and I look forward to seeing you on the other side.</p>
            <p><a href="http://bit.ly/2ffEeKl" target="_blank">Here is the link to the online training again.</a></p>
            <p>&nbsp;</p>
            <p>Best Regards,&nbsp;</p>
            <p>Your friend,</p>
            <p>Mercy,</p>
            <p>Clients Relations Manager<br />Instafxng&nbsp;</p>
            <p>&nbsp;</p>

            <div style="margin-top: 50px;"><hr style="border: dotted 1px maroon;" /><img style="float: right; padding: 0 7px; width: 350px; max-height: 180px;" src="https://instafxng.com/images/point-based-rewards.jpg" alt="" />
                <p><strong>Make Up To $4, 200 and N1, 000, 000 Extra While You Take Your Normal Trades</strong></p>
                <p>The Race to <span style="color: #ff0000;">One Million Naira</span> is Almost Over, win extra cash in our ongoing Unified Reward Program.By consistently trading your account, you get a chance get more bonus point and you also get rewarded daily, monthly and in the year.</p>
                <p style="text-align: center;"><!-- [if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://bit.ly/2gvoIEU" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="250%" strokecolor="#f21c2e" fillcolor="#f21c2e">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">See My Rank</center>
                </v:roundrect>
                <![endif]--><a style="background-color: #f21c2e; border: 1px solid #f21c2e; border-radius: 100px; color: #ffffff; display: inline-block; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none; mso-hide: all;" href="http://bit.ly/2gvoIEU">See My Rank</a></p>
            </div>

            <div style="margin-top: 50px;"><hr style="border: dotted 1px maroon;" /><img style="float: right; padding: 0 7px; width: 350px; max-height: 180px;" src="https://instafxng.com/images/forex-freedom-course-banner.jpg" alt="" /> <strong>Fund at a Reduced Price<br /></strong>
                <p>Take advantage of the reduced funding rate now before the price goes up. Depositing into your InstaForex account is fast, quick and easy. It also qualifies you for our promos and contests.</p>
                <p style="text-align: center;"><!-- [if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://bit.ly/2hnVw7d" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="250%" strokecolor="#f21c2e" fillcolor="#f21c2e">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;">Fund Your Account Now</center>
                </v:roundrect>
                <![endif]--><a style="background-color: #f21c2e; border: 1px solid #f21c2e; border-radius: 100px; color: #ffffff; display: inline-block; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 40px; text-align: center; text-decoration: none; width: 200px; -webkit-text-size-adjust: none; mso-hide: all;" href="http://bit.ly/2hnVw7d">Fund Your Account Now</a>&nbsp;</p>
            </div>
            </div>
        </div>
        <hr />

        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

        $system_object->send_email($subject, $message, $client_email, $client_first_name);

        return true;

    }

    // Get Education Deposit Transactions
    public function get_edu_deposit_by_id($trans_id) {
        global $db_handle;

        $query = "SELECT ec.title, ec.edu_course_id, ec.course_cost, ued.amount, ued.stamp_duty, ued.gateway_charge, ued.pay_method, ued.deposit_origin,
                ued.status, ued.created, u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS client_full_name, u.email, u.phone
                FROM user_edu_deposits AS ued
                INNER JOIN user AS u ON u.user_code = ued.user_code
                INNER JOIN edu_course AS ec ON ec.edu_course_id = ued.course_id
                WHERE ued.trans_id = '$trans_id' LIMIT 1";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $trans_detail = $fetched_data[0];

        return $trans_detail ? $trans_detail : false;
    }

    public function get_edu_deposit_remark($trans_id) {
        global $db_handle;

        $query = "SELECT CONCAT(a.last_name, SPACE(1), a.first_name) AS admin_full_name, uedc.comment, uedc.created
                FROM user_edu_deposits_comment AS uedc
                INNER JOIN admin AS a ON uedc.admin_code = a.admin_code
                WHERE uedc.trans_id = '$trans_id' ORDER BY uedc.created DESC";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;

    }

    public function log_edu_deposit_comment($admin_code, $transaction_no, $admin_comment) {
        global $db_handle;

        $query = "INSERT INTO user_edu_deposits_comment (admin_code, trans_id, comment) VALUES ('$admin_code', '$transaction_no', '$admin_comment')";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function modify_edu_deposit_order($transaction_no, $course_no, $user_no, $deposit_status, $admin_comment, $admin_code) {
        global $db_handle;

        $query = "UPDATE user_edu_deposits SET status = '$deposit_status' WHERE trans_id = '$transaction_no' LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            $this->log_edu_deposit_comment($admin_code, $transaction_no, $admin_comment);

            if($deposit_status == '3') {
                $query = "INSERT INTO user_edu_fee_payment (reference, admin_code, user_code, course_id) VALUES ('$transaction_no', '$admin_code', '$user_no', $course_no)";
                $db_handle->runQuery($query);
                $this->set_access_confirmation_mail($user_no);
            }

            return true;
        } else {
            return false;
        }
    }

    public function submit_payment_notification($transaction_no, $pay_date, $naira_amount, $admin_comment = false, $admin_code = false) {
        global $db_handle;

        $query = "UPDATE user_edu_deposits SET status = '2', pay_date = '$pay_date', amount_paid = $naira_amount WHERE trans_id = '$transaction_no' LIMIT 1";
        $db_handle->runQuery($query);

        if($db_handle->affectedRows() > 0) {
            if($admin_comment) {
                $this->log_edu_deposit_comment($admin_code, $transaction_no, $admin_comment);
            }

            return true;
        } else {
            return false;
        }

    }

    // Get all lesson logged by the client - using answered exercises
    public function get_client_lesson_history($user_code) {
        global $db_handle;

        $query = "SELECT ueel.created AS lesson_log_date, el.title AS lesson_title, ec.title AS course_title
              FROM user_edu_exercise_log AS ueel
              INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE ueel.user_code = '$user_code' GROUP BY (ueel.lesson_id) ORDER BY ec.course_order ASC, ueel.created ASC";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // get client detail with unique code
    public function get_client_detail_by_code($unique_code) {
        global $db_handle;

        $query = "SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name, * FROM user WHERE user_code = '$unique_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // get admin detail with unique code
    public function get_admin_detail_by_code($unique_code) {
        global $db_handle;

        $query = "SELECT first_name AS admin_first_name, CONCAT(first_name, SPACE(1), last_name) AS full_name FROM admin WHERE admin_code = '$unique_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    public function close_support_ticket($ticket_code) {
        global $db_handle;

        $query = "UPDATE user_edu_support_request SET status = '2' WHERE support_request_code = '$ticket_code' LIMIT 1";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Set support reply
    public function set_lesson_support_reply($category, $support_id, $comment_reply, $unique_code, $request_status, $client_email, $client_first_name) {
        global $db_handle;
        global $system_object;

        $query = "SELECT support_request_code FROM user_edu_support_request WHERE user_edu_support_request_id = $support_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $support_request_code = $fetched_data[0]['support_request_code'];

        $return_url = "https://instafxng.com/fxacademy/index.php?se=" . $client_email . "&sid=" . encrypt($support_request_code) . "&c=" . encrypt("1");

        $query = "INSERT INTO user_edu_support_answer (author, category, request_id, response) VALUES ('$unique_code', '$category', $support_id, '$comment_reply')";
        $db_handle->runQuery($query);

        if ($db_handle->affectedRows() > 0) {
            $query = "UPDATE user_edu_support_request SET status = $request_status WHERE user_edu_support_request_id = $support_id LIMIT 1";
            $db_handle->runQuery($query);

            // Send acknowledgement mail to clients when they submit a support request
            $comment_reply_mail = str_replace('\r', "\r", str_replace('\n', "\n", $comment_reply));
            $subject = "RE: FX Academy Support Request";
            $message =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $client_first_name,</p>

            <p>Your support request on the FX Academy has been responded to by an Admin.</p>

            <p>Kindly <a href="$return_url">click here</a> to login to the
             FX Academy portal to see the reply and the full support thread.</p>

            <br /><br />
            <p>Best Regards,<br/>Curry</p>
            <p>Instafxng FX Academy,<br />
                www.instafxng.com/fxacademy</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;

            $system_object->send_email($subject, $message, $client_email, $client_first_name);

            return true;
        } else {
            return false;
        }
    }

    //Set lesson rating
    public function set_lesson_rating($user_code, $lesson_id, $course_id, $rating, $comments)
    {
        global $db_handle;
        $query = "INSERT INTO edu_lesson_rating (user_code, lesson_id, course_id, rating, comments) VALUES('$user_code','$lesson_id','$course_id','$rating','$comments')";
        $db_handle->runQuery($query);
    }

    // Get all initiated transactions by user_code
    public function get_initiated_trans_by_code($user_code) {
        global $db_handle;

        $query = "SELECT status, amount, stamp_duty, gateway_charge, trans_id, pay_method, deposit_origin,
            created
            FROM user_edu_deposits
            WHERE status = '1' AND user_code = '$user_code'
            ORDER BY created DESC ";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    // Get the transaction ID of the user latest transaction by user code
    public function get_last_trans_id($user_code) {
        global $db_handle;

        $query = "SELECT trans_id
            FROM user_edu_deposits
            WHERE status = '1' AND user_code = '$user_code'
            ORDER BY created DESC LIMIT 1 ";

        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;
    }

    //Send access confirmation mail to client
    public function set_access_confirmation_mail($user_code)
    {
        global $system_object;
        global $db_handle;
        $query = "SELECT email, first_name FROM user WHERE user_code = '$user_code' ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $client_email = $result[0]['email'];
        $client_first_name = $result[0]['first_name'];
        $subject = "Access to the Forex Profit Optimizer Course";
        $message =
            <<<MAIL
                    <div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Hello $client_first_name,</p>

            <p>Your payment for the Forex Profit Optimizer course has been completed and you have been granted access to continue the course.</p>
            <p>Kindly <a href="https://instafxng.com/fxacademy/">click here</a> to start the Forex profit optimizer course.</p>
             <p>Feel free to send us a message if you need any clarification or assistance with any of the lesson.</p>
             <p>We're always on hand to guide you through.</p>
             <p>Have a wonderful time in class!</p>

            <br /><br />
            <p>Best Regards,<br/>Curry</p>
            <p>Instafxng FX Academy,<br />
                www.instafxng.com/fxacademy</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We're Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos.</p>
                <p><strong>Office Number:</strong> 08028281192</p>
                <br />
            </div>
            <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                <p>This email was sent to you by Instant Web-Net Technologies Limited, the
                    official Nigerian Representative of Instaforex, operator and administrator
                    of the website www.instafxng.com</p>
                <p>To ensure you continue to receive special offers and updates from us,
                    please add support@instafxng.com to your address book.</p>
            </div>
        </div>
    </div>
</div>
MAIL;
        $system_object->send_email($subject, $message, $client_email, $client_first_name);
    }
}

$education_object = new Education();