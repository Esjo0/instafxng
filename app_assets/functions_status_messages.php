<?php

/*
 * Table: career_jobs
 * Column: status
 */
function status_career_jobs($status) {
    switch ($status) {
        case '1': $message = "Closed"; break;
        case '2': $message = "Open"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: career_jobs
 * Column: status
 */
function status_edu_deposit($status) {
    switch ($status) {
        case '1': $message = "Deposit Initiated"; break;
        case '2': $message = "Notified"; break;
        case '3': $message = "Confirmed"; break;
        case '4': $message = "Declined"; break;
        case '5': $message = "Failed"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: user_deposit
 * Column: status
 */
function status_user_deposit($status) {
    switch ($status) {
        case '1': $message = "Deposit Initiated"; break;
        case '2': $message = "Notified"; break;
        case '3': $message = "Confirmation In Progress"; break;
        case '4': $message = "Confirmation Declined"; break;
        case '5': $message = "Confirmed"; break;
        case '6': $message = "Funding In Progress"; break;
        case '7': $message = "Funding Declined"; break;
        case '8': $message = "Completed"; break;
        case '9': $message = "Payment Failed"; break;
        case '10': $message = "Expired"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: user_withdrawal
 * Column: status
 */
function status_user_withdrawal($status) {
    switch ($status) {
        case '1': $message = "Withdrawal Initiated"; break;
        case '2': $message = "Account Check In Progress"; break;
        case '3': $message = "Account Check Failed"; break;
        case '4': $message = "Account Check Successful"; break;
        case '5': $message = "Withdrawal In Progress"; break;
        case '6': $message = "Withdrawal Declined"; break;
        case '7': $message = "Withdrawal Successful"; break;
        case '8': $message = "Payment In Progress"; break;
        case '9': $message = "Payment Declined"; break;
        case '10': $message = "Payment Made / Completed"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: user_deposit
 * Column: client_pay_method
 */
function status_user_deposit_pay_method($status) {
    switch ($status) {
        case '1': $message = "WebPay"; break;
        case '2': $message = "Internet Transfer"; break;
        case '3': $message = "ATM Transfer"; break;
        case '4': $message = "Bank Transfer"; break;
        case '5': $message = "Mobile Money Transfer"; break;
        case '6': $message = "Cash Deposit"; break;
        case '7': $message = "Office Funding"; break;
        case '8': $message = "Not Listed"; break;
        case '9': $message = "USSD"; break;
        default: $message = "Payment Method Unknown"; break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: status
 */
function status_admin($status) {
    switch ($status) {
        case '1': $message = "Active"; break;
        case '2': $message = "Inactive"; break;
        case '3': $message = "Suspended"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_course
 */
function status_edu_course($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Published"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_lesson
 */
function status_edu_lesson($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Published"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_lesson
 */
function lesson_rating($status)
{
    switch ($status)
    {
        case '0': $message = "No Ratings Yet"; break;
        case '1': $message = "Very Poor"; break;
        case '2': $message = "Poor"; break;
        case '3': $message = "Average"; break;
        case '4': $message = "Good"; break;
        case '5': $message = "Very Good"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: campaign_category
 * Column: status
 */
function status_campaign_category($status) {
    switch ($status) {
        case '1': $message = "Active"; break;
        case '2': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Article
 * Column: status
 */
function status_article($status) {
    switch ($status) {
        case '1': $message = "Published"; break;
        case '2': $message = "Draft"; break;
        case '3': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Admin Bulletin
 * Column: status
 */
function status_admin_bulletin($status) {
    switch ($status) {
        case '1': $message = "Published"; break;
        case '2': $message = "Draft"; break;
        case '3': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Snappy Help
 * Column: status
 */
function status_snappy_help($status) {
    switch ($status) {
        case '1': $message = "Active"; break;
        case '2': $message = "Inactive"; break;
        case '3': $message = "Draft"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Campaign Email
 * Column: status
 */
function status_campaign_email($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Published"; break;
        case '3': $message = "Approved"; break;
        case '4': $message = "Disapproved"; break;
        case '5': $message = "In Progress"; break;
        case '6': $message = "Completed"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Campaign SMS
 * Column: status
 */
function status_campaign_sms($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Published"; break;
        case '3': $message = "Approved"; break;
        case '4': $message = "Disapproved"; break;
        case '5': $message = "In Progress"; break;
        case '6': $message = "Completed"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Campaign Email
 * Column: status
 */
function status_solo_campaign_email($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Published"; break;
        case '3': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: User Account Flag
 * Column: status
 */
function status_account_flag($status) {
    switch ($status) {
        case '1': $message = "Active"; break;
        case '2': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: User Verification
 * Column: phone_status
 */
function status_user_verification($status) {
    switch ($status) {
        case '1': $message = "New"; break;
        case '2': $message = "Verified"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: 
 * Column: 
 */
function client_group_campaign_category($status) {
    switch ($status) {
        case '1': $message = "All Clients"; break;
        case '2': $message = "Last Month New Clients"; break;
        case '3': $message = "Free Training Campaign Clients"; break;
        case '4': $message = "Level 1 Clients"; break;
        case '5': $message = "Level 2 Clients"; break;
        case '6': $message = "Level 3 Clients"; break;
        case '7': $message = "Unverified Clients"; break;
        case '8': $message = "Dinner Clients"; break;
        case '9': $message = "Lagos Clients"; break;
        case '10': $message = "Online Training Students"; break;
        case '11': $message = "Lekki Training Students"; break;
        case '12': $message = "Diamond Training Students"; break;
        case '13': $message = "Past Forum Participants"; break;
        case '14': $message = "Clients Interested in Training"; break;
        case '15': $message = "Clients Interested in Funding"; break;
        case '16': $message = "Clients Interested in Bonuses"; break;
        case '17': $message = "Clients Interested in Investment"; break;
        case '18': $message = "Clients Interested in Services"; break;
        case '19': $message = "Clients Interested in Other Things"; break;
        case '20': $message = "Last Month Funding Clients"; break;
        case '21': $message = "Pencil Unbroken Reg"; break;
        case '22': $message = "In-house Test"; break;
        case '23': $message = "Top 20 Rank in Current Loyalty Year"; break;
        case '24': $message = "Career Application Submitted"; break;
        case '25': $message = "Top Traders"; break;
        case '26': $message = "Prospect - Pencil Comedy Event"; break;
        case '27': $message = "Prospect - 500 USD No-Deposit"; break;
        case '28': $message = "Online Trainee - Not Started"; break;
        case '29': $message = "Point Winners (Dec '16 - Oct '17)"; break;
        case '30': $message = "Commission Clients (Dec '16 - Oct '17)"; break;
        case '31': $message = "Online Training - Completed Course 1"; break;
        case '32': $message = "2017 Dinner Guests"; break;
        case '33': $message = "Office Funding Clients"; break;
        case '34': $message = "Failed SMS Clients"; break;
        case '35': $message = "Students Category 1"; break;
        case '36': $message = "Students Category 2"; break;
        case '37': $message = "Students Category 3"; break;
        case '38': $message = "Students Category 4"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

/*
 * Table: Campaign Category
 * Column: client_group
 */
function client_group_query($client_group, $campaign_type) {
    $from_date = date('Y-m-d', strtotime('first day of last month'));
    $to_date = date('Y-m-d', strtotime('last day of last month'));

    $current_day = date('d');

    if($current_day <= 15) {
        // Date will be from 16 - last day of last month
        $top_trader_from_date = date('Y-m', strtotime('first day of last month')) . '-16';
        $top_trader_to_date = date('Y-m-d', strtotime('last day of last month')) ;
    } else {
        // Date will be from 1 - 15th day of this month
        $top_trader_from_date = date('Y-m') . '-01';
        $top_trader_to_date = date('Y-m') . '-15';
    }

    if($campaign_type == 1) {
        // This is an email campaign

        switch ($client_group) {
            case '1': $query = "SELECT user_code, first_name, email, phone FROM user WHERE campaign_subscribe = '1' ORDER BY created ASC"; break;
            case '2': $query = "SELECT user_code, first_name, email, phone FROM user WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND campaign_subscribe = '1' ORDER BY created ASC"; break;
            case '3': $query = "SELECT first_name, email, phone FROM free_training_campaign ORDER BY created ASC"; break;
            case '4': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON uv.user_code = u.user_code WHERE (uv.phone_status = '2') AND u.campaign_subscribe = '1' ORDER BY u.created ASC"; break;
            case '5': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_credential AS uc INNER JOIN user AS u ON uc.user_code = u.user_code WHERE (uc.doc_status = '111') AND u.campaign_subscribe = '1' ORDER BY u.created ASC"; break;
            case '6': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_bank AS ub INNER JOIN user AS u ON ub.user_code = u.user_code WHERE (ub.is_active = '1' AND ub.status = '2') AND u.campaign_subscribe = '1' ORDER BY u.created ASC"; break;
            case '7': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE (u.password IS NULL OR u.password = '') AND u.created < DATE_SUB(NOW(), INTERVAL 9 MONTH) AND u.campaign_subscribe = '1' ORDER BY u.created ASC"; break;
            case '8': $query = "SELECT full_name AS first_name, email, phone FROM dinner_2016 WHERE attended = '2' ORDER BY created ASC"; break;
            case '9': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_meta AS um ON u.user_code = um.user_code LEFT JOIN state AS s ON um.state_id = s.state_id WHERE u.campaign_subscribe = '1' AND um.address LIKE '%Lagos%'"; break;
            case '10': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '3'"; break;
            case '11': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '2'"; break;
            case '12': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '1'"; break;
            case '13': $query = "SELECT first_name, email, phone FROM forum_participant"; break;
            case '14': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_training = '2'"; break;
            case '15': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_funding = '2'"; break;
            case '16': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_bonus = '2'"; break;
            case '17': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_investment = '2'"; break;
            case '18': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_services = '2'"; break;
            case '19': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_other = '2'"; break;
            case '20': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE u.campaign_subscribe = '1' AND (ud.status = '8' AND STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY ud.ifxaccount_id"; break;
            case '21': $query = "SELECT full_name AS first_name, email_address AS email, phone_number AS phone FROM pencil_comedy_reg"; break;
            case '22': $query = "SELECT user_code, first_name, email, phone FROM user WHERE email IN ('abegundeemmanuel@gmail.com', 'rightpma@gmail.com', 'utomudopercy@gmail.com', 'olagold4@yahoo.com', 'ademolaoyebode@gmail.com', 'Scargger2010560@gmail.com', 'Joshuagoke08@gmail.com', 'olasomimercy@gmail.com', 'estellynab38@yahoo.com', 'bunmzyfad@yahoo.com', 'estherogunsola463@yahoo.com', 'afujah@yahoo.com', 'ayoola@instafxng.com')"; break;
            case '23': $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS first_name, u.email, u.phone FROM point_ranking AS pr INNER JOIN user AS u ON pr.user_code = u.user_code ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20"; break;
            case '24': $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM career_user_application AS cua INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code WHERE cua.status = '2'"; break;
            case '25': $query = "SELECT u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned BETWEEN '$top_trader_from_date' AND '$top_trader_to_date'"; break;
            case '26': $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 1"; break;
            case '27': $query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 2"; break;
            case '28': $query = "SELECT CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS first_name, ftc.email, ftc.phone FROM free_training_campaign AS ftc LEFT JOIN user AS u on u.email = ftc.email WHERE training_centre = '3' AND ftc.email NOT IN (SELECT email AS c_email FROM user WHERE academy_signup IS NOT NULL)"; break;
            case '29': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM point_ranking_log AS prl INNER JOIN user AS u ON prl.user_code = u.user_code WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code"; break;
            case '30': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (td.date_earned BETWEEN '2016-12-01' AND '2017-11-30') AND u.user_code NOT IN (SELECT prl.user_code FROM point_ranking_log AS prl WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code) GROUP BY u.email"; break;
            case '31': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL GROUP BY ueel.user_code"; break;
            case '32': $query = "SELECT full_name AS first_name, email, phone FROM dinner_2017 WHERE attended = '1'"; break;
            case '33': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.deposit_origin IN ('2', '3') GROUP BY u.user_code"; break;
            case '34': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON u.user_code = uv.user_code WHERE uv.user_code = u.user_code AND u.password IS NULL"; break;
            case '35': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.academy_signup IS NOT NULL AND ueel.user_code IS NULL "; break;
            case '36': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id IN (1, 2, 3, 4) AND uefp.user_code IS NULL AND u.user_code NOT IN (SELECT u.user_code FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL) GROUP BY ueel.user_code"; break;
            case '37': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id > 4 AND uefp.user_code IS NULL GROUP BY ueel.user_code"; break;
            case '38': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code WHERE ued.status = '3' GROUP BY u.user_code"; break;
            default: $query = false; break;
        }

    } else {
        // This is an SMS campaign
        // Reformat queries and group by phone to avoid repetition of phone numbers

        switch ($client_group) {
            case '1': $query = "SELECT user_code, first_name, email, phone FROM user WHERE campaign_subscribe = '1' GROUP BY phone ORDER BY created ASC"; break;
            case '2': $query = "SELECT user_code, first_name, email, phone FROM user WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND campaign_subscribe = '1' GROUP BY phone ORDER BY created ASC"; break;
            case '3': $query = "SELECT first_name, email, phone FROM free_training_campaign GROUP BY phone ORDER BY created ASC"; break;
            case '4': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON uv.user_code = u.user_code WHERE (uv.phone_status = '2') AND u.campaign_subscribe = '1' GROUP BY u.phone ORDER BY u.created ASC"; break;
            case '5': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_credential AS uc INNER JOIN user AS u ON uc.user_code = u.user_code WHERE (uc.doc_status = '111') AND u.campaign_subscribe = '1' GROUP BY u.phone ORDER BY u.created ASC"; break;
            case '6': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_bank AS ub INNER JOIN user AS u ON ub.user_code = u.user_code WHERE (ub.is_active = '1' AND ub.status = '2') AND u.campaign_subscribe = '1' GROUP BY u.phone ORDER BY u.created ASC"; break;
            case '7': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE (u.password IS NULL OR u.password = '') AND u.created < DATE_SUB(NOW(), INTERVAL 9 MONTH) AND u.campaign_subscribe = '1' GROUP BY u.phone ORDER BY u.created ASC"; break;
            case '8': $query = "SELECT full_name AS first_name, email, phone FROM dinner_2016 WHERE attended = '2' GROUP BY phone ORDER BY created ASC"; break;
            case '9': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_meta AS um ON u.user_code = um.user_code LEFT JOIN state AS s ON um.state_id = s.state_id WHERE u.campaign_subscribe = '1' AND um.address LIKE '%Lagos%' GROUP BY u.phone"; break;
            case '10': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '3' GROUP BY phone"; break;
            case '11': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '2' GROUP BY phone"; break;
            case '12': $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '1' GROUP BY phone"; break;
            case '13': $query = "SELECT first_name, email, phone FROM forum_participant GROUP BY phone"; break;
            case '14': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_training = '2' GROUP BY u.phone"; break;
            case '15': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_funding = '2' GROUP BY u.phone"; break;
            case '16': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_bonus = '2' GROUP BY u.phone"; break;
            case '17': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_investment = '2' GROUP BY u.phone"; break;
            case '18': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_services = '2' GROUP BY u.phone"; break;
            case '19': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_other = '2' GROUP BY u.phone"; break;
            case '20': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE u.campaign_subscribe = '1' AND (ud.status = '8' AND STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY u.phone GROUP BY ud.ifxaccount_id"; break;
            case '21': $query = "SELECT full_name AS first_name, email_address AS email, phone_number AS phone FROM pencil_comedy_reg"; break;
            case '22': $query = "SELECT user_code, first_name, email, phone FROM user WHERE email IN ('abegundeemmanuel@gmail.com', 'rightpma@gmail.com', 'utomudopercy@gmail.com', 'olagold4@yahoo.com', 'ademolaoyebode@gmail.com', 'Scargger2010560@gmail.com', 'Joshuagoke08@gmail.com', 'olasomimercy@gmail.com', 'estellynab38@yahoo.com', 'bunmzyfad@yahoo.com', 'estherogunsola463@yahoo.com', 'afujah@yahoo.com', 'ayoola@instafxng.com') GROUP BY phone"; break;
            case '23': $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS first_name, u.email, u.phone FROM point_ranking AS pr INNER JOIN user AS u ON pr.user_code = u.user_code ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20"; break;
            case '24': $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM career_user_application AS cua INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code WHERE cua.status = '2' GROUP BY phone"; break;
            case '25': $query = "SELECT u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned BETWEEN '$top_trader_from_date' AND '$top_trader_to_date' GROUP BY phone"; break;
            case '26': $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 1 GROUP BY phone"; break;
            case '27': $query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 2 GROUP BY phone"; break;
            case '28': $query = "SELECT CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS first_name, ftc.email, ftc.phone FROM free_training_campaign AS ftc LEFT JOIN user AS u on u.email = ftc.email WHERE training_centre = '3' AND ftc.email NOT IN (SELECT email AS c_email FROM user WHERE academy_signup IS NOT NULL) GROUP BY ftc.phone"; break;
            case '29': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM point_ranking_log AS prl INNER JOIN user AS u ON prl.user_code = u.user_code WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code"; break;
            case '30': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (td.date_earned BETWEEN '2016-12-01' AND '2017-11-30') AND u.user_code NOT IN (SELECT prl.user_code FROM point_ranking_log AS prl WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code) GROUP BY u.email"; break;
            case '31': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL GROUP BY ueel.u.phone"; break;
            case '32': $query = "SELECT full_name AS first_name, email, phone FROM dinner_2017 WHERE attended = '1' GROUP BY u.phone"; break;
            case '33': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.deposit_origin IN ('2', '3') GROUP BY u.phone"; break;
            case '34': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON u.user_code = uv.user_code WHERE uv.user_code = u.user_code AND u.password IS NULL GROUP BY u.phone"; break;
            case '35': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.academy_signup IS NOT NULL AND ueel.user_code IS NULL GROUP BY u.phone"; break;
            case '36': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id IN (1, 2, 3, 4) AND uefp.user_code IS NULL AND u.user_code NOT IN (SELECT u.user_code FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL) GROUP BY u.phone"; break;
            case '37': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id > 4 AND uefp.user_code IS NULL GROUP BY u.phone"; break;
            case '38': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code WHERE ued.status = '3' GROUP BY u.phone"; break;
            default: $query = false; break;
        }

    }

    return $query;
}

function status_fc_type($status) {
    switch ($status) {
        case '1': $message = "Credit / User Deposit"; break;
        case '2': $message = "Credit / User Withdrawal"; break;
        case '3': $message = "Debit"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

function status_trans_type($status) {
    switch ($status) {
        case '1': $message = "Credit"; break;
        case '2': $message = "Debit"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

function publish_status($status) {
    switch ($status) {
        case '1': $message = "Draft"; break;
        case '2': $message = "Publish"; break;
        case '3': $message = "Inactive"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: User Credential
 * Column: status
 */
function user_credential_status($status) {
    switch ($status) {
        case '1': $message = "Awaiting Moderation"; break;
        case '2': $message = "Approved"; break;
        case '3': $message = "Not Approved"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Free Training Campaign
 * Column: training_interest
 */
function free_training_campaign_interest($status) {
    switch ($status) {
        case '1': $message = "Not Yet"; break;
        case '2': $message = "Yes"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: Free Training Campaign
 * Column: training_centre
 */
function free_training_campaign_centre($status) {
    switch ($status) {
        case '1': $message = "Diamond Estate"; break;
        case '2': $message = "Ikota Office"; break;
        case '3': $message = "Online"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

/*
 * Table: User Verification
 * Column: phone_status
 */
function phone_code_status($status) {
    switch ($status) {
        case '1': $message = "New"; break;
        case '2': $message = "Used"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}


/*
 * Table: Dinner 2016
 * Column: Interest
 */
function dinner_interest_status($status) {
    switch ($status) {
        case '1': $message = "Not Yet"; break;
        case '2': $message = "Yes"; break;
        case '3': $message = "No"; break;
        case '4': $message = "Maybe"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

/*
 * Table: Dinner 2016
 * Column: invite
 */
function dinner_invite_status($status) {
    switch ($status) {
        case '1': $message = "No"; break;
        case '2': $message = "Yes"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}



/*
 * Table: Dinner 2017
 * Column: invite
 */
function dinner_2017_invite_status($status) {
    switch ($status) {
        case '0': $message = "Not Sent"; break;
        case '1': $message = "Sent"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

function dinner_2017_confirmation_status($status) {
    switch ($status) {
        case '2': $message = "Confirmed"; break;
        case '3': $message = "Declined"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

/*
 * Table: Dinner 2017
 * Column: Ticket Type
 */
function dinner_ticket_type($status) {
    switch ($status)
    {
        case '0': $message = "Single Client"; break;
        case '1': $message = "Plus One Client (Double)"; break;
        case '2': $message = "VIP Single"; break;
        case '3': $message = "VIP Plus One (Double)"; break;
        case '4': $message = "Hired Help"; break;
        case '5': $message = "Staff"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

/*
 * Table: Dinner 2017
 * Column: Confirmation Status
 */
function dinner_confirmation_status($status) {
    switch ($status)
    {
        case '0': $message = "Pending"; break;
        case '1': $message = "Maybe"; break;
        case '2': $message = "Confirmed"; break;
        case '3': $message = "Declined"; break;
        case '4': $message = "Attendance Confirmed"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}


/*
 * Table: Attendance Sysytem
 * Column: status
 */
function office_location($status)
{
    switch ($status) {
        case '1': $message = "HFP Eastline Office"; break;
        case '2': $message = "Diamond Estate Office"; break;
        default: $message = "Status Unknown"; break;
    }
    return $message;
}

function forum_reg_venue($status) {
    switch ($status) {
        case '1': $message = "Diamond Estate"; break;
        case '2': $message = "Eastline Complex"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

function career_application_status($status) {
    switch ($status) {
        case '1': $message = "Not Submitted"; break;
        case '2': $message = "Submitted"; break;
        case '3': $message = "Review"; break;
        case '4': $message = "No Review"; break;
        case '5': $message = "Interviewed"; break;
        case '6': $message = "Employed"; break;
        case '7': $message = "Not Employed"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}

function biodata_sex_status($status) {
    switch ($status) {
        case 'M': $message = "Male"; break;
        case 'F': $message = "Female"; break;
        default: $message = "Unknown"; break;
    }

    return $message;
}

function biodata_marriage_status($status) {
    switch ($status) {
        case 'S': $message = "Single"; break;
        case 'M': $message = "Married"; break;
        default: $message = "Unknown"; break;
    }

    return $message;
}

function biodata_competency_status($status)
{
    switch ($status) {
        case '1': $message = "Beginner"; break;
        case '2': $message = "Advanced"; break;
        case '3': $message = "Professional"; break;
        case '4': $message = "Master"; break;
        case '5': $message = "Certified"; break;
        default: $message = "Unknown"; break;
    }

    return $message;
}

function biodata_achievement_status($status) {
    switch ($status) {
        case '1': $message = "Certification"; break;
        case '2': $message = "Course"; break;
        case '3': $message = "Honour/Award"; break;
        case '4': $message = "Project"; break;
        default: $message = "Unknown"; break;
    }

    return $message;
}

function status_point_claimed($status) {
    switch($status) {
        case '1': $message = "New Request"; break;
        case '2': $message = "Completed"; break;
        case '3': $message = "Failed"; break;
        default: $message = "Unknown"; break;
    }

    return $message;
}


/*
 * Table: Dinner 2017
 * Column: invite
 */
function project_management_status($status)
{
    switch ($status)
    {
        case '0': $message = "Suspended"; break;
        case '1': $message = "In Progress"; break;
        case '2': $message = "Completed"; break;
        default: $message = "Unknown"; break;
    }
    return $message;
}