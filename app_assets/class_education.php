<?php

class Education {

    // Log course deposit attempt
    public function log_course_deposit($user_code, $trans_id, $course_id, $course_cost, $stamp_duty, $card_processing, $pay_type, $origin_of_deposit) {
        global $db_handle;

        $query = "INSERT INTO user_edu_deposits (user_code, trans_id, course_id, amount, stamp_duty,
            gateway_charge, pay_method, deposit_origin) VALUES ('$user_code', '$trans_id', $course_id, $course_cost, $stamp_duty, $card_processing, '$pay_type', '$origin_of_deposit')";

        $db_handle->runQuery($query);
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

    // Get all the courses attempted by this client
    public function get_courses_attempted($user_code) {
        global $db_handle;

        $query = "SELECT ec.edu_course_id, ec.title
              FROM user_edu_exercise_log AS ueel
              INNER JOIN edu_lesson AS el ON ueel.lesson_id = el.edu_lesson_id
              INNER JOIN edu_course AS ec ON el.course_id = ec.edu_course_id
              WHERE ueel.user_code = '$user_code' GROUP BY ec.edu_course_id ORDER BY ueel.created DESC";
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
    public function get_active_lessons_by_id($course_id) {
        global $db_handle;

        $query = "SELECT el.edu_lesson_id, el.course_id,
            el.lesson_order, el.title, el.content, el.time_required, el.status, el.created
            FROM edu_lesson AS el
            INNER JOIN edu_course AS ec ON ec.edu_course_id = el.course_id
            INNER JOIN admin AS a ON a.admin_code = el.admin_code
            WHERE el.course_id = $course_id AND el.status = '2' ORDER BY el.lesson_order ASC";

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
    public function set_lesson_support_request($course_id, $course_lesson_id, $comment, $client_code) {
        global $db_handle;

        support_request_code:
        $request_code = rand_string(20);
        if($db_handle->numRows("SELECT support_request_code FROM user_edu_support_request WHERE support_request_code = '$request_code'") > 0) { goto support_request_code; };


        $query = "INSERT INTO user_edu_support_request (support_request_code, user_code, lesson_id, course_id, request)
            VALUES ('$request_code', '$client_code', $course_lesson_id, $course_id, '$comment')";
        $db_handle->runQuery($query);

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

    // Set support reply
    public function set_lesson_support_reply($category, $support_id, $comment_reply, $unique_code, $request_status) {
        global $db_handle;

        $query = "INSERT INTO user_edu_support_answer (author, category, request_id, response) VALUES ('$unique_code', '$category', $support_id, '$comment_reply')";
        $db_handle->runQuery($query);

        if ($db_handle->affectedRows() > 0) {
            $query = "UPDATE user_edu_support_request SET status = $request_status WHERE user_edu_support_request_id = $support_id LIMIT 1";
            $db_handle->runQuery($query);
            return true;
        } else {
            return false;
        }
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
            }

            return true;
        } else {
            return false;
        }
    }

    // get client detail with unique code
    public function get_client_detail_by_code($unique_code) {
        global $db_handle;

        $query = "SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name FROM user WHERE user_code = '$unique_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    // get admin detail with unique code
    public function get_admin_detail_by_code($unique_code) {
        global $db_handle;

        $query = "SELECT CONCAT(first_name, SPACE(1), last_name) AS full_name FROM admin WHERE admin_code = '$unique_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

}

$education_object = new Education();