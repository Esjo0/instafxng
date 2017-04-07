<?php
/**
 * Created by PhpStorm.
 * User: WeaverBird
 * Date: 9/13/2016
 * Time: 12:43 PM
 */

namespace public_html\app_assets;


class Questions
{

    public function add_question($course_id, $lesson_id, $question, $options, $right){
        global $db_handle;

        $query = "INSERT INTO train_questions ( course_id, lesson_id, question )
        VALUES ($course_id, $lesson_id, '$question') ";

        if($db_handle->runQuery($query)){

            $inserted_id = $db_handle->insertedId();
            if(is_array($options['new'])){

                foreach($options['new'] as $key => $value){

                    if(! empty($value) ) {
                        $option_id = $this->add_option($inserted_id, $value);
                        if ($key == $right) { // right one
                            if (!empty($option_id)) {

                                if ($this->update_right_option($inserted_id, $option_id)) {
                                    //success

                                } else { /* Something went wrong */ }

                            }
                        }
                    }

                } // #end foreach

                return true;

            }

        }
        return false;

    }

    public function add_option($question_id, $option){
        global $db_handle;

        $query = "INSERT INTO train_question_options (question_id, `option`)
        VALUES ($question_id, '$option')";
        $db_handle->runQuery($query);
        return $db_handle->insertedId();


    }

    public function update_right_option($question_id, $option_id){
        global $db_handle;
        $query = "UPDATE train_questions SET right_option = {$option_id} WHERE question_id = {$question_id} ";
        return $db_handle->runQuery($query);

    }

    private function update_question($question_id, $question){
        global $db_handle;
        $query = "UPDATE train_questions SET question = '$question' WHERE question_id = {$question_id}";
        return $db_handle->runQuery($query);
    }

    private function update_option($option_id, $question_id, $option){
        global $db_handle;
        $query = "UPDATE train_question_options SET `option` = '$option'
        WHERE question_id = $question_id AND option_id = $option_id";
        return $db_handle->runQuery($query);
    }


    /**
     * Used for the Editing of question and options forms
     * @param $course_id
     * @param $lesson_id
     * @param $question_id
     * @param $question
     * @param $options
     * @param $right
     * @return bool
     */
    public function update_question_and_options($course_id, $lesson_id, $question_id, $question, $options, $right){

        if(empty($question_id) || empty($question)) return false;

        if($this->update_question($question_id, $question)){

            $right_option_check = 0;

            // Note : The options is a two dimensional array that contains an associative array
            if(is_array($options)){
                foreach($options as $key => $value){

                    if($key == 'old'){// perform an update to the database

                        if(is_array($value)){
                            foreach($value as $option_id => $option_text){


                                if( !empty($option_text) ){ // perform the update

                                    if($this->update_option($option_id, $question_id, $option_text)){

                                        if($right == $right_option_check){ // update right option
                                            $this->update_right_option($question_id, $option_id);
                                        }

                                    }

                                }else { /* Delete the option from the table */
                                    $this->delete_option($question_id, $option_id);

                                }

                                $right_option_check++;

                            }
                        }

                    }else if($key == 'new'){ // insert into the database

                        if(is_array($value)){
                            foreach($value as $option_text){
                                if(!empty($option_text)){

                                    $option_inserted_id = $this->add_option($question_id, $option_text);
                                    if($option_inserted_id) {
                                        if ($right == $right_option_check) { // update right option
                                            $this->update_right_option($question_id, $option_inserted_id);
                                        }
                                    }

                                }

                                $right_option_check ++;
                            }

                        }

                    }


                }// #foreach options

                return true;

            } // #end option is array


        }

        return false;

    }

    public function get_questions($course_id){
        global $db_handle;
        $query = "SELECT * FROM train_questions WHERE course_id = {$course_id}";
        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

    public function get_question_and_options($course_id, $lesson_id){
        global $db_handle;
        $query = "SELECT *
        FROM train_question_options AS op
        LEFT JOIN train_questions AS q ON q.question_id = op.question_id
        WHERE q.course_id = {$course_id} AND q.lesson_id = {$lesson_id} ";

        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);

    }


    public function get_question($course_id, $lesson_id, $question_id){
        global $db_handle;
        $query = "SELECT * FROM train_questions WHERE course_id = {$course_id} AND question_id = {$question_id} AND lesson_id = {$lesson_id}";
        $result = $db_handle->runQuery($query);
        return $result->fetch_assoc();
    }

    public function get_options($question_id){
        global $db_handle;
        $query = "SELECT *
        FROM train_question_options AS op
        WHERE op.question_id = {$question_id} ";

        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);

    }

    public function delete_option($question_id, $option_id){
        global $db_handle;
        $query = "DELETE FROM train_question_options WHERE question_id = $question_id AND option_id = $option_id";
        return $db_handle->runQuery($query);
    }

    public function delete_question($question_id, $course_id){
        global $db_handle;
        $query = "DELETE FROM train_question_options WHERE question_id = $question_id";

        if($db_handle->runQuery($query)){// delete the main
            $query = "DELETE FROM train_questions WHERE question_id = $question_id AND course_id = $course_id";
            if($db_handle->runQuery($query)){
                return true;
            }

        }
        return false;

    }

    public function get_answers($course_id, $lesson_id){
        global $db_handle;
        $query = "SELECT q.question_id, right_option, op.`option`
        FROM train_questions AS q
        LEFT JOIN train_question_options AS op ON q.right_option = op.option_id
        WHERE course_id = $course_id AND lesson_id = $lesson_id";

        $result = $db_handle->runQuery($query);
        return $db_handle->fetchAssoc($result);
    }

}

$questions_object = new Questions();