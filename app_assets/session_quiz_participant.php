<?php
class Session_Quiz_Participant
{
    private $logged_in = false;
    public $participant_acc_no;
    
    function __construct() {
        ob_start();
        $sess_name = session_name();
        if ($sess_name != "instafxng_quiz") {
            session_name("instafxng_quiz");
        }
        session_start();
        $this->check_login();
        if($this->logged_in) {
            // actions to take right away if user is logged in
        } else {
            // actions to take right away if user is not logged in
        }
    }
    
    public function is_logged_in() {
        return $this->logged_in;
    }
    
    public function login($participant_email) {
        // database should find participant based on acc_no

        if($participant_email)
        {
            $this->participant_email = $_SESSION['participant_email'] = $participant_email;
            $this->logged_in = true;
        }
    }
    
    public function logout() {
        unset($_SESSION['participant_email']);
        unset($this->participant_email);
        session_unset();
        session_destroy();
        $this->logged_in = false;
    }

    
    private function check_login() {
        if(isset($_SESSION['participant_email'])) {
            $this->participant_email = $_SESSION['participant_email'];
            $this->logged_in = true;
        } else {
            unset($this->participant_email);
            $this->logged_in = false;
        }
    }

    public function authenticate_particpant($email)
    {
        global $db_handle;
        $email = $db_handle->sanitizePost($email);
        $query = "SELECT * FROM quiz_participant WHERE participant_email = '$email' LIMIT 1";
        $result = $db_handle->runQuery($query);

        if($db_handle->numOfRows($result) == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
$session_participant = new Session_Quiz_Participant();