

ALTER TABLE `accounting_system_budgets`
  ADD UNIQUE KEY `budget_id` (`budget_id`);

ALTER TABLE `accounting_system_office_locations`
  ADD UNIQUE KEY `location_id` (`location_id`);

ALTER TABLE `accounting_system_refunds`
  ADD PRIMARY KEY (`refund_id`);

ALTER TABLE `accounting_system_req_order`
  ADD PRIMARY KEY (`req_order_id`),
  ADD UNIQUE KEY `order_id` (`req_order_id`),
  ADD UNIQUE KEY `req_order_code` (`req_order_code`);

ALTER TABLE `account_officers`
  ADD PRIMARY KEY (`account_officers_id`);

ALTER TABLE `active_client`
  ADD PRIMARY KEY (`active_client_id`),
  ADD UNIQUE KEY `date_UNIQUE` (`date`),
  ADD UNIQUE KEY `active_client_id_UNIQUE` (`active_client_id`);

ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_code_UNIQUE` (`admin_code`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `salt_UNIQUE` (`pass_salt`),
  ADD UNIQUE KEY `admin_id_UNIQUE` (`admin_id`);

ALTER TABLE `admin_bulletin`
  ADD PRIMARY KEY (`admin_bulletin_id`);

ALTER TABLE `admin_bulletin_comments`
  ADD UNIQUE KEY `comment_id` (`comment_id`);

ALTER TABLE `admin_privilege`
  ADD PRIMARY KEY (`admin_privilege_id`,`admin_code`),
  ADD UNIQUE KEY `admin_privilege_id_UNIQUE` (`admin_privilege_id`),
  ADD UNIQUE KEY `admin_code_UNIQUE` (`admin_code`);

ALTER TABLE `article`
  ADD PRIMARY KEY (`article_id`),
  ADD UNIQUE KEY `article_id_UNIQUE` (`article_id`),
  ADD KEY `fk_article_admin1_idx` (`admin_code`);

ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`comment_id`);

ALTER TABLE `article_visitors`
  ADD PRIMARY KEY (`visitor_id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`),
  ADD UNIQUE KEY `banks_id_UNIQUE` (`bank_id`),
  ADD UNIQUE KEY `bank_name_UNIQUE` (`bank_name`);

ALTER TABLE `campaign_category`
  ADD PRIMARY KEY (`campaign_category_id`),
  ADD UNIQUE KEY `campaign_category_id_UNIQUE` (`campaign_category_id`),
  ADD UNIQUE KEY `client_group_UNIQUE` (`client_group`);

ALTER TABLE `campaign_email`
  ADD PRIMARY KEY (`campaign_email_id`),
  ADD UNIQUE KEY `campaign_email_id_UNIQUE` (`campaign_email_id`),
  ADD KEY `fk_campaign_email_campaign_category1_idx` (`campaign_category_id`);

ALTER TABLE `campaign_email_solo`
  ADD PRIMARY KEY (`campaign_email_solo_id`),
  ADD UNIQUE KEY `campaign_email_solo_id_UNIQUE` (`campaign_email_solo_id`),
  ADD KEY `fk_campaign_email_solo_campaign_email_solo_group1_idx` (`solo_group`);

ALTER TABLE `campaign_email_solo_group`
  ADD PRIMARY KEY (`campaign_email_solo_group_id`);

ALTER TABLE `campaign_email_track`
  ADD PRIMARY KEY (`campaign_track_id`),
  ADD UNIQUE KEY `campaign_id_UNIQUE` (`campaign_id`);

ALTER TABLE `campaign_sms`
  ADD PRIMARY KEY (`campaign_sms_id`),
  ADD UNIQUE KEY `campaign_sms_id_UNIQUE` (`campaign_sms_id`),
  ADD KEY `fk_campaign_sms_campaign_category1_idx` (`campaign_category_id`);

ALTER TABLE `campaign_sms_track`
  ADD PRIMARY KEY (`campaign_track_id`),
  ADD UNIQUE KEY `campaign_id_UNIQUE` (`campaign_id`);

ALTER TABLE `career_application_comment`
  ADD PRIMARY KEY (`career_application_comment_id`),
  ADD KEY `fk_career_application_comment_career_user_application1_idx` (`application_id`),
  ADD KEY `fk_career_application_comment_admin1_idx` (`admin_code`);

ALTER TABLE `career_jobs`
  ADD PRIMARY KEY (`career_jobs_id`),
  ADD UNIQUE KEY `career_jobs_id_UNIQUE` (`career_jobs_id`),
  ADD UNIQUE KEY `job_code_UNIQUE` (`job_code`);

ALTER TABLE `career_user_achievement`
  ADD PRIMARY KEY (`career_user_achievement_id`),
  ADD KEY `fk_career_user_achievement_career_user_biodata1_idx` (`cu_user_code`);

ALTER TABLE `career_user_application`
  ADD PRIMARY KEY (`career_user_application_id`),
  ADD UNIQUE KEY `cu_user_code_UNIQUE` (`cu_user_code`),
  ADD KEY `fk_career_user_application_career_user_biodata1_idx` (`cu_user_code`),
  ADD KEY `fk_career_user_application_career_jobs1_idx` (`job_code`);

ALTER TABLE `career_user_biodata`
  ADD PRIMARY KEY (`career_user_biodata_id`),
  ADD UNIQUE KEY `email_address_UNIQUE` (`email_address`),
  ADD UNIQUE KEY `phone_number_UNIQUE` (`phone_number`),
  ADD UNIQUE KEY `cu_user_code_UNIQUE` (`cu_user_code`),
  ADD KEY `fk_career_user_biodata_state1_idx` (`state_of_origin`),
  ADD KEY `fk_career_user_biodata_state2_idx` (`state`);

ALTER TABLE `career_user_education`
  ADD PRIMARY KEY (`career_user_education_id`),
  ADD KEY `fk_career_user_education_career_user_biodata1_idx` (`cu_user_code`);

ALTER TABLE `career_user_skill`
  ADD PRIMARY KEY (`career_user_skill_id`),
  ADD KEY `fk_career_user_skill_career_user_biodata1_idx` (`cu_user_code`);

ALTER TABLE `career_user_work_experience`
  ADD PRIMARY KEY (`career_user_work_experience_id`),
  ADD KEY `fk_career_user_work_experience_state1_idx` (`location`),
  ADD KEY `fk_career_user_work_experience_career_user_biodata1_idx` (`cu_user_code`);

ALTER TABLE `country`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `state_id_UNIQUE` (`country_id`);

ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`),
  ADD UNIQUE KEY `currency_id_UNIQUE` (`currency_id`),
  ADD UNIQUE KEY `symbol_UNIQUE` (`symbol`);

ALTER TABLE `customer_care_log`
  ADD PRIMARY KEY (`log_id`);

ALTER TABLE `deposit_comment`
  ADD PRIMARY KEY (`deposit_comment_id`),
  ADD UNIQUE KEY `deposit_comment_id_UNIQUE` (`deposit_comment_id`),
  ADD KEY `fk_deposit_comment_user_deposit1_idx` (`trans_id`),
  ADD KEY `fk_deposit_comment_admin1_idx` (`admin_code`);

ALTER TABLE `deposit_monitoring`
  ADD PRIMARY KEY (`deposit_monitoring_id`),
  ADD UNIQUE KEY `transaction_monitoring_id_UNIQUE` (`deposit_monitoring_id`),
  ADD KEY `fk_transaction_monitoring_admin1_idx` (`admin_code`),
  ADD KEY `fk_deposit_user_deposit1` (`trans_id`);

ALTER TABLE `dinner2016_comment`
  ADD PRIMARY KEY (`dinner2016_comment_id`),
  ADD KEY `fk_dinner2016_comment_dinner_20161_idx` (`dinner_id`);

ALTER TABLE `dinner2017_comment`
  ADD PRIMARY KEY (`comment_id`);

ALTER TABLE `dinner_2016`
  ADD PRIMARY KEY (`id_dinner_2016`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_dinner_2016`);

ALTER TABLE `dinner_2017`
  ADD PRIMARY KEY (`reservation_id`),
  ADD UNIQUE KEY `reservation_code` (`reservation_code`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `edu_course`
  ADD PRIMARY KEY (`edu_course_id`),
  ADD UNIQUE KEY `code_UNIQUE` (`course_code`),
  ADD UNIQUE KEY `edu_course_id_UNIQUE` (`edu_course_id`);

ALTER TABLE `edu_lesson`
  ADD PRIMARY KEY (`edu_lesson_id`),
  ADD UNIQUE KEY `edu_lesson_id_UNIQUE` (`edu_lesson_id`),
  ADD KEY `fk_edu_lesson_edu_course1_idx` (`course_id`);

ALTER TABLE `edu_lesson_exercise`
  ADD PRIMARY KEY (`edu_lesson_exercise_id`),
  ADD UNIQUE KEY `edu_lesson_exercise_id_UNIQUE` (`edu_lesson_exercise_id`),
  ADD KEY `fk_edu_lesson_exercise_edu_lesson1_idx` (`lesson_id`);

ALTER TABLE `event_reg`
  ADD PRIMARY KEY (`event_reg_id`),
  ADD UNIQUE KEY `event_reg_id_UNIQUE` (`event_reg_id`);

ALTER TABLE `exchange_rate_log`
  ADD PRIMARY KEY (`exchange_rate_log_id`),
  ADD UNIQUE KEY `exchange_rate_log_id_UNIQUE` (`exchange_rate_log_id`);

ALTER TABLE `facility_category`
  ADD PRIMARY KEY (`facility_category_id`);

ALTER TABLE `facility_inventory`
  ADD PRIMARY KEY (`facility_inventory_id`),
  ADD KEY `fk_facility_inventory_facility_station1_idx` (`station_id`),
  ADD KEY `fk_facility_inventory_facility_category1_idx` (`category_id`),
  ADD KEY `fk_facility_inventory_admin1_idx` (`admin_code`);

ALTER TABLE `facility_request`
  ADD PRIMARY KEY (`facility_request_id`),
  ADD KEY `fk_facility_request_facility_station1_idx` (`station_id`),
  ADD KEY `fk_facility_request_admin1_idx` (`author`),
  ADD KEY `fk_facility_request_facility_category1_idx` (`category_id`);

ALTER TABLE `facility_request_comment`
  ADD PRIMARY KEY (`facility_request_comment_id`),
  ADD KEY `fk_facility_request_comment_admin1_idx` (`admin_code`),
  ADD KEY `fk_facility_request_comment_facility_request1_idx` (`request_id`);

ALTER TABLE `facility_station`
  ADD PRIMARY KEY (`facility_station_id`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

ALTER TABLE `forum_participant`
  ADD PRIMARY KEY (`forum_participant_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

ALTER TABLE `free_training_campaign`
  ADD PRIMARY KEY (`free_training_campaign_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `phone_UNIQUE` (`phone`);

ALTER TABLE `free_training_campaign_comment`
  ADD PRIMARY KEY (`free_training_campaign_comment_id`),
  ADD UNIQUE KEY `free_training_campaign_comment_id_UNIQUE` (`free_training_campaign_comment_id`),
  ADD KEY `fk_free_training_campaign_comment_free_training_campaign1_idx` (`training_campaign_id`);

ALTER TABLE `hr_attendance_log`
  ADD PRIMARY KEY (`log_id`);

ALTER TABLE `lekki_office_training`
  ADD PRIMARY KEY (`id_new_office_warming`),
  ADD UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_new_office_warming`);

ALTER TABLE `log_of_dates`
  ADD PRIMARY KEY (`log_of_dates_id`),
  ADD UNIQUE KEY `log_of_dates_id_UNIQUE` (`log_of_dates_id`);

ALTER TABLE `miss_tourism_lagos`
  ADD PRIMARY KEY (`miss_tourism_lagos_id`),
  ADD UNIQUE KEY `contest_id_UNIQUE` (`contest_id`),
  ADD UNIQUE KEY `miss_tourism_lagos_id_UNIQUE` (`miss_tourism_lagos_id`);

ALTER TABLE `new_office_warming`
  ADD PRIMARY KEY (`id_new_office_warming`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `id_dinner_2016_UNIQUE` (`id_new_office_warming`);

ALTER TABLE `partner`
  ADD PRIMARY KEY (`partner_id`),
  ADD UNIQUE KEY `partner_code_UNIQUE` (`partner_code`),
  ADD UNIQUE KEY `partners_id_UNIQUE` (`partner_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD KEY `fk_partner_user_ifxaccount1_idx` (`settlement_ifxaccount_id`);

ALTER TABLE `partner_balance`
  ADD PRIMARY KEY (`partner_balance_id`);

ALTER TABLE `partner_financial_activity_commission`
  ADD PRIMARY KEY (`partner_financial_activity_commission_id`),
  ADD UNIQUE KEY `partner_financial_activity_commission_id_UNIQUE` (`partner_financial_activity_commission_id`),
  ADD KEY `fk_partner_financial_activity_commission_partner1_idx` (`partner_code`);

ALTER TABLE `partner_payment`
  ADD PRIMARY KEY (`partner_pay_id`);

ALTER TABLE `partner_trading_commission`
  ADD PRIMARY KEY (`partner_trading_commission_id`),
  ADD UNIQUE KEY `partner_payment_id_UNIQUE` (`partner_trading_commission_id`),
  ADD UNIQUE KEY `reference_trans_id_UNIQUE` (`reference_trans_id`),
  ADD KEY `fk_partner_payment_trading_commission_partner1_idx` (`partner_code`);

ALTER TABLE `partner_withdrawal`
  ADD PRIMARY KEY (`partner_payment_id`),
  ADD UNIQUE KEY `partner_payment_id_UNIQUE` (`partner_payment_id`),
  ADD UNIQUE KEY `transaction_id_UNIQUE` (`transaction_id`),
  ADD KEY `fk_partner_payment_admin1_idx` (`admin_code`),
  ADD KEY `fk_partner_payment_user_ifxaccount1_idx` (`account_id`),
  ADD KEY `fk_partner_payment_partner1_idx` (`partner_code`);

ALTER TABLE `pencil_comedy_reg`
  ADD PRIMARY KEY (`pencil_comedy_reg_id`),
  ADD UNIQUE KEY `email_address_UNIQUE` (`email_address`),
  ADD KEY `fk_pencil_comedy_reg_state1_idx` (`state_of_residence`);

ALTER TABLE `point_based_claimed`
  ADD PRIMARY KEY (`point_based_claimed_id`),
  ADD UNIQUE KEY `point_based_claimed_id_UNIQUE` (`point_based_claimed_id`),
  ADD KEY `fk_point_based_claimed_user1_idx` (`user_code`);

ALTER TABLE `point_based_reward`
  ADD PRIMARY KEY (`point_based_reward_id`),
  ADD KEY `fk_points_based_reward_user1_idx` (`user_code`);

ALTER TABLE `point_ranking`
  ADD PRIMARY KEY (`point_ranking_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`);

ALTER TABLE `point_ranking_log`
  ADD PRIMARY KEY (`point_ranking_log_id`),
  ADD KEY `fk_point_ranking_log_user1_idx` (`user_code`);

ALTER TABLE `point_season`
  ADD PRIMARY KEY (`point_season_id`),
  ADD UNIQUE KEY `point_season_id_UNIQUE` (`point_season_id`);

ALTER TABLE `project_management_messages`
  ADD PRIMARY KEY (`message_id`);

ALTER TABLE `project_management_projects`
  ADD PRIMARY KEY (`project_id`),
  ADD UNIQUE KEY `project_code` (`project_code`);

ALTER TABLE `project_management_project_comments`
  ADD PRIMARY KEY (`comment_id`);

ALTER TABLE `project_management_reminders`
  ADD PRIMARY KEY (`reminder_id`);

ALTER TABLE `project_management_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD UNIQUE KEY `report_code` (`report_code`);

ALTER TABLE `project_management_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD UNIQUE KEY `task_code` (`task_code`);

ALTER TABLE `prospect_biodata`
  ADD PRIMARY KEY (`prospect_biodata_id`),
  ADD UNIQUE KEY `email_address_UNIQUE` (`email_address`),
  ADD KEY `fk_prospect_biodata_admin1_idx` (`admin_code`),
  ADD KEY `fk_prospect_biodata_prospect_source1_idx` (`prospect_source`);

ALTER TABLE `prospect_sales_contact`
  ADD PRIMARY KEY (`prospect_sales_contact_id`),
  ADD KEY `fk_prospect_sales_contact_admin1_idx` (`admin_code`),
  ADD KEY `fk_prospect_sales_contact_prospect_biodata1_idx` (`prospect_id`);

ALTER TABLE `prospect_source`
  ADD PRIMARY KEY (`prospect_source_id`);

ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`notification_id`);

ALTER TABLE `quiz_participant`
  ADD PRIMARY KEY (`participant_id`),
  ADD UNIQUE KEY `participant_acc_no` (`participant_email`);

ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`);

ALTER TABLE `reminders`
  ADD PRIMARY KEY (`reminder_id`);

ALTER TABLE `sales_contact_client_interest`
  ADD PRIMARY KEY (`sales_contact_client_interest_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`);

ALTER TABLE `sales_contact_comment`
  ADD PRIMARY KEY (`sales_contact_comment_id`);

ALTER TABLE `sales_contact_email`
  ADD PRIMARY KEY (`sales_contact_email_id`);

ALTER TABLE `sales_contact_sms`
  ADD PRIMARY KEY (`sales_contact_sms_id`);

ALTER TABLE `signal_daily`
  ADD PRIMARY KEY (`signal_daily_id`),
  ADD KEY `fk_signal_daily_signal_symbol1_idx` (`symbol_id`);

ALTER TABLE `signal_symbol`
  ADD PRIMARY KEY (`signal_symbol_id`),
  ADD UNIQUE KEY `symbol_UNIQUE` (`symbol`);

ALTER TABLE `snappy_help`
  ADD PRIMARY KEY (`snappy_help_id`),
  ADD UNIQUE KEY `snappy_help_id_UNIQUE` (`snappy_help_id`),
  ADD KEY `fk_snappy_help_admin1_idx` (`admin_code`);

ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`),
  ADD UNIQUE KEY `state_id_UNIQUE` (`state_id`),
  ADD KEY `fk_state_country1_idx` (`country_id`);

ALTER TABLE `support_email_inbox`
  ADD PRIMARY KEY (`email_id`);

ALTER TABLE `support_email_sent_box`
  ADD PRIMARY KEY (`email_id`);

ALTER TABLE `system_message`
  ADD PRIMARY KEY (`system_message_id`),
  ADD UNIQUE KEY `constant_UNIQUE` (`constant`);

ALTER TABLE `system_setting`
  ADD PRIMARY KEY (`system_setting_id`),
  ADD UNIQUE KEY `constant_UNIQUE` (`constant`),
  ADD UNIQUE KEY `system_setting_id_UNIQUE` (`system_setting_id`);

ALTER TABLE `trading_commission`
  ADD PRIMARY KEY (`trading_commission_id`),
  ADD UNIQUE KEY `trading_commission_id_UNIQUE` (`trading_commission_id`),
  ADD KEY `fk_user_trading_commission_currency1_idx` (`currency_id`);

ALTER TABLE `train_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_train_questions_edu_course1_idx` (`course_id`),
  ADD KEY `fk_train_questions_edu_lesson1_idx` (`lesson_id`);

ALTER TABLE `train_question_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `fk_train_question_options_train_questions1_idx` (`question_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);

ALTER TABLE `user_account_flag`
  ADD PRIMARY KEY (`user_account_flag_id`),
  ADD UNIQUE KEY `user_account_flag_id_UNIQUE` (`user_account_flag_id`),
  ADD KEY `fk_user_account_flag_user_ifxaccount1_idx` (`ifxaccount_id`),
  ADD KEY `fk_user_account_flag_admin1_idx` (`admin_code`);

ALTER TABLE `user_bank`
  ADD PRIMARY KEY (`user_bank_id`),
  ADD UNIQUE KEY `bank_id_UNIQUE` (`user_bank_id`),
  ADD KEY `fk_bank_user1_idx` (`user_code`),
  ADD KEY `fk_user_bank_banks1_idx` (`bank_id`);

ALTER TABLE `user_bonus_deposit`
  ADD PRIMARY KEY (`user_bonus_deposit_id`),
  ADD UNIQUE KEY `user_deposit_deposit_id_UNIQUE` (`user_deposit_id`),
  ADD KEY `fk_user_bonus_deposit_user_deposit1_idx` (`user_deposit_id`),
  ADD KEY `fk_user_bonus_deposit_user_ifxaccount1` (`ifxaccount_id`);

ALTER TABLE `user_credential`
  ADD PRIMARY KEY (`user_credential_id`),
  ADD UNIQUE KEY `credential_id_UNIQUE` (`user_credential_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD KEY `fk_credential_user1_idx` (`user_code`);

ALTER TABLE `user_deposit`
  ADD PRIMARY KEY (`user_deposit_id`),
  ADD UNIQUE KEY `trans_id_UNIQUE` (`trans_id`),
  ADD UNIQUE KEY `deposit_id_UNIQUE` (`user_deposit_id`),
  ADD UNIQUE KEY `points_claimed_id_UNIQUE` (`points_claimed_id`),
  ADD KEY `fk_deposit_ifxaccount1_idx` (`ifxaccount_id`);

ALTER TABLE `user_deposit_meta`
  ADD PRIMARY KEY (`user_deposit_meta_id`),
  ADD UNIQUE KEY `iduser_deposit_meta_UNIQUE` (`user_deposit_meta_id`),
  ADD UNIQUE KEY `user_deposit_id_UNIQUE` (`user_deposit_id`),
  ADD KEY `fk_user_deposit_meta_user_deposit1_idx` (`user_deposit_id`);

ALTER TABLE `user_edu_deposits`
  ADD PRIMARY KEY (`user_edu_deposits_id`),
  ADD UNIQUE KEY `user_edu_deposits_id_UNIQUE` (`user_edu_deposits_id`),
  ADD UNIQUE KEY `trans_id_UNIQUE` (`trans_id`);

ALTER TABLE `user_edu_deposits_comment`
  ADD PRIMARY KEY (`user_edu_deposits_comment_id`),
  ADD UNIQUE KEY `user_edu_deposits_comment_id_UNIQUE` (`user_edu_deposits_comment_id`),
  ADD KEY `fk_user_edu_deposits_comment_user_edu_deposits1_idx` (`trans_id`);

ALTER TABLE `user_edu_exercise_log`
  ADD PRIMARY KEY (`user_edu_exercise_log_id`),
  ADD UNIQUE KEY `user_edu_exercise_log_id_UNIQUE` (`user_edu_exercise_log_id`);

ALTER TABLE `user_edu_fee_payment`
  ADD PRIMARY KEY (`user_edu_fee_payment_id`),
  ADD UNIQUE KEY `user_edu_fee_payment_id_UNIQUE` (`user_edu_fee_payment_id`),
  ADD UNIQUE KEY `reference_UNIQUE` (`reference`);

ALTER TABLE `user_edu_student_log`
  ADD PRIMARY KEY (`user_edu_student_log_id`),
  ADD UNIQUE KEY `user_edu_student_log_id_UNIQUE` (`user_edu_student_log_id`);

ALTER TABLE `user_edu_support_answer`
  ADD PRIMARY KEY (`user_edu_support_answer_id`),
  ADD KEY `fk_user_edu_support_answer_user_edu_support_request1_idx` (`request_id`);

ALTER TABLE `user_edu_support_request`
  ADD PRIMARY KEY (`user_edu_support_request_id`),
  ADD UNIQUE KEY `support_request_code_UNIQUE` (`support_request_code`);

ALTER TABLE `user_ifxaccount`
  ADD PRIMARY KEY (`ifxaccount_id`),
  ADD UNIQUE KEY `ifxaccount_id_UNIQUE` (`ifxaccount_id`),
  ADD UNIQUE KEY `ifx_acct_no_UNIQUE` (`ifx_acct_no`),
  ADD KEY `fk_ifxaccount_user1_idx` (`user_code`);

ALTER TABLE `user_ilpr_enrolment`
  ADD PRIMARY KEY (`user_ilpr_enrolment_id`),
  ADD KEY `fk_user_ilpr_enrolment_user_ifxaccount1_idx` (`ifxaccount_id`);

ALTER TABLE `user_loyalty_log`
  ADD PRIMARY KEY (`user_loyalty_log_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`) USING BTREE,
  ADD KEY `fk_user_loyalty_log_user1_idx` (`user_code`);

ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`user_meta_id`),
  ADD UNIQUE KEY `meta_id_UNIQUE` (`user_meta_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD KEY `fk_user_meta_state1_idx` (`state_id`);

ALTER TABLE `user_subscription`
  ADD PRIMARY KEY (`user_subscription_id`),
  ADD UNIQUE KEY `user_subscription_id_UNIQUE` (`user_subscription_id`),
  ADD KEY `fk_subscription_user1_idx` (`user_code`),
  ADD KEY `fk_user_subscription_campaign_category1_idx` (`campaign_category_id`);

ALTER TABLE `user_val_2017`
  ADD PRIMARY KEY (`user_val_2017_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD UNIQUE KEY `val_pics_UNIQUE` (`val_pics`);

ALTER TABLE `user_verification`
  ADD PRIMARY KEY (`verification_id`),
  ADD UNIQUE KEY `verification_id_UNIQUE` (`verification_id`),
  ADD UNIQUE KEY `user_code_UNIQUE` (`user_code`),
  ADD KEY `fk_user_verification_user1_idx` (`user_code`);

ALTER TABLE `user_withdrawal`
  ADD PRIMARY KEY (`withdrawal_id`),
  ADD UNIQUE KEY `trans_id_UNIQUE` (`trans_id`),
  ADD UNIQUE KEY `withdrawal_id_UNIQUE` (`withdrawal_id`),
  ADD KEY `fk_withdrawal_ifxaccount1_idx` (`ifxaccount_id`);

ALTER TABLE `withdrawal_comment`
  ADD PRIMARY KEY (`withdrawal_comment_id`),
  ADD UNIQUE KEY `withdrawal_comment_id_UNIQUE` (`withdrawal_comment_id`),
  ADD KEY `fk_withdrawal_comment_user_withdrawal1_idx` (`trans_id`),
  ADD KEY `fk_withdrawal_comment_admin1_idx` (`admin_code`);

ALTER TABLE `withdrawal_monitoring`
  ADD PRIMARY KEY (`withdrawal_monitoring_id`),
  ADD UNIQUE KEY `transaction_monitoring_id_UNIQUE` (`withdrawal_monitoring_id`),
  ADD KEY `fk_transaction_monitoring_admin1_idx` (`admin_code`),
  ADD KEY `fk_withdrawal_user_deposit10_idx` (`trans_id`);


ALTER TABLE `accounting_system_budgets`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `accounting_system_office_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `accounting_system_refunds`
  MODIFY `refund_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `accounting_system_req_order`
  MODIFY `req_order_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `account_officers`
  MODIFY `account_officers_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `active_client`
  MODIFY `active_client_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `admin_bulletin`
  MODIFY `admin_bulletin_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `admin_bulletin_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `admin_privilege`
  MODIFY `admin_privilege_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `article`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `article_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `article_visitors`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_category`
  MODIFY `campaign_category_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_email`
  MODIFY `campaign_email_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_email_solo`
  MODIFY `campaign_email_solo_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_email_solo_group`
  MODIFY `campaign_email_solo_group_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_email_track`
  MODIFY `campaign_track_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_sms`
  MODIFY `campaign_sms_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `campaign_sms_track`
  MODIFY `campaign_track_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_application_comment`
  MODIFY `career_application_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_jobs`
  MODIFY `career_jobs_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_achievement`
  MODIFY `career_user_achievement_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_application`
  MODIFY `career_user_application_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_biodata`
  MODIFY `career_user_biodata_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_education`
  MODIFY `career_user_education_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_skill`
  MODIFY `career_user_skill_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `career_user_work_experience`
  MODIFY `career_user_work_experience_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `customer_care_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `deposit_comment`
  MODIFY `deposit_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `deposit_monitoring`
  MODIFY `deposit_monitoring_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dinner2016_comment`
  MODIFY `dinner2016_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dinner2017_comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dinner_2016`
  MODIFY `id_dinner_2016` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `dinner_2017`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `edu_course`
  MODIFY `edu_course_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `edu_lesson`
  MODIFY `edu_lesson_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `edu_lesson_exercise`
  MODIFY `edu_lesson_exercise_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `event_reg`
  MODIFY `event_reg_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `exchange_rate_log`
  MODIFY `exchange_rate_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `facility_category`
  MODIFY `facility_category_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `facility_inventory`
  MODIFY `facility_inventory_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `facility_request`
  MODIFY `facility_request_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `facility_request_comment`
  MODIFY `facility_request_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `facility_station`
  MODIFY `facility_station_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `forum_participant`
  MODIFY `forum_participant_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `free_training_campaign`
  MODIFY `free_training_campaign_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `free_training_campaign_comment`
  MODIFY `free_training_campaign_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hr_attendance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lekki_office_training`
  MODIFY `id_new_office_warming` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log_of_dates`
  MODIFY `log_of_dates_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `miss_tourism_lagos`
  MODIFY `miss_tourism_lagos_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `new_office_warming`
  MODIFY `id_new_office_warming` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner_balance`
  MODIFY `partner_balance_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner_financial_activity_commission`
  MODIFY `partner_financial_activity_commission_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner_payment`
  MODIFY `partner_pay_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner_trading_commission`
  MODIFY `partner_trading_commission_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `partner_withdrawal`
  MODIFY `partner_payment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pencil_comedy_reg`
  MODIFY `pencil_comedy_reg_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `point_based_claimed`
  MODIFY `point_based_claimed_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `point_based_reward`
  MODIFY `point_based_reward_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `point_ranking`
  MODIFY `point_ranking_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `point_ranking_log`
  MODIFY `point_ranking_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `point_season`
  MODIFY `point_season_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_project_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `project_management_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `prospect_biodata`
  MODIFY `prospect_biodata_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `prospect_sales_contact`
  MODIFY `prospect_sales_contact_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `prospect_source`
  MODIFY `prospect_source_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `push_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `quiz_participant`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sales_contact_client_interest`
  MODIFY `sales_contact_client_interest_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sales_contact_comment`
  MODIFY `sales_contact_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sales_contact_email`
  MODIFY `sales_contact_email_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sales_contact_sms`
  MODIFY `sales_contact_sms_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `signal_daily`
  MODIFY `signal_daily_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `signal_symbol`
  MODIFY `signal_symbol_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `snappy_help`
  MODIFY `snappy_help_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `state`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `support_email_inbox`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `support_email_sent_box`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `system_message`
  MODIFY `system_message_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `system_setting`
  MODIFY `system_setting_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `trading_commission`
  MODIFY `trading_commission_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `train_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `train_question_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_account_flag`
  MODIFY `user_account_flag_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_bank`
  MODIFY `user_bank_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_bonus_deposit`
  MODIFY `user_bonus_deposit_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_credential`
  MODIFY `user_credential_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_deposit`
  MODIFY `user_deposit_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_deposit_meta`
  MODIFY `user_deposit_meta_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_deposits`
  MODIFY `user_edu_deposits_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_deposits_comment`
  MODIFY `user_edu_deposits_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_exercise_log`
  MODIFY `user_edu_exercise_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_fee_payment`
  MODIFY `user_edu_fee_payment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_student_log`
  MODIFY `user_edu_student_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_support_answer`
  MODIFY `user_edu_support_answer_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_edu_support_request`
  MODIFY `user_edu_support_request_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_ifxaccount`
  MODIFY `ifxaccount_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_ilpr_enrolment`
  MODIFY `user_ilpr_enrolment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_loyalty_log`
  MODIFY `user_loyalty_log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_meta`
  MODIFY `user_meta_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_subscription`
  MODIFY `user_subscription_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_val_2017`
  MODIFY `user_val_2017_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_withdrawal`
  MODIFY `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `withdrawal_comment`
  MODIFY `withdrawal_comment_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `withdrawal_monitoring`
  MODIFY `withdrawal_monitoring_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `admin_privilege`
  ADD CONSTRAINT `fk_admin_privilege_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `campaign_email`
  ADD CONSTRAINT `fk_campaign_email_campaign_category1` FOREIGN KEY (`campaign_category_id`) REFERENCES `campaign_category` (`campaign_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `campaign_email_solo`
  ADD CONSTRAINT `fk_campaign_email_solo_campaign_email_solo_group1` FOREIGN KEY (`solo_group`) REFERENCES `campaign_email_solo_group` (`campaign_email_solo_group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `campaign_sms`
  ADD CONSTRAINT `fk_campaign_sms_campaign_category1` FOREIGN KEY (`campaign_category_id`) REFERENCES `campaign_category` (`campaign_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_application_comment`
  ADD CONSTRAINT `fk_career_application_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_career_application_comment_career_user_application1` FOREIGN KEY (`application_id`) REFERENCES `career_user_application` (`career_user_application_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_achievement`
  ADD CONSTRAINT `fk_career_user_achievement_career_user_biodata1` FOREIGN KEY (`cu_user_code`) REFERENCES `career_user_biodata` (`cu_user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_application`
  ADD CONSTRAINT `fk_career_user_application_career_jobs1` FOREIGN KEY (`job_code`) REFERENCES `career_jobs` (`job_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_career_user_application_career_user_biodata1` FOREIGN KEY (`cu_user_code`) REFERENCES `career_user_biodata` (`cu_user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_biodata`
  ADD CONSTRAINT `fk_career_user_biodata_state1` FOREIGN KEY (`state_of_origin`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_career_user_biodata_state2` FOREIGN KEY (`state`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_education`
  ADD CONSTRAINT `fk_career_user_education_career_user_biodata1` FOREIGN KEY (`cu_user_code`) REFERENCES `career_user_biodata` (`cu_user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_skill`
  ADD CONSTRAINT `fk_career_user_skill_career_user_biodata1` FOREIGN KEY (`cu_user_code`) REFERENCES `career_user_biodata` (`cu_user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `career_user_work_experience`
  ADD CONSTRAINT `fk_career_user_work_experience_career_user_biodata1` FOREIGN KEY (`cu_user_code`) REFERENCES `career_user_biodata` (`cu_user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_career_user_work_experience_state1` FOREIGN KEY (`location`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `deposit_comment`
  ADD CONSTRAINT `fk_deposit_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deposit_comment_user_deposit1` FOREIGN KEY (`trans_id`) REFERENCES `user_deposit` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `deposit_monitoring`
  ADD CONSTRAINT `fk_deposit_monitoring_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deposit_user_deposit1` FOREIGN KEY (`trans_id`) REFERENCES `user_deposit` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `dinner2016_comment`
  ADD CONSTRAINT `fk_dinner2016_comment_dinner_20161` FOREIGN KEY (`dinner_id`) REFERENCES `dinner_2016` (`id_dinner_2016`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `edu_lesson`
  ADD CONSTRAINT `fk_edu_lesson_edu_course1` FOREIGN KEY (`course_id`) REFERENCES `edu_course` (`edu_course_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `edu_lesson_exercise`
  ADD CONSTRAINT `fk_edu_lesson_exercise_edu_lesson1` FOREIGN KEY (`lesson_id`) REFERENCES `edu_lesson` (`edu_lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `facility_inventory`
  ADD CONSTRAINT `fk_facility_inventory_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_inventory_facility_category1` FOREIGN KEY (`category_id`) REFERENCES `facility_category` (`facility_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_inventory_facility_station1` FOREIGN KEY (`station_id`) REFERENCES `facility_station` (`facility_station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `facility_request`
  ADD CONSTRAINT `fk_facility_request_admin1` FOREIGN KEY (`author`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_facility_category1` FOREIGN KEY (`category_id`) REFERENCES `facility_category` (`facility_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_facility_station1` FOREIGN KEY (`station_id`) REFERENCES `facility_station` (`facility_station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `facility_request_comment`
  ADD CONSTRAINT `fk_facility_request_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facility_request_comment_facility_request1` FOREIGN KEY (`request_id`) REFERENCES `facility_request` (`facility_request_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `free_training_campaign_comment`
  ADD CONSTRAINT `fk_free_training_campaign_comment_free_training_campaign1` FOREIGN KEY (`training_campaign_id`) REFERENCES `free_training_campaign` (`free_training_campaign_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `partner`
  ADD CONSTRAINT `fk_partner_user_ifxaccount1` FOREIGN KEY (`settlement_ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `partner_financial_activity_commission`
  ADD CONSTRAINT `fk_partner_financial_activity_commission_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `partner_trading_commission`
  ADD CONSTRAINT `fk_partner_payment_trading_commission_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `partner_withdrawal`
  ADD CONSTRAINT `fk_partner_withdrawal_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_partner_withdrawal_user_ifxaccount1` FOREIGN KEY (`account_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_partner_withdrawalt_partner1` FOREIGN KEY (`partner_code`) REFERENCES `partner` (`partner_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `pencil_comedy_reg`
  ADD CONSTRAINT `fk_pencil_comedy_reg_state1` FOREIGN KEY (`state_of_residence`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `point_based_claimed`
  ADD CONSTRAINT `fk_point_based_claimed_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `point_based_reward`
  ADD CONSTRAINT `fk_points_based_reward_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `point_ranking_log`
  ADD CONSTRAINT `fk_point_ranking_log_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `prospect_biodata`
  ADD CONSTRAINT `fk_prospect_biodata_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prospect_biodata_prospect_source1` FOREIGN KEY (`prospect_source`) REFERENCES `prospect_source` (`prospect_source_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `prospect_sales_contact`
  ADD CONSTRAINT `fk_prospect_sales_contact_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_prospect_sales_contact_prospect_biodata1` FOREIGN KEY (`prospect_id`) REFERENCES `prospect_biodata` (`prospect_biodata_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `sales_contact_client_interest`
  ADD CONSTRAINT `fk_sales_contact_client_interest_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `signal_daily`
  ADD CONSTRAINT `fk_signal_daily_signal_symbol1` FOREIGN KEY (`symbol_id`) REFERENCES `signal_symbol` (`signal_symbol_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `snappy_help`
  ADD CONSTRAINT `fk_snappy_help_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `state`
  ADD CONSTRAINT `fk_state_country1` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `trading_commission`
  ADD CONSTRAINT `fk_user_trading_commission_currency1` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `train_questions`
  ADD CONSTRAINT `fk_train_questions_edu_course1` FOREIGN KEY (`course_id`) REFERENCES `edu_course` (`edu_course_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_train_questions_edu_lesson1` FOREIGN KEY (`lesson_id`) REFERENCES `edu_lesson` (`edu_lesson_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `train_question_options`
  ADD CONSTRAINT `fk_train_question_options_train_questions1` FOREIGN KEY (`question_id`) REFERENCES `train_questions` (`question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_account_flag`
  ADD CONSTRAINT `fk_user_account_flag_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_account_flag_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_bank`
  ADD CONSTRAINT `fk_bank_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_bank_nigerian_banks1` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_bonus_deposit`
  ADD CONSTRAINT `fk_user_bonus_deposit_user_deposit1` FOREIGN KEY (`user_deposit_id`) REFERENCES `user_deposit` (`user_deposit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_bonus_deposit_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_credential`
  ADD CONSTRAINT `fk_credential_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_deposit`
  ADD CONSTRAINT `fk_deposit_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_deposit_meta`
  ADD CONSTRAINT `fk_user_deposit_meta_user_deposit1` FOREIGN KEY (`user_deposit_id`) REFERENCES `user_deposit` (`user_deposit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_edu_deposits_comment`
  ADD CONSTRAINT `fk_user_edu_deposits_comment_user_edu_deposits1` FOREIGN KEY (`trans_id`) REFERENCES `user_edu_deposits` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_edu_fee_payment`
  ADD CONSTRAINT `fk_user_edu_fee_payment_user_edu_deposits1` FOREIGN KEY (`reference`) REFERENCES `user_edu_deposits` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_edu_support_answer`
  ADD CONSTRAINT `fk_user_edu_support_answer_user_edu_support_request1` FOREIGN KEY (`request_id`) REFERENCES `user_edu_support_request` (`user_edu_support_request_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_ifxaccount`
  ADD CONSTRAINT `fk_ifxaccount_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_ilpr_enrolment`
  ADD CONSTRAINT `fk_user_ilpr_enrolment_user_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_loyalty_log`
  ADD CONSTRAINT `fk_user_loyalty_log_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_meta`
  ADD CONSTRAINT `fk_user_meta_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`state_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_meta_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_subscription`
  ADD CONSTRAINT `fk_subscription_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_subscription_campaign_category1` FOREIGN KEY (`campaign_category_id`) REFERENCES `campaign_category` (`campaign_category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_verification`
  ADD CONSTRAINT `fk_user_verification_user1` FOREIGN KEY (`user_code`) REFERENCES `user` (`user_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `user_withdrawal`
  ADD CONSTRAINT `fk_withdrawal_ifxaccount1` FOREIGN KEY (`ifxaccount_id`) REFERENCES `user_ifxaccount` (`ifxaccount_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `withdrawal_comment`
  ADD CONSTRAINT `fk_withdrawal_comment_admin1` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_withdrawal_comment_user_withdrawal1` FOREIGN KEY (`trans_id`) REFERENCES `user_withdrawal` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `withdrawal_monitoring`
  ADD CONSTRAINT `fk_withdrawal_user_deposit10` FOREIGN KEY (`trans_id`) REFERENCES `user_withdrawal` (`trans_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_withdrawalt_monitoring_admin10` FOREIGN KEY (`admin_code`) REFERENCES `admin` (`admin_code`) ON DELETE NO ACTION ON UPDATE NO ACTION;
