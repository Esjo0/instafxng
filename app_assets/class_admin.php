<?php

class AdminUser {

    //get transaction issues details
    public function get_transaction_issue( $transaction_id ) {
        global $db_handle;
        $query = "SELECT * FROM operations_log WHERE transaction_id = '$transaction_id'";
        $result = $db_handle->runQuery($query);
        $fetch_data = $db_handle->fetchAssoc($result);

        return $fetch_data ? $fetch_data : false;
    }


    //get operations log comment details
    public function get_comment_details( $transaction_id ) {
        global $db_handle;
        $query = "SELECT * FROM operations_log_comments WHERE transaction_id = '$transaction_id' ORDER BY created DESC";
        $result = $db_handle->runQuery($query);
        $fetch_data = $db_handle->fetchAssoc($result);

        return $fetch_data ? $fetch_data : false;
    }

    //add new operation issue comment
    public function add_operations_comment($transaction_id,$comment,$admin_code) {
        global $db_handle;

        $query = "INSERT INTO operations_log_comments(transaction_id, comment, admin_code) VALUES ('$transaction_id','$comment','$admin_code')";
        return $db_handle->runQuery($query) ? true : false;
    }


    //add new operation issue to the record
    public function add_issues($transaction_id,$details,$admin_code) {
        global $db_handle;

        $query = "INSERT INTO operations_log (transaction_id, details, admin, status) VALUES ('$transaction_id','$details','$admin_code','0')";
        return $db_handle->runQuery($query) ? true : false;
    }

    //get transaction details by transaction id in admin operations log page
    public function get_transaction_details($transaction_id)
    {
        global $db_handle;
        $check = substr($transaction_id, 0, 3);
        if($check  == WIT){
            $query = "SELECT uw.trans_id, uw.dollar_withdraw, uw.status, ui.ifx_acct_no, uw.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_withdrawal AS uw
              INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE uw.trans_id = '$transaction_id'";
        }else{
            $query = "SELECT ud.trans_id, ud.dollar_ordered, ud.status, ui.ifx_acct_no, ud.created, CONCAT(u.last_name, SPACE(1), u.first_name) AS full_name
              FROM user_deposit AS ud
              INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id
              INNER JOIN user AS u ON ui.user_code = u.user_code
              WHERE ud.trans_id = '$transaction_id'";
        }
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;

    }

    public function authenticate($username = "", $password = "") {
        global $db_handle;
        $username = $db_handle->sanitizePost($username);

        $query = "SELECT pass_salt FROM admin WHERE email = '{$username}' LIMIT 1";
        
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) == 1) {
            $user = $db_handle->fetchAssoc($result);
            $pass_salt = $user[0]['pass_salt'];
            $hashed_password = hash("SHA512", "$pass_salt.$password");
            
            $query = "SELECT admin_code, email, first_name, last_name, last_login, "
                    . "status FROM admin WHERE email = '{$username}' AND password "
                    . "= '{$hashed_password}' LIMIT 1";
            $result = $db_handle->runQuery($query);
            
            if($db_handle->numOfRows($result) == 1) {
                $found_user = $db_handle->fetchAssoc($result);
                return $found_user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function get_admin_detail($username = "", $password = "") {
        return $this->authenticate($username, $password);
    }
    
    public function update_admin_password($username = "", $password = "") {
        global $db_handle;
        
        $query = "SELECT pass_salt FROM admin WHERE email = '{$username}' LIMIT 1";
        $result = $db_handle->runQuery($query);
        $user = $db_handle->fetchAssoc($result);
        $pass_salt = $user[0]['pass_salt'];
        $hashed_password = hash("SHA512", "$pass_salt.$password");
        
        $query = "UPDATE admin SET password = '{$hashed_password}' WHERE email = '{$username}' LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_all_admin_member() {
        global $db_handle;

        $query = "SELECT CONCAT(last_name, ' ', first_name) AS full_name, admin_code FROM admin";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data;
    }
    
    public function get_admin_name_by_code($admin_code) {
        global $db_handle;
        
        $query = "SELECT CONCAT(last_name, ' ', first_name) AS full_name FROM admin WHERE admin_code = '$admin_code'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        
        $full_name = $fetched_data[0]['full_name'];
        return $full_name;
    }
    
    public function get_admin_detail_by_code($admin_code) {
        global $db_handle;
        
        $query = "SELECT last_name, first_name, email, status FROM admin WHERE admin_code = '$admin_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            return $fetched_data[0];
        } else {
            return false;
        }
        
    }
    
    public function get_privileges($admin_code) {
        global $db_handle;
        
        $query = "SELECT allowed_pages FROM admin_privilege WHERE admin_code = '$admin_code' LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        if($db_handle->numOfRows($result) > 0) {
            $fetched_data = $db_handle->fetchAssoc($result);
            return $fetched_data[0];
        } else {
            return false;
        }
    }
    
    // Confirm that the email address is not existing
    public function admin_is_duplicate($email) {
        global $db_handle;
        
        $query = "SELECT * FROM admin WHERE email = '$email'";
        $result = $db_handle->numRows($query);
        
        if($result) {
            return true;
        } else {
            return false;
        }
    }
    
    // Check whether admin has an active status
    public function admin_is_active($email) {
        global $db_handle;
        
        $query = "SELECT status FROM admin WHERE email = '$email'";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        
        if($fetched_data[0]['status'] == '1') {
            return true;
        } else {
            return false;
        }
    }
    
    // Add a new admin profile
    public function add_new_admin($first_name, $last_name, $email) {
        global $db_handle;
        global $system_object;
        
        //check whether admin_code generated by rand_string is already existing
        admin_code:
        $admin_code = rand_string(5);
        if($db_handle->numRows("SELECT admin_code FROM admin WHERE admin_code = '$admin_code'") > 0) { goto admin_code; };
    
        $pass = rand_string(7);
        $pass_salt = hash("SHA256", "$admin_code");
        $hashed_password = hash("SHA512", "$pass_salt.$pass");
        
        $query = "INSERT INTO admin (admin_code, email, pass_salt, password, first_name, last_name) VALUES ('$admin_code', '$email', '$pass_salt', '$hashed_password', '$first_name', '$last_name')";
        $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            
            $query = "INSERT INTO admin_privilege (admin_code, allowed_pages) VALUES ('$admin_code', '')";
            $db_handle->runQuery($query);
            
            //New admin succefully inserted, send default password to the admin
            $subject = "Instafxng Admin Login";
            $body =
<<<MAIL
<div style="background-color: #F3F1F2">
    <div style="max-width: 80%; margin: 0 auto; padding: 10px; font-size: 14px; font-family: Verdana;">
        <img src="https://instafxng.com/images/ifxlogo.png" />
        <hr />
        <div style="background-color: #FFFFFF; padding: 15px; margin: 5px 0 5px 0;">
            <p>Dear $first_name,</p>

            <p>A new Instafxng Admin profile has been created for you.</p>

            <p>Your system generated password is $pass</p>
            <p>Your username is $email</p>

            <p>Login with the URL below, you can update your password in the
            profile settings.</p>

            <p>https://instafxng.com/admin</p>

            <p>Do not share your Admin credentials with anyone.</p>

            <br /><br />
            <p>Best Regards,</p>
            <p>Instafxng Support,<br />
                www.instafxng.com</p>
            <br /><br />
        </div>
        <hr />
        <div style="background-color: #EBDEE9;">
            <div style="font-size: 11px !important; padding: 15px;">
                <p style="text-align: center"><span style="font-size: 12px"><strong>We"re Social</strong></span><br /><br />
                    <a href="https://facebook.com/InstaForexNigeria"><img src="https://instafxng.com/images/Facebook.png"></a>
                    <a href="https://twitter.com/instafxng"><img src="https://instafxng.com/images/Twitter.png"></a>
                    <a href="https://www.instagram.com/instafxng/"><img src="https://instafxng.com/images/instagram.png"></a>
                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw"><img src="https://instafxng.com/images/Youtube.png"></a>
                    <a href="https://linkedin.com/company/instaforex-ng"><img src="https://instafxng.com/images/LinkedIn.png"></a>
                </p>
                <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                <p><strong>Lekki Office Address:</strong> Road 5, Suite K137, Ikota Shopping Complex, Lekki/Ajah Express Road, Lagos State</p>
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
            
            $system_object->send_email($subject, $body, $email, $first_name);
            
            return true;
        } else {
            return false;
        }
    }

    // Add New Dinner Attendee
    public function add_new_dinner_attendee($full_name, $email, $phone_number) {
        global $db_handle;

        $query = "INSERT INTO dinner_2016 (full_name, email, phone) VALUES ('$full_name', '$email', '$phone_number')";
        $db_handle->runQuery($query);

        return $db_handle->affectedRows() > 0 ? true : false;

    }

    // Add New Dinner Guest 2017
    public function add_new_dinner_guest_2017($confirmation, $full_name, $email, $phone_number, $ticket_type, $state_of_residence, $comments)
    {
        global $db_handle;

        reservation_code:
        $reservation_code = rand_string(5);
        if($db_handle->numRows("SELECT reservation_code FROM dinner_2017 WHERE reservation_code = '$reservation_code'") > 0) { goto reservation_code; };

        $query = "INSERT INTO dinner_2017 (confirmation, reservation_code, full_name, email, phone, ticket_type, state_of_residence, comments) VALUES ('$confirmation', '$reservation_code', '$full_name', '$email', '$phone_number', '$ticket_type', '$state_of_residence', '$comments')";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Update Dinner Guest 2017
    public function update_dinner_guest_2017($reservation_code, $ticket_type, $confirmation)
    {
        global $db_handle;
        $query = "UPDATE dinner_2017 SET ticket_type = '$ticket_type', confirmation = '$confirmation' WHERE reservation_code = '$reservation_code'";
        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }
    // Confirm that the email address is not existing
    public function dinner_guest_2017_is_duplicate($email)
    {
        global $db_handle;
        $query = "SELECT * FROM dinner_2017 WHERE email = '$email'";
        $result = $db_handle->numRows($query);
        return $result ? true : false;
    }
    
    // Update admin profile - modify the status
    public function modify_admin_profile($admin_code, $first_name, $last_name, $admin_status) {
        global $db_handle;
        
        $query = "UPDATE admin SET first_name = '$first_name', last_name = '$last_name', status = '{$admin_status}' WHERE admin_code = '{$admin_code}' LIMIT 1";
        $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function modify_admin_privilege($admin_code, $allowed_pages) {
        global $db_handle;
        
        $query = "UPDATE admin_privilege SET allowed_pages = '{$allowed_pages}' WHERE admin_code = '{$admin_code}' LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Add a new bulletin
    public function add_new_bulletin($bulletin_no, $title, $content, $bulletin_status = '2', $all_allowed_admin, $admin_code) {
        global $db_handle;
        
        if(!empty($bulletin_no)) {
            $query = "UPDATE admin_bulletin SET title = '{$title}', content = '$content', status = '$bulletin_status', allowed_admin = '$all_allowed_admin' WHERE admin_bulletin_id = $bulletin_no LIMIT 1";
        } else {
            $query = "INSERT INTO admin_bulletin (admin_code, title, content, status, allowed_admin) VALUES ('$admin_code', '$title', '$content', '$bulletin_status', '$all_allowed_admin')";
        }
        
        $result = $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Add a new article
    public function add_new_article($article_no, $title, $description, $tags, $display_picture, $url, $content, $article_status = '2', $admin_code) {
        global $db_handle;
        global $system_object;
        if(!empty($article_no)) {
            $query = "UPDATE article SET title = '{$title}', description = '$description', keyword = '$tags', display_image = '$display_picture', content = '$content', url = '$url', status = '$article_status', created = NOW() WHERE article_id = $article_no LIMIT 1";

        } else {
            $query = "INSERT INTO article (admin_code, title, description, keyword, display_image, content, url, status) VALUES ('$admin_code', '$title', '$description', '$tags', '$display_picture', '$content', '$url', '$article_status')";

        }
        $result = $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0)
        {
            if($article_status == "1")
            {
                $article_id = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM article WHERE status = 1 ORDER BY created DESC "))[0]['article_id'];
                if(file_exists("../images/blog/".$display_picture))
                {
                    $image_url = "https://instafxng.com/images/blog/".$display_picture;
                }else{
                    $image_url = "https://instafxng.com/images/placeholder2.jpg";
                }
                $content =  <<<MAIL
        <div class="respond" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="background-color: #f3f1f2;">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">

                          <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                                <tr>
                                    <td align="center" height="70" style="height:70px;">
                                        <a href="" style="display: block; border-style: none !important; border: 0 !important;">
                                        <img  border="0" src="https://instafxng.com/images/ifxlogo.png" alt="InstaFxNg " /></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                    <tr>
                        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">


                            <div style="line-height: 35px; background-image: url("$image_url")">

                                <span style="color: rgba(205,210,209,0);">_</span>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                <tr>
                                    <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #343434; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 2px; line-height: 35px;" class="main-header">
                            <div style="line-height: 35px">
                                $title
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container590">
                                <tr>
                                    <td align="center" style="color: #888888; font-size: 16px; font-family: Work Sans, Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 30px">
                                           $description
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="center">
                            <table border="0" align="center" width="160" cellpadding="0" cellspacing="0" bgcolor="5caad2" style="">

                                <tr>
                                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; line-height: 26px;">


                                        <a href="https://instafxng.com/news1/id/$article_id/u/$url/">
                                        <div style="line-height: 26px;">
                                            <div style="color: #ffffff; text-decoration: none;">READ ON</div>
                                        </div>
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                </tr>

                            </table>
                        </td>
                    </tr>


                </table>

            </td>
        </tr>

        <tr class="hide">
            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>
        <tr>
            <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
        </tr>

    </table>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff">
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
MAIL;
                $old_posts = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM article WHERE status = 1 ORDER BY created DESC LIMIT 2, 3"));
                foreach($old_posts as $row)
                {
                    $_title = $row['title'];
                    $_description = $row['description'];
                    if(file_exists("../images/blog/".$row['display_image']))
                    {
                        $_image = "https://instafxng.com/images/blog/".$row['display_image'];
                    }else{
                        $_image = "https://instafxng.com/images/placeholder2.jpg";
                    }
                    $_url = "news1/id/".$row['article_id']."/u/".$row['url']."/";
                    $content.=  <<<MAIL
        
        <tr>
                        <td>
                            <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td align="center">
                                        <a href="" style=" border-style: none !important; border: 0 !important;"><img src="$_image" style="display: block; width: 280px;" width="280" border="0" alt="" /></a>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" width="5" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td width="5" height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                                </tr>
                            </table>

                            <table border="0" width="260" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td align="left" style="color: #3d3d3d; font-size: 22px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="align-center main-header">
                                        <div style="line-height: 35px">
                                            $_title
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td align="left">
                                        <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
                                            <tr>
                                                <td align="center">
                                                    <table align="center" width="40" border="0" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                                        <tr>
                                                            <td height="2" style="font-size: 2px; line-height: 2px;"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="15" style="font-size: 12px; line-height: 12px;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td align="left" style="color: #888888; font-size: 16px; font-family: Work Sans, Calibri, sans-serif; line-height: 24px;" class="align-center">
                                        <div style="line-height: 24px">
                                            $_description
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <table border="0" align="left" cellpadding="0" cellspacing="0" class="container590">
                                            <tr>
                                                <td align="center">
                                                    <table border="0" align="center" width="120" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; ">
                                                        <tr>
                                                            <td height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="color: #5caad2; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; line-height: 20px;">
                                                                <div style="line-height: 20px;">
                                                                    <a href="$_url" style="color: #5caad2; text-decoration: none;">READ MORE</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="8" style="font-size: 8px; line-height: 8px;">&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>
<tr class="hide">
            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>
        <tr>
            <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
        </tr>
MAIL;
                }
                $content.=  <<<MAIL
        </table>
            </td>
        </tr>

        <tr>
            <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
        </tr>

    </table>
        <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
        <tr>
            <td height="60" style="border-top: 1px solid #e0e0e0;font-size: 60px; line-height: 60px;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">
                    <tr>
                        <td>
                            <table border="0" width="300" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <!-- logo -->
                                    <td align="left">
                                        <a href="" style="display: block; border-style: none !important; border: 0 !important;"><img width="80" border="0" style="display: block; width: 80px;" src="images/ifxlogo.png" alt="" /></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="25" style="line-height: 25px;">
                                        <div style="color: #333333; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px;">
                                            <p><strong>Head Office Address:</strong> TBS Place, Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri Olofin, Lagos.</p>
                                            <p><strong>Lekki Office Address:</strong> Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway,Lagos state.</p>

                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table border="0" width="2" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td width="2" height="10" style="font-size: 10px; line-height: 10px;"></td>
                                </tr>
                            </table>
                            <table border="0" width="200" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td class="hide" height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" align="right" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <a href="https://facebook.com/InstaForexNigeria" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="https://instafxng.com/images/Facebook.png" alt=""></a>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <a href="https://twitter.com/instafxng" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="https://instafxng.com/images/Twitter.png" alt=""></a>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <a href="https://www.instagram.com/instafxng/" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="https://instafxng.com/images/instagram.png" alt=""></a>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <a href="https://www.youtube.com/channel/UC0Z9AISy_aMMa3OJjgX6SXw" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="https://instafxng.com/images/Youtube.png" alt=""></a>
                                                </td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                <td>
                                                    <a href="https://linkedin.com/company/instaforex-ng" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="https://instafxng.com/images/LinkedIn.png" alt=""></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="color: #888888; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; line-height: 23px;" class="text_color">
                                        <div style="color: #333333; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px;">
                                            <br/>www.instafxng.com
                                            <p><strong>Office Number:</strong> 08028281192</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f4f4f4">
        <tr>
            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                    <tr>
                        <td>
                            <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td align="left" style="color: #aaaaaa; font-size: 14px; font-family: Work Sans, Calibri, sans-serif; line-height: 24px;">
                                        <div style="font-size: 10px !important; padding: 15px; text-align: center;">
                                            <p>This email was sent to you by Instant Web-Net Technologies Limited, the official Nigerian Representative of Instaforex, operator and administrator of the website www.instafxng.com</p>
                                            <p>To ensure you continue to receive special offers and updates from us, please add support@instafxng.com to your address book. You may click <a href="https://instafxng.com/unsubscribe.php">unsubscribe</a> if you wish to stop receiving newsletter emails and other special promotions, offers and complementary gifts.</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="container590">
                                <tr>
                                    <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
        </tr>
    </table>
</div>
MAIL;
                $sender = "Instaforex NG";
                $campaign_category = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM campaign_category WHERE client_group = 46 "))[0]['campaign_category_id'];
                if(empty($campaign_category))
                {
                    $db_handle->runQuery("INSERT INTO campaign_category(title, description, client_group, status) VALUES('Article Readers (Visitors)', 'This category describes the people that have read an article and left a comment on the article.', '46', '1')");
                }
                $campaign_category = $db_handle->fetchAssoc($db_handle->runQuery("SELECT * FROM campaign_category WHERE client_group = 46 "))[0]['campaign_category_id'];
                $campaign_email_no = "";
                $system_object->add_new_campaign_email($campaign_email_no, $sender, $title, $campaign_category, $content, $admin_code, 3);
                $campaign_email_id = $db_handle->fetchAssoc($db_handle->runQuery("SELECT campaign_email_id FROM campaign_email WHERE campaign_category_id = '$campaign_category' AND status = '3' ORDER BY created DESC"))[0]['campaign_email_id'];
                $system_object->schedule_campaign($campaign_email_id, "email");
            }
            return true;
        } else {
            return false;
        }
    }


    
    // Publish bulletin
    public function get_publish_bulletin($bulletin_id) {
        global $db_handle;
        
        $query = "UPDATE admin_bulletin SET status = '1' WHERE admin_bulletin_id = $bulletin_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        
        return true;
    }
    
    // Add a new article
    public function update_system_message($system_message_id, $system_message_type, $subject, $content) {
        global $db_handle;
        
        // if it is an email, i.e. type == 1 and the subject is empty, do nothing
        // because an email message requires a subject to be set
        if($system_message_type == '1' && empty($subject)) {
            return false;
        }
        
        if($system_message_type == '1') {
            $query = "UPDATE system_message SET subject = '{$subject}', value = '$content' WHERE system_message_id = $system_message_id LIMIT 1";
        } else {
            $query = "UPDATE system_message SET value = '$content' WHERE system_message_id = $system_message_id LIMIT 1";
        }
        
        $result = $db_handle->runQuery($query);
        
        if($db_handle->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Create new job posting
    public function create_new_job($job_no, $job_title, $job_content, $job_status, $admin_code) {
        global $db_handle;

        if(!empty($job_no)) {
            $query = "UPDATE career_jobs SET title = '$job_title', detail = '$job_content', status = '$job_status'
                WHERE career_jobs_id = $job_no LIMIT 1";
        } else {
            //check whether admin_code generated by rand_string is already existing
            new_job_code:
            $job_code = rand_string_caps(6);
            if($db_handle->numRows("SELECT job_code FROM career_jobs WHERE job_code = '$job_code'") > 0) { goto new_job_code; };

            $query = "INSERT INTO career_jobs (admin_code, job_code, title, detail, status)
                VALUES ('$admin_code', '$job_code', '$job_title', '$job_content', '$job_status')";
        }

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    // Get course by id
    public function get_job_posting_by_id($job_id) {
        global $db_handle;

        $query = "SELECT * FROM career_jobs WHERE career_jobs_id = $job_id LIMIT 1";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        $fetched_data = $fetched_data[0];

        return $fetched_data ? $fetched_data : false;
    }

    public function add_new_prospect_source($source_title, $source_description) {
        global $db_handle;

        $query = "INSERT INTO prospect_source (source_name, source_description)
                VALUES ('$source_title', '$source_description')";

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_all_prospect_source() {
        global $db_handle;

        $query = "SELECT * FROM prospect_source";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);

        return $fetched_data ? $fetched_data : false;

    }

    // Confirm that the email address is not existing
    public function prospect_is_duplicate($email) {
        global $db_handle;

        $query = "SELECT * FROM prospect_biodata WHERE email_address = '$email'";
        $result = $db_handle->numRows($query);

        return $result ? true : false;
    }

    public function add_new_prospect_profile($admin_code, $last_name, $first_name, $middle_name = '', $email_address, $phone, $prospect_source) {
        global $db_handle;

        $query = "INSERT INTO prospect_biodata (admin_code, last_name, first_name, other_names, email_address, phone_number, prospect_source)
                VALUES ('$admin_code', '$last_name', '$first_name', '$middle_name', '$email_address', '$phone', $prospect_source);
                SELECT @p_id:= prospect_biodata_id FROM prospect_biodata WHERE email_address = '$email_address';
                INSERT IGNORE INTO prospect_sales_contact (prospect_id, admin_code) VALUES (@p_id, '$admin_code')";

        $db_handle->runQuery($query);
        return $db_handle->affectedRows() > 0 ? true : false;
    }

    public function get_all_account_officers() {
        global $db_handle;

        $query = "SELECT ao.account_officers_id, CONCAT(a.last_name, SPACE(1), a.first_name) AS account_officer_full_name
            FROM account_officers AS ao
            INNER JOIN admin AS a ON ao.admin_code = a.admin_code";
        $result = $db_handle->runQuery($query);
        $fetched_data = $db_handle->fetchAssoc($result);
        return $fetched_data;
    }
}

$admin_object = new AdminUser();