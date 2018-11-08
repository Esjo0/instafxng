<?php


/**
 * Editable schedule mode
 */
function target_period($id)
{
    switch ($id) {
        case '1':
            $period = "January";
            break;
        case '2':
            $period = "February";
            break;
        case '3':
            $period = "March";
            break;
        case '4':
            $period = "April";
            break;
        case '5':
            $period = "May";
            break;
        case '6':
            $period = "June";
            break;
        case '7':
            $period = "July";
            break;
        case '8':
            $period = "August";
            break;
        case '9':
            $period = "September";
        break;
        case '10':
            $period = "October";
            break;
        case '11':
            $period = "November";
            break;
        case '12':
            $period = "December";
            break;
        case '1-12':
            $period = "Annual";
            break;
        case '1-6':
            $period = "First Half";
            break;
        case '6-12':
            $period = "Second Half";
            break;
        case '1-3':
            $period = "First Quarter";
            break;
        case '3-6':
            $period = "Second Quarter";
            break;
        case '6-9':
            $period = "Third Quarter";
            break;
        case '9-12':
            $period = "Fourth Quarter";
            break;
        default:
            $period = "";
            break;
    }
    return $period;

}

/**
 * Black friday tires
 * table black_friday_2018
 * column tire
 */
function black_friday_tire($id)
{
    switch ($id) {
        case '1':
            $tire = "PLATINUM";
            break;
        case '2':
            $tire = "GOLD";
            break;
        case '3':
            $tire = "SILVER";
            break;
        case '4':
            $tire = "BRONZE PRO";
            break;
        case '5':
            $tire = "BRONZE LITE";
            break;
        default:
            $tire = " ";
            break;
    }
    return $tire;

}

function black_friday_tire_target($id)
{
    switch ($id) {
        case '1':
            $tire = 2000;
            break;
        case '2':
            $tire = 1000;
            break;
        case '3':
            $tire = 500;
            break;
        case '4':
            $tire = 200;
            break;
        case '5':
            $tire = 100;
            break;
        default:
            $tire = " ";
            break;
    }
    return $tire;

}

/**
 * Editable schedule mode
 */
function training_mode($id)
{
    switch ($id) {
        case '1':
            $address = "Online";
            break;
        case '2':
            $address = "Offline";
            break;
        default:
            $address = "";
            break;
    }
    return $address;

}

/**
 * Editable office addresses
 */
function office_addresses($id)
{
    switch ($id) {
        case '1':
            $address = "Block 1A, Plot 8, Diamond Estate, Estate Bus-Stop, LASU/Isheri road, Isheri-Olofin, Lagos. ";
            break;
        case '3':
            $address = "Block A3, Suite 508/509 Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout, along Lekki - Epe expressway, Lagos";
            break;
        default:
            $address = "";
            break;
    }
    return $address;

}

/*
* Table: user_deposit_refund_type
* Column: issue_type
*/
function refund_type($refund_type)
{
    switch ($refund_type) {
        case '1':
            $type = "Omission of Transaction ID";
            break;
        case '2':
            $type = "Third Party Transaction";
            break;
        case '3':
            $type = "Wrong remark";
            break;
        default:
            $type = "Unknown";
            break;
    }
    return $type;
}

/*
* Table: unverified_campaign_mail_log
* Column: email_flag position
*/
function position_status($number)
{
    switch ($number) {
        case '1':
            $position = "1st";
            break;
        case '2':
            $position = "2nd";
            break;
        case '3':
            $position = "3rd";
            break;
        case '4':
            $position = "4th";
            break;
        case '5':
            $position = "5th";
            break;
        default:
            $position = "$number";
            break;
    }
    return $position;
}

/*
* Table: partner
* Column: status
*/
function partner_status($status)
{
    switch ($status) {
        case '1':
            $message = "New";
            break;
        case '2':
            $message = "Active";
            break;
        case '3':
            $message = "Inactive";
            break;
        case '4':
            $message = "Suspended";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
* Table: free training campaign
* Column: entry_point
*/
function training_entry_point($status)
{
    switch ($status) {
        case '1':
            $message = "Incoming Calls";
            break;
        case '2':
            $message = "Whats App";
            break;
        case '3':
            $message = "Support Mails";
            break;
        case '4':
            $message = "Walk in Clients";
            break;
        case '5':
            $message = "Facebook Advert";
            break;
        case '6':
            $message = "Referrals";
            break;
        case '7':
            $message = "Instagram";
            break;
        default:
            $message = "Channel Unknown";
            break;
    }
    return $message;
}

/*
* Table: forum_participants
* Column: entry_route
*/
function entry_route_forum_participants($status)
{
    switch ($status) {
        case '1':
            $message = "Facebook";
            break;
        case '2':
            $message = "Instagram";
            break;
        case '3':
            $message = "Twitter";
            break;
        case '4':
            $message = "WhatsApp";
            break;
        case '5':
            $message = "Email Invite";
            break;
        case '6':
            $message = "SMS Invite";
            break;
        case '7':
            $message = "Instafxng Website";
            break;
        case '8':
            $message = "Friend";
            break;
        case '9':
            $message = "Other means";
            break;
        default:
            $message = "Channel Unknown";
            break;
    }
    return $message;
}

/*
* Table: operations_log
* Column: status
*/
function status_operations($status)
{
    switch ($status) {
        case '0':
            $message = "Opened";
            break;
        case '1':
            $message = "Closed";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: career_jobs
 * Column: status
 */
function status_career_jobs($status)
{
    switch ($status) {
        case '1':
            $message = "Closed";
            break;
        case '2':
            $message = "Open";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: career_jobs
 * Column: status
 */
function status_edu_deposit($status)
{
    switch ($status) {
        case '1':
            $message = "Deposit Initiated";
            break;
        case '2':
            $message = "Notified";
            break;
        case '3':
            $message = "Confirmed";
            break;
        case '4':
            $message = "Declined";
            break;
        case '5':
            $message = "Failed";
            break;
        case '6':
            $message = "Pending";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: user_deposit
 * Column: status
 */
function status_user_deposit($status)
{
    switch ($status) {
        case '1':
            $message = "Deposit Initiated";
            break;
        case '2':
            $message = "Notified";
            break;
        case '3':
            $message = "Confirmation In Progress";
            break;
        case '4':
            $message = "Confirmation Declined";
            break;
        case '5':
            $message = "Confirmed";
            break;
        case '6':
            $message = "Funding In Progress";
            break;
        case '7':
            $message = "Funding Declined";
            break;
        case '8':
            $message = "Completed";
            break;
        case '9':
            $message = "Payment Failed";
            break;
        case '10':
            $message = "Expired";
            break;
        case '11':
            $message = "Refund";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: user_withdrawal
 * Column: status
 */
function status_user_withdrawal($status)
{
    switch ($status) {
        case '1':
            $message = "Withdrawal Initiated";
            break;
        case '2':
            $message = "Account Check In Progress";
            break;
        case '3':
            $message = "Account Check Failed";
            break;
        case '4':
            $message = "Account Check Successful";
            break;
        case '5':
            $message = "Withdrawal In Progress";
            break;
        case '6':
            $message = "Withdrawal Declined";
            break;
        case '7':
            $message = "Withdrawal Successful";
            break;
        case '8':
            $message = "Payment In Progress";
            break;
        case '9':
            $message = "Payment Declined";
            break;
        case '10':
            $message = "Payment Made / Completed";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: user_deposit
 * Column: client_pay_method
 */
function status_user_deposit_pay_method($status)
{
    switch ($status) {
        case '1':
            $message = "WebPay";
            break;
        case '2':
            $message = "Internet Transfer";
            break;
        case '3':
            $message = "ATM Transfer";
            break;
        case '4':
            $message = "Bank Transfer";
            break;
        case '5':
            $message = "Mobile Money Transfer";
            break;
        case '6':
            $message = "Cash Deposit";
            break;
        case '7':
            $message = "Office Funding";
            break;
        case '8':
            $message = "Not Listed";
            break;
        case '9':
            $message = "USSD";
            break;
        case '10':
            $message = "PayStack";
            break;
        default:
            $message = "Payment Method Unknown";
            break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: status
 */
function status_admin($status)
{
    switch ($status) {
        case '1':
            $message = "Active";
            break;
        case '2':
            $message = "Inactive";
            break;
        case '3':
            $message = "Suspended";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_course
 */
function status_edu_course($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Published";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_lesson
 */
function status_edu_lesson($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Published";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: admin
 * Column: edu_lesson
 */
function lesson_rating($status)
{
    switch ($status) {
        case '0':
            $message = "No Ratings Yet";
            break;
        case '1':
            $message = "Very Poor";
            break;
        case '2':
            $message = "Poor";
            break;
        case '3':
            $message = "Average";
            break;
        case '4':
            $message = "Good";
            break;
        case '5':
            $message = "Very Good";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: campaign_category
 * Column: status
 */
function status_campaign_category($status)
{
    switch ($status) {
        case '1':
            $message = "Active";
            break;
        case '2':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Article
 * Column: status
 */
function status_article($status)
{
    switch ($status) {
        case '1':
            $message = "Published";
            break;
        case '2':
            $message = "Draft";
            break;
        case '3':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Admin Bulletin
 * Column: status
 */
function status_admin_bulletin($status)
{
    switch ($status) {
        case '1':
            $message = "Published";
            break;
        case '2':
            $message = "Draft";
            break;
        case '3':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Snappy Help
 * Column: status
 */
function status_snappy_help($status)
{
    switch ($status) {
        case '1':
            $message = "Active";
            break;
        case '2':
            $message = "Inactive";
            break;
        case '3':
            $message = "Draft";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Campaign Email
 * Column: status
 */
function status_campaign_email($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Published";
            break;
        case '3':
            $message = "Approved";
            break;
        case '4':
            $message = "Disapproved";
            break;
        case '5':
            $message = "In Progress";
            break;
        case '6':
            $message = "Completed";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Campaign SMS
 * Column: status
 */
function status_campaign_sms($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Published";
            break;
        case '3':
            $message = "Approved";
            break;
        case '4':
            $message = "Disapproved";
            break;
        case '5':
            $message = "In Progress";
            break;
        case '6':
            $message = "Completed";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Campaign Email
 * Column: status
 */
function status_solo_campaign_email($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Published";
            break;
        case '3':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: User Account Flag
 * Column: status
 */
function status_account_flag($status)
{
    switch ($status) {
        case '1':
            $message = "Active";
            break;
        case '2':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: User Verification
 * Column: phone_status
 */
function status_user_verification($status)
{
    switch ($status) {
        case '1':
            $message = "New";
            break;
        case '2':
            $message = "Verified";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: 
 * Column: 
 */
function client_group_campaign_category($status)
{
    switch ($status) {
        case '1':
            $message = "All Clients";
            break;
        case '2':
            $message = "Last Month New Clients";
            break;
        case '3':
            $message = "Free Training Campaign Clients";
            break;
        case '4':
            $message = "Level 1 Clients";
            break;
        case '5':
            $message = "Level 2 Clients";
            break;
        case '6':
            $message = "Level 3 Clients";
            break;
        case '7':
            $message = "Unverified Clients";
            break;
        case '8':
            $message = "Dinner Clients";
            break;
        case '9':
            $message = "Lagos Clients";
            break;
        case '10':
            $message = "Online Training Students";
            break;
        case '11':
            $message = "Lekki Training Students";
            break;
        case '12':
            $message = "Diamond Training Students";
            break;
        case '13':
            $message = "Past Forum Participants";
            break;
        case '14':
            $message = "Clients Interested in Training";
            break;
        case '15':
            $message = "Clients Interested in Funding";
            break;
        case '16':
            $message = "Clients Interested in Bonuses";
            break;
        case '17':
            $message = "Clients Interested in Investment";
            break;
        case '18':
            $message = "Clients Interested in Services";
            break;
        case '19':
            $message = "Clients Interested in Other Things";
            break;
        case '20':
            $message = "Last Month Funding Clients";
            break;
        case '21':
            $message = "Pencil Unbroken Reg";
            break;
        case '22':
            $message = "In-house Test";
            break;
        case '23':
            $message = "Top 20 Rank in Current Loyalty Year";
            break;
        case '24':
            $message = "Career Application Submitted";
            break;
        case '25':
            $message = "Top Traders";
            break;
        case '26':
            $message = "Prospect - Pencil Comedy Event";
            break;
        case '27':
            $message = "Prospect - 500 USD No-Deposit";
            break;
        case '28':
            $message = "Online Trainee - Not Started";
            break;
        case '29':
            $message = "Point Winners (Dec '16 - Oct '17)";
            break;
        case '30':
            $message = "Commission Clients (Dec '16 - Oct '17)";
            break;
        case '31':
            $message = "Online Training - Completed Course 1";
            break;
        case '32':
            $message = "2017 Dinner Guests";
            break;
        case '33':
            $message = "Office Funding Clients";
            break;
        case '34':
            $message = "Failed SMS Clients";
            break;
        case '35':
            $message = "Students Category 1";
            break;
        case '36':
            $message = "Students Category 2";
            break;
        case '37':
            $message = "Students Category 3";
            break;
        case '38':
            $message = "Students Category 4";
            break;
        case '39':
            $message = "3 Months Active Clients";
            break;
        case '40':
            $message = "6 Months Active Clients";
            break;
        case '41':
            $message = "12 Months Active Clients";
            break;
        case '42':
            $message = "3 Months Inactive Clients";
            break;
        case '43':
            $message = "6 Months Inactive Clients";
            break;
        case '44':
            $message = "12 Months Inactive Clients";
            break;
        case '45':
            $message = "Non-ILPR Clients";
            break;
        case '46':
            $message = "Article Readers (Visitors)";
            break;
        case '47':
            $message = "Last Months New Clients with Accounts";
            break;
        case '48':
            $message = "Last Months New Clients without Accounts and have no Training";
            break;
        case '49':
            $message = "Last Months New Clients without Accounts and have Training";
            break;
        case '50':
            $message = "Last Months New Clients not yet funded above $50";
            break;
        case '51':
            $message = "Clients that funded before March 22, 2018 and haven't funded till date.";
            break;
        case '52':
            $message = "ILPR Campaign leads that came into our system in April";
            break;
        case '53':
            $message = "All clients who made deposit last month";
            break;
        case '54':
            $message = "All clients who made withdrawals last month";
            break;
        case '55':
            $message = "All inactive clients before May 1 2018.";
            break;
        case '56':
            $message = "All who have used WebPay for Deposit.";
            break;
        case '57':
            $message = "All signal user.";
            break;
        case '58':
            $message = "VIP Clients";
            break;
        case '59':
            $message = "Independence Contest Participant";
            break;
        case '60':
            $message = "Platinum Commission Clients";
            break;
        case '61':
            $message = "Gold Commission Clients";
            break;
        case '62':
            $message = "Silver Commission Clients";
            break;
        case '63':
            $message = "Bronze Commission Clients";
            break;
        case '64':
            $message = "Student Category 0";
            break;
        case '65':
            $message = "Last Month New Clients not yet funded";
            break;
        case '66':
            $message = "Last Month Trading Clients";
            break;
        case '67':
            $message = "Splurge participant without tier";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Campaign Category
 * Column: client_group
 */
function client_group_query($client_group, $campaign_type)
{
    $from_date = date('Y-m-d', strtotime('first day of last month'));
    $to_date = date('Y-m-d', strtotime('last day of last month'));

    $date_today = date('Y-m-d');

    $current_day = date('d');
    $current_month = date('n');

    if ($current_day <= 15) {
        // Date will be from 16 - last day of last month
        $top_trader_from_date = date('Y-m', strtotime('first day of last month')) . '-16';
        $top_trader_to_date = date('Y-m-d', strtotime('last day of last month'));
    } else {
        // Date will be from 1 - 15th day of this month
        $top_trader_from_date = date('Y-m') . '-01';
        $top_trader_to_date = date('Y-m') . '-15';
    }

    if ($campaign_type == 1) {
        // This is an email campaign

        switch ($client_group) {
            case '1':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE campaign_subscribe = '1'";
                break;
            case '2':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND campaign_subscribe = '1'";
                break;
            case '3':
                $query = "SELECT first_name, email, phone FROM free_training_campaign";
                break;
            case '4':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON uv.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code LEFT JOIN user_credential AS uc ON u.user_code = uc.user_code WHERE (uv.phone_status = '2') AND (uc.doc_status != '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '5':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_credential AS uc INNER JOIN user AS u ON uc.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code WHERE (uc.doc_status = '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '6':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_bank AS ub INNER JOIN user AS u ON ub.user_code = u.user_code WHERE (ub.is_active = '1' AND ub.status = '2') AND u.campaign_subscribe = '1'";
                break;
            case '7':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE (u.password IS NULL OR u.password = '') AND u.created < DATE_SUB(NOW(), INTERVAL 2 MONTH) AND u.campaign_subscribe = '1'";
                break;
            case '8':
                $query = "SELECT full_name AS first_name, email, phone FROM dinner_2016 WHERE attended = '2'";
                break;
            case '9':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_meta AS um ON u.user_code = um.user_code LEFT JOIN state AS s ON um.state_id = s.state_id WHERE u.campaign_subscribe = '1' AND um.address LIKE '%Lagos%'";
                break;
            case '10':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '3'";
                break;
            case '11':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '2'";
                break;
            case '12':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '1'";
                break;
            case '13':
                $query = "SELECT first_name, email, phone FROM forum_participant";
                break;
            case '14':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_training = '2'";
                break;
            case '15':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_funding = '2'";
                break;
            case '16':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_bonus = '2'";
                break;
            case '17':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_investment = '2'";
                break;
            case '18':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_services = '2'";
                break;
            case '19':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_other = '2'";
                break;
            case '20':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE u.campaign_subscribe = '1' AND (ud.status = '8' AND STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY ud.ifxaccount_id";
                break;
            case '21':
                $query = "SELECT full_name AS first_name, email_address AS email, phone_number AS phone FROM pencil_comedy_reg";
                break;
            case '22':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE email IN ('esan@sludasoft.com', 'utomudopercy@gmail.com', 'olagold4@yahoo.com', 'ademolaoyebode@gmail.com', 'Joshuagoke08@gmail.com', 'olasomimercy@gmail.com', 'estellynab38@yahoo.com', 'bunmzyfad@yahoo.com', 'estherogunsola463@yahoo.com', 'afujah@yahoo.com', 'ayoola@instafxng.com')";
                break;
            case '23':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS first_name, u.email, u.phone FROM point_ranking AS pr INNER JOIN user AS u ON pr.user_code = u.user_code ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20";
                break;
            case '24':
                $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM career_user_application AS cua INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code WHERE cua.status = '2'";
                break;
            case '25':
                $query = "SELECT u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned BETWEEN '$top_trader_from_date' AND '$top_trader_to_date'";
                break;
            case '26':
                $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 1";
                break;
            case '27':
                $query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 2";
                break;
            case '28':
                $query = "SELECT CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS first_name, ftc.email, ftc.phone FROM free_training_campaign AS ftc LEFT JOIN user AS u on u.email = ftc.email WHERE training_centre = '3' AND ftc.email NOT IN (SELECT email AS c_email FROM user WHERE academy_signup IS NOT NULL)";
                break;
            case '29':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM point_ranking_log AS prl INNER JOIN user AS u ON prl.user_code = u.user_code WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code";
                break;
            case '30':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (td.date_earned BETWEEN '2016-12-01' AND '2017-11-30') AND u.user_code NOT IN (SELECT prl.user_code FROM point_ranking_log AS prl WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code) GROUP BY u.email";
                break;
            case '31':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL GROUP BY ueel.user_code";
                break;
            case '32':
                $query = "SELECT full_name AS first_name, email, phone FROM dinner_2017 WHERE attended = '1'";
                break;
            case '33':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.deposit_origin IN ('2', '3') GROUP BY u.user_code";
                break;
            case '34':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON u.user_code = uv.user_code WHERE uv.user_code = u.user_code AND u.password IS NULL";
                break;
            case '35':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.academy_signup IS NOT NULL AND ueel.user_code IS NULL ";
                break;
            case '36':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id IN (1, 2, 3, 4) AND uefp.user_code IS NULL AND u.user_code NOT IN (SELECT u.user_code FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL) GROUP BY ueel.user_code";
                break;
            case '37':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id > 4 AND uefp.user_code IS NULL GROUP BY ueel.user_code";
                break;
            case '38':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code WHERE ued.status = '3' GROUP BY u.user_code";
                break;
            case '39':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 3 MONTH) GROUP BY u.email) GROUP BY u.email";
                break;
            case '40':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY u.email) GROUP BY u.email";
                break;
            case '41':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY u.email) GROUP BY u.email";
                break;
            case '42':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 3 MONTH)) GROUP BY u.user_code";
                break;
            case '43':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 6 MONTH)) GROUP BY u.user_code";
                break;
            case '44':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 12 MONTH)) GROUP BY u.user_code";
                break;
            case '45':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_ifxaccount AS ui INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (ui.type = '2') GROUP BY u.email";
                break;
            case '46':
                $query = "SELECT full_name AS first_name, email FROM article_visitors ";
                break;
            case '47':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code = u.user_code AND ui.type = '1' AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '48':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code IS NULL AND ftc.email IS NULL AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '49':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code IS NULL AND ftc.email IS NOT NULL AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '50':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND u.user_code = ui.user_code AND ui.ifxaccount_id = ud.ifxaccount_id AND ud.real_dollar_equivalent < 50.00 AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '51':
                $query = "SELECT u.first_name, u.email, u.phone, u.status, u.user_code, ud.order_complete_time FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND u.user_code NOT IN (SELECT u.user_code FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '2018-03-22' AND '$date_today') GROUP BY u.user_code ";
                break;
            case '52':
                $query = "SELECT user.user_code, user.first_name, user.email, campaign_leads.phone FROM campaign_leads, user WHERE(STR_TO_DATE(campaign_leads.created, '%Y-%m-%d') BETWEEN '2018-04-01' AND '2018-04-30') AND campaign_leads.email = user.email AND campaign_leads.interest = '2' GROUP BY user.email";
                break;
            case '53':
                $query = "SELECT '$from_date' AS date_from, '$to_date' AS date_to, u.first_name, u.email, u.phone, u.user_code FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' AND u.user_code NOT IN (SELECT u.user_code FROM user_withdrawal AS uw INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE uw.status = '10' AND STR_TO_DATE(uw.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY u.user_code ";
                break;
            case '54':
                $query = "SELECT '$from_date' AS date_from, '$to_date' AS date_to, u.first_name, u.email, u.phone, u.user_code FROM user_withdrawal AS uw INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE uw.status = '10' AND STR_TO_DATE(uw.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'  GROUP BY u.user_code ";
                break;
            case '55':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN ( SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '2018-07-20' AND '2018-07-20' ) AND STR_TO_DATE(td.date_earned, '%Y-%m-%d') <= '2018-07-20' GROUP BY u.user_code ORDER BY last_trade_date DESC LIMIT 0,600";
                break;
            case '56':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.client_pay_method = '1' GROUP BY u.user_code";
                break;
            case '57':
                $query = "SELECT su.name AS first_name, su.email, su.phone FROM signal_users AS su GROUP BY su.phone";
                break;
            case '58':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ui.ifxaccount_id = ud.ifxaccount_id INNER JOIN user AS u ON u.user_code = ui.user_code WHERE ud.status = '8' AND ud.real_dollar_equivalent >= 1000 AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '2017-12-01' AND '$date_today') GROUP BY u.user_code ";
                break;
            case '59':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM independence_promo AS ip INNER JOIN user AS u ON ip.user_code = u.user_code GROUP BY u.user_code ";
                break;
            case '60':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission >= 1000 ";
                break;
            case '61':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 500 AND 999 ";
                break;
            case '62':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 300 AND 499 ";
                break;
            case '63':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 1 AND 299 ";
                break;
            case '64':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM free_training_campaign AS ftc INNER JOIN user AS u ON ftc.email = u.email WHERE u.academy_signup IS NULL GROUP BY u.user_code ORDER BY ftc.created DESC, u.last_name ASC ";
                break;
            case '65':
                $query = "SELECT DISTINCT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND u.user_code = ui.user_code AND ui.ifxaccount_id NOT IN (SELECT ifxaccount_id FROM user_deposit) GROUP BY u.email  ";
                break;
            case '66':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' GROUP BY u.user_code ";
                break;
            case '67':
                $query = "SELECT u.first_name, u.phone, u.email, u.user_code FROM black_friday_2018 AS bf INNER JOIN user AS u ON bf.user_code = u.user_code WHERE bf.tire IS NULL GROUP BY u.user_code ";
                break;
            default:
                $query = false;
                break;
        }

    } else {
        // This is an SMS campaign
        // Reformat queries and group by phone to avoid repetition of phone numbers

        switch ($client_group) {
            case '1':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE campaign_subscribe = '1' GROUP BY user_code";
                break;
            case '2':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE (STR_TO_DATE(created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND campaign_subscribe = '1' GROUP BY user_code";
                break;
            case '3':
                $query = "SELECT first_name, email, phone FROM free_training_campaign GROUP BY phone";
                break;
            case '4':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON uv.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code LEFT JOIN user_credential AS uc ON u.user_code = uc.user_code WHERE (uv.phone_status = '2') AND (uc.doc_status != '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '5':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_credential AS uc INNER JOIN user AS u ON uc.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_bank AS ub ON u.user_code = ub.user_code WHERE (uc.doc_status = '111') AND (ub.status != '2' OR ub.bank_acct_no IS NULL) AND u.campaign_subscribe = '1' GROUP BY u.email ";
                break;
            case '6':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_bank AS ub INNER JOIN user AS u ON ub.user_code = u.user_code WHERE (ub.is_active = '1' AND ub.status = '2') AND u.campaign_subscribe = '1' GROUP BY u.user_code";
                break;
            case '7':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE (u.password IS NULL OR u.password = '') AND u.created < DATE_SUB(NOW(), INTERVAL 2 MONTH) AND u.campaign_subscribe = '1' GROUP BY u.user_code";
                break;
            case '8':
                $query = "SELECT full_name AS first_name, email, phone FROM dinner_2016 WHERE attended = '2' GROUP BY phone ORDER BY created ASC";
                break;
            case '9':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_meta AS um ON u.user_code = um.user_code LEFT JOIN state AS s ON um.state_id = s.state_id WHERE u.campaign_subscribe = '1' AND um.address LIKE '%Lagos%' GROUP BY u.user_code";
                break;
            case '10':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '3' GROUP BY phone";
                break;
            case '11':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '2' GROUP BY phone";
                break;
            case '12':
                $query = "SELECT first_name, email, phone FROM free_training_campaign WHERE training_centre = '1' GROUP BY phone";
                break;
            case '13':
                $query = "SELECT first_name, email, phone FROM forum_participant GROUP BY phone";
                break;
            case '14':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_training = '2' GROUP BY u.user_code";
                break;
            case '15':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_funding = '2' GROUP BY u.user_code";
                break;
            case '16':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_bonus = '2' GROUP BY u.user_code";
                break;
            case '17':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_investment = '2' GROUP BY u.user_code";
                break;
            case '18':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_services = '2' GROUP BY u.user_code";
                break;
            case '19':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM sales_contact_client_interest AS scci INNER JOIN user AS u ON scci.user_code = u.user_code WHERE scci.interest_other = '2' GROUP BY u.user_code";
                break;
            case '20':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE u.campaign_subscribe = '1' AND (ud.status = '8' AND STR_TO_DATE(ud.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY u.user_code";
                break;
            case '21':
                $query = "SELECT full_name AS first_name, email_address AS email, phone_number AS phone FROM pencil_comedy_reg";
                break;
            case '22':
                $query = "SELECT user_code, first_name, email, phone FROM user WHERE email IN ('esan@sludasoft.com','utomudopercy@gmail.com', 'olagold4@yahoo.com', 'ademolaoyebode@gmail.com', 'Joshuagoke08@gmail.com', 'olasomimercy@gmail.com', 'estellynab38@yahoo.com', 'bunmzyfad@yahoo.com', 'estherogunsola463@yahoo.com', 'afujah@yahoo.com', 'ayoola@instafxng.com') GROUP BY phone";
                break;
            case '23':
                $query = "SELECT u.user_code, CONCAT(u.last_name, SPACE(1), u.first_name) AS first_name, u.email, u.phone FROM point_ranking AS pr INNER JOIN user AS u ON pr.user_code = u.user_code ORDER BY pr.year_rank DESC, first_name ASC LIMIT 20";
                break;
            case '24':
                $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM career_user_application AS cua INNER JOIN career_user_biodata AS cub ON cua.cu_user_code = cub.cu_user_code WHERE cua.status = '2' GROUP BY phone";
                break;
            case '25':
                $query = "SELECT u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned BETWEEN '$top_trader_from_date' AND '$top_trader_to_date' GROUP BY phone";
                break;
            case '26':
                $query = "SELECT first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 1 GROUP BY phone";
                break;
            case '27':
                $query = "SELECT CONCAT(last_name, SPACE(1), first_name) AS first_name, email_address AS email, phone_number AS phone FROM prospect_biodata WHERE prospect_source = 2 GROUP BY phone";
                break;
            case '28':
                $query = "SELECT CONCAT(ftc.last_name, SPACE(1), ftc.first_name) AS first_name, ftc.email, ftc.phone FROM free_training_campaign AS ftc LEFT JOIN user AS u on u.email = ftc.email WHERE training_centre = '3' AND ftc.email NOT IN (SELECT email AS c_email FROM user WHERE academy_signup IS NOT NULL) GROUP BY ftc.phone";
                break;
            case '29':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM point_ranking_log AS prl INNER JOIN user AS u ON prl.user_code = u.user_code WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code";
                break;
            case '30':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (td.date_earned BETWEEN '2016-12-01' AND '2017-11-30') AND u.user_code NOT IN (SELECT prl.user_code FROM point_ranking_log AS prl WHERE prl.position IN ('1', '2', '3', '4', '5') AND prl.start_date BETWEEN '2016-12-01' AND '2017-10-01' GROUP BY user_code) GROUP BY u.user_code";
                break;
            case '31':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL GROUP BY u.user_code";
                break;
            case '32':
                $query = "SELECT full_name AS first_name, email, phone FROM dinner_2017 WHERE attended = '1' GROUP BY u.phone";
                break;
            case '33':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.deposit_origin IN ('2', '3') GROUP BY u.user_code";
                break;
            case '34':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_verification AS uv INNER JOIN user AS u ON u.user_code = uv.user_code WHERE uv.user_code = u.user_code AND u.password IS NULL GROUP BY u.user_code";
                break;
            case '35':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_edu_exercise_log AS ueel ON u.user_code = ueel.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.academy_signup IS NOT NULL AND ueel.user_code IS NULL GROUP BY u.user_code";
                break;
            case '36':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id IN (1, 2, 3, 4) AND uefp.user_code IS NULL AND u.user_code NOT IN (SELECT u.user_code FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id = 5 AND uefp.user_code IS NULL) GROUP BY u.user_code ";
                break;
            case '37':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_fee_payment AS uefp ON ueel.user_code = uefp.user_code WHERE ueel.lesson_id > 4 AND uefp.user_code IS NULL GROUP BY u.user_code";
                break;
            case '38':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_edu_exercise_log AS ueel INNER JOIN user AS u ON ueel.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code LEFT JOIN user_edu_deposits AS ued ON ued.user_code = u.user_code WHERE ued.status = '3' GROUP BY u.user_code";
                break;
            case '39':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 3 MONTH) GROUP BY u.email) GROUP BY u.user_code";
                break;
            case '40':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY u.email) GROUP BY u.user_code";
                break;
            case '41':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u WHERE u.user_code IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE date_earned > DATE_SUB(NOW(), INTERVAL 12 MONTH) GROUP BY u.email) GROUP BY u.user_code";
                break;
            case '42':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 3 MONTH)) GROUP BY u.user_code ";
                break;
            case '43':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 6 MONTH)) GROUP BY u.user_code ";
                break;
            case '44':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN (SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE td.date_earned > DATE_SUB(NOW(), INTERVAL 12 MONTH)) GROUP BY u.user_code ";
                break;
            case '45':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_ifxaccount AS ui INNER JOIN user AS u ON ui.user_code = u.user_code WHERE (ui.type = '2') GROUP BY u.phone";
                break;
            case '46':
                $query = "SELECT full_name AS first_name, email FROM article_visitors ";
                break;
            case '47':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u INNER JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code = u.user_code AND ui.type = '1' AND u.campaign_subscribe = '1' GROUP BY u.user_code ";
                break;
            case '48':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code IS NULL AND ftc.email IS NULL AND u.campaign_subscribe = '1' GROUP BY u.user_code ";
                break;
            case '49':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN free_training_campaign AS ftc ON u.email = ftc.email WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND ui.user_code IS NULL AND ftc.email IS NOT NULL AND u.campaign_subscribe = '1' GROUP BY u.user_code ";
                break;
            case '50':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code LEFT JOIN user_deposit AS ud ON ui.ifxaccount_id = ud.ifxaccount_id WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND u.user_code = ui.user_code AND ui.ifxaccount_id = ud.ifxaccount_id AND ud.real_dollar_equivalent < 50.00 AND u.campaign_subscribe = '1' GROUP BY u.user_code ";
                break;
            case '51':
                $query = "SELECT u.first_name, u.email, u.phone, u.status, u.user_code, ud.order_complete_time FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND u.user_code NOT IN (SELECT u.user_code FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '2018-03-22' AND '$date_today') GROUP BY u.user_code ";
                break;
            case '52':
                $query = "SELECT user.user_code, user.first_name, user.email, campaign_leads.phone FROM campaign_leads, user WHERE(STR_TO_DATE(campaign_leads.created, '%Y-%m-%d') BETWEEN '2018-04-01' AND '2018-04-30')AND campaign_leads.email = user.email AND campaign_leads.interest = '2' GROUP BY user.user_code ";
                break;
            case '53':
                $query = "SELECT '$from_date' AS date_from, '$to_date' AS date_to, u.first_name, u.email, u.phone, u.user_code FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.status = '8' AND STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' AND u.user_code NOT IN (SELECT u.user_code FROM user_withdrawal AS uw INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE uw.status = '10' AND STR_TO_DATE(uw.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') GROUP BY u.user_code ";
                break;
            case '54':
                $query = "SELECT '$from_date' AS date_from, '$to_date' AS date_to, u.first_name, u.email, u.phone, u.user_code FROM user_withdrawal AS uw INNER JOIN user_ifxaccount AS ui ON uw.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE uw.status = '10' AND STR_TO_DATE(uw.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' GROUP BY u.user_code ";
                break;
            case '55':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE u.user_code NOT IN ( SELECT u.user_code FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '2018-07-20' AND '2018-07-20' ) AND STR_TO_DATE(td.date_earned, '%Y-%m-%d') <= '2018-07-20' GROUP BY u.phone ORDER BY last_trade_date DESC LIMIT 0,600";
                break;
            case '56':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ud.ifxaccount_id = ui.ifxaccount_id INNER JOIN user AS u ON ui.user_code = u.user_code WHERE ud.client_pay_method = '1' GROUP BY u.user_code";
                break;
            case '57':
                $query = "SELECT su.name AS first_name, su.phone, su.email FROM signal_users AS su GROUP BY su.phone";
                break;
            case '58':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM user_deposit AS ud INNER JOIN user_ifxaccount AS ui ON ui.ifxaccount_id = ud.ifxaccount_id INNER JOIN user AS u ON u.user_code = ui.user_code WHERE ud.status = '8' AND ud.real_dollar_equivalent >= 1000 AND (STR_TO_DATE(ud.order_complete_time, '%Y-%m-%d') BETWEEN '2017-12-01' AND '$date_today') GROUP BY u.user_code ";
                break;
            case '59':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM independence_promo AS ip INNER JOIN user AS u ON ip.user_code = u.user_code GROUP BY u.user_code ";
                break;
            case '60':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission >= 1000 ";
                break;
            case '61':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 500 AND 999 ";
                break;
            case '62':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 300 AND 499 ";
                break;
            case '63':
                $query = "SELECT SUM(td.commission) AS total_commission, u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON ui.ifx_acct_no = td.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE date_earned BETWEEN '2017-12-01' AND '2018-09-30' GROUP BY u.user_code HAVING total_commission BETWEEN 1 AND 300 ";
                break;
            case '64':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM free_training_campaign AS ftc INNER JOIN user AS u ON ftc.email = u.email WHERE u.academy_signup IS NULL GROUP BY u.user_code ORDER BY ftc.created DESC, u.last_name ASC ";
                break;
            case '65':
                $query = "SELECT DISTINCT u.user_code, u.first_name, u.email, u.phone FROM user AS u LEFT JOIN user_ifxaccount AS ui ON u.user_code = ui.user_code WHERE (STR_TO_DATE(u.created, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date') AND u.user_code = ui.user_code AND ui.ifxaccount_id NOT IN (SELECT ifxaccount_id FROM user_deposit) GROUP BY u.email  ";
                break;
            case '66':
                $query = "SELECT u.user_code, u.first_name, u.email, u.phone FROM trading_commission AS td INNER JOIN user_ifxaccount AS ui ON td.ifx_acct_no = ui.ifx_acct_no INNER JOIN user AS u ON ui.user_code = u.user_code INNER JOIN account_officers AS ao ON u.attendant = ao.account_officers_id INNER JOIN admin AS a ON ao.admin_code = a.admin_code WHERE STR_TO_DATE(td.date_earned, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' GROUP BY u.user_code ";
                break;
            case '67':
                $query = "SELECT u.first_name, u.phone, u.email, u.user_code FROM black_friday_2018 AS bf INNER JOIN user AS u ON bf.user_code = u.user_code WHERE bf.tire IS NULL GROUP BY u.user_code ";
                break;
            default:
                $query = false;
                break;
        }

    }

    return $query;
}

function status_fc_type($status)
{
    switch ($status) {
        case '1':
            $message = "Credit / User Deposit";
            break;
        case '2':
            $message = "Credit / User Withdrawal";
            break;
        case '3':
            $message = "Debit";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

function status_trans_type($status)
{
    switch ($status) {
        case '1':
            $message = "Credit";
            break;
        case '2':
            $message = "Debit";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

function publish_status($status)
{
    switch ($status) {
        case '1':
            $message = "Draft";
            break;
        case '2':
            $message = "Publish";
            break;
        case '3':
            $message = "Inactive";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: User Credential
 * Column: status
 */
function user_credential_status($status)
{
    switch ($status) {
        case '1':
            $message = "Awaiting Moderation";
            break;
        case '2':
            $message = "Approved";
            break;
        case '3':
            $message = "Not Approved";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Free Training Campaign
 * Column: training_interest
 */
function free_training_campaign_interest($status)
{
    switch ($status) {
        case '1':
            $message = "Not Yet";
            break;
        case '2':
            $message = "Yes";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Free Training Campaign
 * Column: training_centre
 */
function free_training_campaign_centre($status)
{
    switch ($status) {
        case '1':
            $message = "Diamond Estate";
            break;
        case '2':
            $message = "Ikota Office";
            break;
        case '3':
            $message = "Online";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

/*
 * Table: User Verification
 * Column: phone_status
 */
function phone_code_status($status)
{
    switch ($status) {
        case '1':
            $message = "New";
            break;
        case '2':
            $message = "Used";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}


/*
 * Table: Dinner 2016
 * Column: Interest
 */
function dinner_interest_status($status)
{
    switch ($status) {
        case '1':
            $message = "Not Yet";
            break;
        case '2':
            $message = "Yes";
            break;
        case '3':
            $message = "No";
            break;
        case '4':
            $message = "Maybe";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Dinner 2016
 * Column: invite
 */
function dinner_invite_status($status)
{
    switch ($status) {
        case '1':
            $message = "No";
            break;
        case '2':
            $message = "Yes";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}


/*
 * Table: Dinner 2017
 * Column: invite
 */
function dinner_2017_invite_status($status)
{
    switch ($status) {
        case '0':
            $message = "Not Sent";
            break;
        case '1':
            $message = "Sent";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

function dinner_2017_confirmation_status($status)
{
    switch ($status) {
        case '2':
            $message = "Confirmed";
            break;
        case '3':
            $message = "Declined";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Dinner 2017
 * Column: Ticket Type
 */
function dinner_ticket_type($status)
{
    switch ($status) {
        case '0':
            $message = "Single Client";
            break;
        case '1':
            $message = "Plus One Client (Double)";
            break;
        case '2':
            $message = "VIP Single";
            break;
        case '3':
            $message = "VIP Plus One (Double)";
            break;
        case '4':
            $message = "Hired Help";
            break;
        case '5':
            $message = "Staff";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

/*
 * Table: Dinner 2017
 * Column: Confirmation Status
 */
function dinner_confirmation_status($status)
{
    switch ($status) {
        case '0':
            $message = "Pending";
            break;
        case '1':
            $message = "Maybe";
            break;
        case '2':
            $message = "Confirmed";
            break;
        case '3':
            $message = "Declined";
            break;
        case '4':
            $message = "Attendance Confirmed";
            break;
        default:
            $message = "Unknown";
            break;
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
        case '1':
            $message = "HFP Eastline Office";
            break;
        case '2':
            $message = "Diamond Estate Office";
            break;
        default:
            $message = "Status Unknown";
            break;
    }
    return $message;
}

function forum_reg_venue($status)
{
    switch ($status) {
        case '1':
            $message = "Diamond Estate";
            break;
        case '2':
            $message = "Eastline Complex";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

//TODO: regularize office location numbers
function office_location_walkin_client($status)
{
    switch ($status) {
        case '1':
            $message = "Diamond Estate";
            break;
        case '2':
            $message = "Eastline Complex";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

function career_application_status($status)
{
    switch ($status) {
        case '1':
            $message = "Not Submitted";
            break;
        case '2':
            $message = "Submitted";
            break;
        case '3':
            $message = "Review";
            break;
        case '4':
            $message = "No Review";
            break;
        case '5':
            $message = "Interviewed";
            break;
        case '6':
            $message = "Employed";
            break;
        case '7':
            $message = "Not Employed";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

function biodata_sex_status($status)
{
    switch ($status) {
        case 'M':
            $message = "Male";
            break;
        case 'F':
            $message = "Female";
            break;
        default:
            $message = "Unknown";
            break;
    }

    return $message;
}

function biodata_marriage_status($status)
{
    switch ($status) {
        case 'S':
            $message = "Single";
            break;
        case 'M':
            $message = "Married";
            break;
        default:
            $message = "Unknown";
            break;
    }

    return $message;
}

function biodata_competency_status($status)
{
    switch ($status) {
        case '1':
            $message = "Beginner";
            break;
        case '2':
            $message = "Advanced";
            break;
        case '3':
            $message = "Professional";
            break;
        case '4':
            $message = "Master";
            break;
        case '5':
            $message = "Certified";
            break;
        default:
            $message = "Unknown";
            break;
    }

    return $message;
}

function biodata_achievement_status($status)
{
    switch ($status) {
        case '1':
            $message = "Certification";
            break;
        case '2':
            $message = "Course";
            break;
        case '3':
            $message = "Honour/Award";
            break;
        case '4':
            $message = "Project";
            break;
        default:
            $message = "Unknown";
            break;
    }

    return $message;
}

function status_point_claimed($status)
{
    switch ($status) {
        case '1':
            $message = "New Request";
            break;
        case '2':
            $message = "Completed";
            break;
        case '3':
            $message = "Failed";
            break;
        default:
            $message = "Unknown";
            break;
    }

    return $message;
}


/*
 * Table: Dinner 2017
 * Column: invite
 */
function project_management_status($status)
{
    switch ($status) {
        case '0':
            $message = "Suspended";
            break;
        case '1':
            $message = "In Progress";
            break;
        case '2':
            $message = "Completed";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}

/*
 * Table:
 */
function financial_trans_type($status)
{
    switch ($status) {
        case '1':
            $message = "Deposit";
            break;
        case '2':
            $message = "Withdrawal";
            break;
        default:
            $message = "Unknown";
            break;
    }
    return $message;
}