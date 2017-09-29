<?php
$time_per_question = 5; //5  seconds
$no_of_allowed_questions = 15; //15 randomly picked questions per client
$mark_per_question = 6.7; //5 marks per question
$max_time = 60; //Number of seconds in a minute
class quiz
{
    public function grade_question($question_id, $option, $email)
    {
        global $db_handle;
        $query = "SELECT answer FROM quiz_questions WHERE question_id = '$question_id' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $result = $result[0];
        $answer = $result['answer'];
        extract($answer);
        if($answer == $option)
        {
            $this->update_total_no_of_correct_answers($email);
        }
    }

    public function add_new_participant($email)
    {
        global $db_handle;
        $query = "INSERT IGNORE INTO quiz_participant (participant_email) VALUES ('$email')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_invalid_participant($email)
    {
        global $db_handle;
        global $no_of_allowed_questions;
        $query = "SELECT * FROM quiz_participant WHERE participant_email = '$email' LIMIT 1 ";
        $result = $db_handle->runQuery($query);
        $result = $db_handle->fetchAssoc($result);
        $result = $result[0];
        //var_dump($result);
        if($result['total_questions'] > $no_of_allowed_questions || $result['total_questions'] == $no_of_allowed_questions)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_question($email)
    {
        global $db_handle;
        $query = "SELECT question_id, question, options FROM quiz_questions ORDER BY RAND() LIMIT 1";
        //$query = "SELECT question_id, question, options FROM quiz_questions LIMIT 1";
        $result = $db_handle->runQuery($query);
        $question = $db_handle->fetchAssoc($result);
        $question = $question[0];
        $answered_questions = $this->check_questions_taken($email);
        //var_dump($answered_questions);
        $answered_questions = explode("*", $answered_questions['questions_answered']);
        //$answered_questions = $this->comma_separated_to_array($answered_questions);
        //var_dump($answered_questions);

        if (in_array($question['question_id'], $answered_questions, true))
        {
            $this->get_question($email);
        }
        else
        {
            $this->update_questions_taken($question['question_id'], $email);
            $this->update_total_no_of_questions_taken($email);
        }

        return $question;
    }

    function comma_separated_to_array($string, $separator = '*')
    {
        $vals = explode($separator, $string);
        foreach($vals as $key => $val) {
            $vals[$key] = trim($val);
        }
        return array_diff($vals, array(""));
    }

    public function check_questions_taken($email)
    {
        global $db_handle;
        $query = "SELECT questions_answered FROM quiz_participant WHERE participant_email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $questions_answered = $db_handle->fetchAssoc($result);
        return $questions_answered[0];
    }

    public function update_questions_taken($question_id, $email)
    {
        global $db_handle;
        $query = "UPDATE quiz_participant SET questions_answered = CONCAT(questions_answered, '*$question_id') WHERE participant_email = '$email'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function update_time($time, $email)
    {
        global $db_handle;
        $query = "SELECT total_time FROM quiz_participant WHERE participant_email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $total_time = $db_handle->fetchAssoc($result);
        $total_time = $total_time[0];
        extract($total_time);
        $query = "UPDATE quiz_participant SET total_time = CONCAT(total_time + '$time') WHERE participant_email = '$email' ";
        $db_handle->runQuery($query);
    }

    public function update_total_no_of_questions_taken($email)
    {
        global $db_handle;
        $total_no_of_questions_taken = $this->get_total_no_of_questions_taken($email);
            $query = "UPDATE quiz_participant SET total_questions = CONCAT(total_questions + 1) WHERE participant_email = '$email' LIMIT 1";
            $db_handle->runQuery($query);
            return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function update_total_no_of_correct_answers($email)
    {
        global $db_handle;
        $query = "UPDATE quiz_participant SET questions_answered_correctly = CONCAT(questions_answered_correctly + 1) WHERE participant_email = '$email' LIMIT 1";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_total_no_of_questions_taken( $email)
    {
        global $db_handle;
        $query = "SELECT total_questions FROM quiz_participant WHERE participant_email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $total_no_of_questions_taken = $db_handle->fetchAssoc($result);
        $total_no_of_questions_taken = $total_no_of_questions_taken[0];
        $total_no_of_questions_taken = $total_no_of_questions_taken['total_questions'];
        return $total_no_of_questions_taken;
    }

    public function valid_ifx_email($email)
    {
        global $db_handle;
        $query = "SELECT email FROM user WHERE email = '$email' LIMIT 1";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

}

$obj_quiz = new quiz();