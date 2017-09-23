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
        default: $message = "Unknown"; break;
    }
    return $message;
}

/*
 * Table: Campaign Category
 * Column: client_group
 */
function client_group_query($client_group) {
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

    switch ($client_group) {
        case '1': $query = "SELECT user_code, first_name, email, phone FROM user WHERE campaign_subscribe = '1'"; break;
        case '2': $query = "SELECT user_code, first_name, email, phone FROM user WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND campaign_subscribe = '1'"; break;
        case '3': $query = "SELECT first_name, email, phone FROM free_training_campaign"; break;
        case '4': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON uv.user_code = u.user_code WHERE (uv.phone_status = '2') AND u.campaign_subscribe = '1'"; break;
        case '5': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_credential AS uc INNER JOIN user AS u ON uc.user_code = u.user_code WHERE (uc.doc_status = '111') AND u.campaign_subscribe = '1'"; break;
        case '6': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_bank AS ub INNER JOIN user AS u ON ub.user_code = u.user_code WHERE (ub.is_active = '1' AND ub.status = '2') AND u.campaign_subscribe = '1'"; break;
        case '7': $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE (u.password IS NULL OR u.password = '') AND u.created < DATE_SUB(NOW(), INTERVAL 9 MONTH) AND u.campaign_subscribe = '1'"; break;
        case '8': $query = "SELECT full_name AS first_name, email, phone FROM dinner_2016 WHERE attended = '2'"; break;
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
        case '22': $query = "SELECT user_code, first_name, email, phone FROM user WHERE user_id IN (1, 37, 167, 444, 8648, 14313, 14406, 14442)"; break;
        case '23': $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS first_name, u.email, u.phone FROM point_ranking AS pr INNER JOIN user AS u ON pr.user_code = u.user_code ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20"; break;
        case '24': $query = "SELECT first_name, email_address, phone_number FROM career_user_application AS cua INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code WHERE cua.status = '2'"; break;
        case '25': $query = "SELECT u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned BETWEEN '$top_trader_from_date' AND '$top_trader_to_date'"; break;
        case '26': $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 1"; break;
        case '27': $query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 2"; break;
        default: $query = false; break;
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

function biodata_competency_status($status) {
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