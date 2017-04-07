<?php
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out
// Keep in mind when working with sessions that it is generally
// inadvisable to stor DB-related objects in sessions
class SessionPartner {    
    private $logged_in = false;
    public $partner_code;
    
    function __construct() {
        ob_start();
        $sess_name = session_name();
        if ($sess_name != "instafxng_partner") {
            session_name("instafxng_partner");
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
    
    public function login($partner) {
        // database should find user based on username/password
        if($partner) {
            $this->partner_code = $_SESSION['partner_code'] = $partner['partner_code'];
            $_SESSION['partner_user_code'] = $partner['user_code'];
            $_SESSION['partner_status'] = $partner['status'];
            $_SESSION['partner_email'] = $partner['email'];
            $_SESSION['partner_first_name'] = $partner['first_name'];
            $_SESSION['partner_middle_name'] = $partner['middle_name'];
            $_SESSION['partner_last_name'] = $partner['last_name'];
            $_SESSION['partner_phone'] = $partner['phone'];
            $_SESSION['partner_time'] = time();
            $this->logged_in = true;
        }
    }
    
    public function logout() {
        unset($_SESSION['partner_code']);
        unset($_SESSION['partner_user_code']);
        unset($_SESSION['partner_status']);
        unset($_SESSION['partner_email']);
        unset($_SESSION['partner_first_name']);
        unset($_SESSION['partner_middle_name']);
        unset($_SESSION['partner_last_name']);
        unset($_SESSION['partner_phone']);
        unset($_SESSION['partner_time']);
        unset($this->partner_code);
        session_unset();
        session_destroy();
        $this->logged_in = false;
    }
    
    private function auto_logout() {
        // Set time allowed to be inactive in seconds. 30min x 60 = 900
        $inactive = 1800;
        $t = time();
        if (isset($_SESSION['partner_time'])) {
            $to = $_SESSION['partner_time'];
            $diff = $t - $to;
            if ($diff > $inactive) {          
                return true;
            } else {
                $_SESSION['partner_time'] = time();
                return false;
            }
            
        } else {
            return false;
        }
    }
    
    private function check_login() {
        if ($this->auto_logout()) {
            $this->logout();
            redirect_to("../login.php?logout=2");
        } elseif(isset($_SESSION['partner_code'])) {
            $this->partner_code = $_SESSION['partner_code'];
            $this->logged_in = true;
        } else {
            unset($this->partner_code);
            $this->logged_in = false;
        }
    }
}
$session_partner = new SessionPartner();