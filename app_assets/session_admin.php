<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out
// Keep in mind when working with sessions that it is generally
// inadvisable to stor DB-related objects in sessions
class SessionAdmin {    
    private $logged_in = false;
    public $admin_unique_code;
    
    function __construct() {
        ob_start();
        $sess_name = session_name();
        if ($sess_name != "instafxng_admin") {
            session_name("instafxng_admin");
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
    
    public function login($user) {
        global $admin_object;
        // database should find user based on admin_code/password
        if($user) {
            $this->admin_unique_code = $_SESSION['admin_unique_code'] = $user['admin_code'];
            $_SESSION['admin_status'] = $user['status'];
            $_SESSION['admin_first_name'] = $user['first_name'];
            $_SESSION['admin_last_name'] = $user['last_name'];
            $_SESSION['admin_last_login'] = $user['last_login'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['user_time'] = time();
            $_SESSION['user_privilege'] = $admin_object->get_privileges($_SESSION['admin_unique_code']);
            $this->logged_in = true;
        }
    }
    
    public function logout() {
        unset($_SESSION['admin_admin_unique_code']);
        unset($_SESSION['admin_status']);
        unset($_SESSION['admin_first_name']);
        unset($_SESSION['admin_last_name']);
        unset($_SESSION['admin_last_login']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['user_time']);
        unset($_SESSION['user_privilege']);
        unset($this->admin_unique_code);
        session_unset();
        session_destroy();
        $this->logged_in = false;
    }
    
    private function auto_logout() {
        // Set time allowed to be inactive in seconds. 60min x 60 = 3600
        $inactive = 3600;
        $t = time();
        if (isset($_SESSION['user_time'])) {
            $to = $_SESSION['user_time'];
            $diff = $t - $to;
            if ($diff > $inactive) {          
                return true;
            } else {
                $_SESSION['user_time'] = time();
                return false;
            }
            
        } else {
            return false;
        }
    }
    
    private function check_login() {
        if ($this->auto_logout()) {
            $this->logout();
            redirect_to("https://instafxng.com/admin/login.php?logout=2");
        } elseif(isset($_SESSION['admin_unique_code'])) {
            $this->admin_unique_code = $_SESSION['admin_unique_code'];
            $this->logged_in = true;
        } else {
            unset($this->admin_unique_code);
            $this->logged_in = false;
        }
    }
}
$session_admin = new SessionAdmin();