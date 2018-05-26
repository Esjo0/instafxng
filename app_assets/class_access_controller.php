<?php
//Define an array of the majour pages and its code
define("PAGE_CODE", json_encode(array(
    1 => "admin_add.php||account_officers.php",
    2 => "admin_view.php||",
    3 => "bulletin_add.php||",
    4 => "bulletin_view.php||",
    5 => "bulletin_centre.php||",
    6 => "snappy_help_add.php||",
    7 => "snappy_help_manage.php||",
    8 => "client_add.php||",
    9 => "client_flag_account.php||",
    10 => "client_flag_view.php||",
    11 => "client_search.php||",
    300 => "deposit_confirm_ifx_account.php||",
    12 => "client_commission_17.php||client_view.php||client_top_traders.php||",
    201 => "client_delete.php||",
    13 => "client_moderate_account.php||",
    14 => "client_update_account.php||",
    15 => "client_doc_verify.php||",
    202 => "client_failed_sms_code.php||",
    16 => "client_bank_verify.php||",
    17 => "client_level_one.php||",
    18 => "client_level_two.php||",
    19 => "client_level_three.php||",
    20 => "client_unverified.php||",
    21 => "client_ilpr.php||",
    22 => "client_non_ilpr.php||",
    244 => "client_active.php||",
    245 => "client_inactive.php||",
    23 => "last_month_funding.php||",
    24 => "last_month_withdrawal.php||",
    25 => "last_month_new_client.php||",
    26 => "sales_client_interest.php||insight_prospects.php||insight_training_campaign.php||",
    27 => "daily_funding.php||",
    28 => "deposit_add.php||",
    29 => "deposit_search.php||",
    30 => "deposit_pending.php||",
    31 => "deposit_notified.php||",
    32 => "deposit_confirmed.php||",
    234 => "deposit_confirmed_view_only.php||",
    33 => "deposit_completed.php||",
    34 => "deposit_declined.php||",
    35 => "deposit_failed.php||",
    36 => "deposit_all.php||",
    37 => "transaction_calculator.php||",
    38 => "deposit_reversal.php||",
    40 => "withdrawal_search.php||",
    41 => "withdrawal_initiated.php||",
    42 => "withdrawal_confirmed.php||",
    235 => "withdrawal_confirmed_view_only.php||",
    43 => "withdrawal_ifx_debited.php||",
    44 => "withdrawal_completed.php||",
    45 => "withdrawal_declined.php||",
    46 => "withdrawal_all.php||",
    47 => "transaction_calculator.php||",
    48 => "new_reg.php||",
    49 => "all_reg.php||",
    50 => "pencil_unbroken.php||lekki_office_warming_all.php||",
    211 => "q2_promo_results.php||independence_quiz_results.php||",
    228 => "dinner_2017_new_reg.php||",
    229 => "dinner_2017_all_reg.php||",
    230 => "dinner_2017_signin.php||",
    209 => "project_management_all_archived_projects.php||project_management_all_projects.php||",
    203 => "project_management_archived_projects.php||project_management_projects.php||",
    210 => "project_management_all_messages.php||",
    207 => "project_management_reminder_add.php||",
    208 => "project_management_reminder_manage.php||",
    51 => "partner_view.php||",
    52 => "partner_update.php||",
    53 => "paypartners.php||",
    54 => "partner_set_commission.php||",
    55 => "partner_pending_payout.php||",
    56 => "partner_payout_history.php||",
    57 => "upload_commission.php||val_contest.php||",
    58 => "commission_upload_log.php||",
    59 => "commission_view.php||",
    60 => "rewards_report.php||",
    61 => "loyalty_point_claimed.php||",
    233 => "client_expired_points.php||",
    63 => "edu_course.php||",
    64 => "edu_support_ticket.php||",
    65 => "edu_student.php||",
    66 => "edu_student_category_1.php||edu_student_category_0.php||",
    236 => "edu_student_category_2.php||",
    237 => "edu_student_category_3.php||",
    238 => "edu_student_category_4.php||",
    73 => "edu_client_training_funded.php||edu_free_training.php||edu_free_training_reg.php||",
    74 => "edu_forum_reg.php||",
    247 => "traders_forum_schedule.php||",
    67 => "edu_deposit_initiated.php||",
    68 => "edu_deposit_notified.php||",
    69 => "edu_deposit_completed.php||",
    70 => "edu_deposit_declined.php||",
    71 => "edu_deposit_failed.php||",
    75 => "recent_comments.php||article_add.php||",
    76 => "list_of_visitors.php||article_comments_history.php||article_manage.php||",
    79 => "system_message.php||",
    239 => "system_activity_logs.php||",
    243 => "system_ad_section.php||",
    80 => "campaign_solo_group_new.php||",
    81 => "campaign_solo_group_all.php||",
    82 => "campaign_solo_view.php||",
    83 => "campaign_solo_all.php||",
    85 => "campaign_new_category.php||",
    86 => "campaign_all_category.php||",
    87 => "campaign_sms.php||",
    232 => "campaign_sms_single.php||",
    88 => "campaign_email.php||",
    89 => "campaign_email_view.php||",
    90 => "campaign_sms_view.php||",
    91 => "campaign_sales.php||",
    255 => "campaign_leads.php||",
    256 => "campaign_analytics.php||",
    92 => "career_new_job.php||",
    93 => "career_all_job.php||",
    94 => "career_all_applications.php||",
    95 => "career_app_submit.php||",
    96 => "career_app_review.php||",
    97 => "career_app_interview.php||",
    98 => "career_app_reject.php||career_app_employ.php||",
    100 => "settings_rates_log.php||settings_rates_log_view.php||",
    101 => "settings_rates.php||",
    102 => "settings_schedules.php||",
    103 => "active_client.php||",
    104 => "report_service_charge.php||",
    105 => "report_vat_charge.php||",
    106 => "report_saldo.php||",
    107 => "report_commission.php||",
    77 => "signal_daily.php||",
    78 => "signal_review.php||",
    110 => "prospect_source.php||",
    111 => "prospect_add.php||",
    112 => "prospect_manage_funded.php||prospect_manage.php||",
    246 => "prospect_ilpr_manage.php||",
    248 => "prospect_fb_leads.php||",
    214 => "customer_care_log_add.php||",
    215 => "customer_care_admin_log_manage.php||",
    216 => "customer_care_all_log_manage.php||",
    249 => "operations_log.php||",
    250 => "operations_log_archive.php||",
    217 => "support_email_compose.php||",
    218 => "support_email_inbox.php||",
    219 => "support_email_sent_box.php||",
    220 => "support_email_assigned.php||",
    221 => "support_email_drafts.php||",
    222 => "accounting_system_requisition_orders.php||accounting_system_requisition_form.php||",
    223 => "accounting_system_confirmation_requests.php||",
    224 => "accounting_system_cashiers_desk.php||",
    225 => "accounting_system_req_reports.php||",
    226 => "accounting_system_settings.php||",
    231 => "hr_attendance_system_logs.php||",
    240 => "facility_manager.php||",
    241 => "facility_admin.php||",
    242 => "facility_user.php||",
    )));
class Access_Controller
{
    public function get_all_pages()
    {
        return json_decode(PAGE_CODE);
    }
    public function validate_access()
    {
        global $admin_object;
        $currentFile = $_SERVER["PHP_SELF"];
        $parts = explode('/', $currentFile);
        $this_page = $parts[count($parts) - 1];
        //$this_page = basename($_SERVER['PHP_SELF']);
        $all_pages = $this->get_all_pages();
        foreach ($all_pages as $key => $value)
        {
            $_pages = explode('||', $value);
            if (in_array($this_page, $_pages))
            {
                $user_privilege = $_SESSION['user_privilege'];
                if(!in_array($key, $user_privilege))
                {
                    //redirect_to('https://localhost/instafxngwebsite_master/public_html/admin/access_denied.php');
                    redirect_to('https://instafxng.com/admin/access_denied.php');
                    exit();
                }
            }
        }
    }

}

$obj_access_control = new Access_Controller();