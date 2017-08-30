
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `active_client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `active_client` (
  `active_client_id` INT(11) NOT NULL AUTO_INCREMENT,
  `clients` INT(11) NOT NULL,
  `accounts` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`active_client_id`),
  UNIQUE INDEX `date_UNIQUE` (`date` ASC),
  UNIQUE INDEX `active_client_id_UNIQUE` (`active_client_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 133
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `pass_salt` VARCHAR(64) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  `first_name` VARCHAR(30) NOT NULL,
  `middle_name` VARCHAR(30) NULL DEFAULT NULL,
  `last_name` VARCHAR(30) NOT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive\n3 - Suspended',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE INDEX `admin_code_UNIQUE` (`admin_code` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `salt_UNIQUE` (`pass_salt` ASC),
  UNIQUE INDEX `admin_id_UNIQUE` (`admin_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `admin_bulletin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_bulletin` (
  `admin_bulletin_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `allowed_admin` TEXT NOT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '2' COMMENT '1 - Published\n2 - Draft\n3 - Inactive',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`admin_bulletin_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `admin_privilege`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_privilege` (
  `admin_privilege_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `allowed_pages` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`admin_privilege_id`, `admin_code`),
  UNIQUE INDEX `admin_privilege_id_UNIQUE` (`admin_privilege_id` ASC),
  UNIQUE INDEX `admin_code_UNIQUE` (`admin_code` ASC),
  CONSTRAINT `fk_admin_privilege_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `article` (
  `article_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `keyword` VARCHAR(255) NOT NULL,
  `display_image` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `view_count` INT(11) NOT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '2' COMMENT '1 - Published\n2 - Draft\n3 - Inactive',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE INDEX `article_id_UNIQUE` (`article_id` ASC),
  INDEX `fk_article_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_article_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 544
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `bank`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bank` (
  `bank_id` INT(11) NOT NULL AUTO_INCREMENT,
  `bank_name` VARCHAR(150) NOT NULL,
  `bank_alias` VARCHAR(45) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`bank_id`),
  UNIQUE INDEX `banks_id_UNIQUE` (`bank_id` ASC),
  UNIQUE INDEX `bank_name_UNIQUE` (`bank_name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 23
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_category` (
  `campaign_category_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `client_group` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12') NOT NULL COMMENT '1 - All Clients 2 - Last Month New Clients 3 - Free Training Campaign Clients 4 - Level 1 Clients 5 - Level 2 Clients 6 - Level 3 Clients 7 - Unverified Clients 8 - Dinner Clients 9 - Lagos Clients 10 - Online Training Students 11 - Lekki Students 12 - Diamond Students',
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_category_id`),
  UNIQUE INDEX `campaign_category_id_UNIQUE` (`campaign_category_id` ASC),
  UNIQUE INDEX `client_group_UNIQUE` (`client_group` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_email`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_email` (
  `campaign_email_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `campaign_category_id` INT(11) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `send_date` DATETIME NULL DEFAULT NULL,
  `send_status` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `status` ENUM('1', '2', '3', '4', '5', '6') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Approved\n4 - Disapproved\n5 - In Progress\n6 - Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_email_id`),
  UNIQUE INDEX `campaign_email_id_UNIQUE` (`campaign_email_id` ASC),
  INDEX `fk_campaign_email_campaign_category1_idx` (`campaign_category_id` ASC),
  CONSTRAINT `fk_campaign_email_campaign_category1`
    FOREIGN KEY (`campaign_category_id`)
    REFERENCES `campaign_category` (`campaign_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 36
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_email_solo_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_email_solo_group` (
  `campaign_email_solo_group_id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(45) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`campaign_email_solo_group_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_email_solo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_email_solo` (
  `campaign_email_solo_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `solo_group` INT(11) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Inactive',
  `day_to_send` INT(11) NULL DEFAULT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_email_solo_id`),
  UNIQUE INDEX `campaign_email_solo_id_UNIQUE` (`campaign_email_solo_id` ASC),
  INDEX `fk_campaign_email_solo_campaign_email_solo_group1_idx` (`solo_group` ASC),
  CONSTRAINT `fk_campaign_email_solo_campaign_email_solo_group1`
    FOREIGN KEY (`solo_group`)
    REFERENCES `campaign_email_solo_group` (`campaign_email_solo_group_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_email_track`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_email_track` (
  `campaign_track_id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `recipient_query` TEXT NOT NULL,
  `total_recipient` INT(11) NOT NULL,
  `current_offset` INT(11) NOT NULL DEFAULT '0',
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_track_id`),
  UNIQUE INDEX `campaign_id_UNIQUE` (`campaign_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_sms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_sms` (
  `campaign_sms_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `campaign_category_id` INT(11) NOT NULL,
  `content` VARCHAR(255) NOT NULL,
  `send_date` DATETIME NULL DEFAULT NULL,
  `send_status` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `status` ENUM('1', '2', '3', '4', '5', '6') NOT NULL DEFAULT '1' COMMENT '1 - Draft\n2 - Published\n3 - Approved\n4 - Disapproved\n5 - In Progress\n6 - Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_sms_id`),
  UNIQUE INDEX `campaign_sms_id_UNIQUE` (`campaign_sms_id` ASC),
  INDEX `fk_campaign_sms_campaign_category1_idx` (`campaign_category_id` ASC),
  CONSTRAINT `fk_campaign_sms_campaign_category1`
    FOREIGN KEY (`campaign_category_id`)
    REFERENCES `campaign_category` (`campaign_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `campaign_sms_track`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign_sms_track` (
  `campaign_track_id` INT(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NOT NULL,
  `recipient_query` TEXT NOT NULL,
  `total_recipient` INT(11) NOT NULL,
  `current_offset` INT(11) NOT NULL DEFAULT '0',
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_track_id`),
  UNIQUE INDEX `campaign_id_UNIQUE` (`campaign_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `career_jobs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_jobs` (
  `career_jobs_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `job_code` VARCHAR(6) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `detail` TEXT NOT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Closed\n2 - Open',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`career_jobs_id`),
  UNIQUE INDEX `career_jobs_id_UNIQUE` (`career_jobs_id` ASC),
  UNIQUE INDEX `job_code_UNIQUE` (`job_code` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `country`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `country` (
  `country_id` INT(11) NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(30) NOT NULL,
  `capital` VARCHAR(20) NOT NULL,
  `abbr` VARCHAR(3) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE INDEX `state_id_UNIQUE` (`country_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `currency`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `currency` (
  `currency_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `symbol` VARCHAR(5) NOT NULL,
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  UNIQUE INDEX `currency_id_UNIQUE` (`currency_id` ASC),
  UNIQUE INDEX `symbol_UNIQUE` (`symbol` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `pass_salt` VARCHAR(64) NULL DEFAULT NULL,
  `password` VARCHAR(128) NULL DEFAULT NULL,
  `title` VARCHAR(5) NULL DEFAULT NULL,
  `first_name` VARCHAR(20) NOT NULL,
  `middle_name` VARCHAR(20) NULL DEFAULT NULL,
  `last_name` VARCHAR(20) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `user_type` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Client\n2 - Partner\n3 - Prospect',
  `campaign_subscribe` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Subscribed\n2 - Not Subscribed\n3 - Cannot Receive Email',
  `referred_by_code` VARCHAR(5) NOT NULL DEFAULT 'BBLR',
  `sales_last_contact` TIMESTAMP NULL DEFAULT NULL,
  `sales_next_contact` TIMESTAMP NULL DEFAULT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Active2 - Inactive3 - Probation4 - Suspended',
  `reset_code` VARCHAR(20) NULL DEFAULT NULL,
  `reset_expiry` TIMESTAMP NULL DEFAULT NULL,
  `point_balance` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 14168
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_ifxaccount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_ifxaccount` (
  `ifxaccount_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `ifx_acct_no` VARCHAR(11) NOT NULL,
  `is_bonus_account` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `partner_code` VARCHAR(4) NOT NULL DEFAULT 'BBLR',
  `type` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - ILPR\n2 - Non-ILPR',
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '2' COMMENT '1 - New2 - Active3 - Inactive',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`ifxaccount_id`),
  UNIQUE INDEX `ifxaccount_id_UNIQUE` (`ifxaccount_id` ASC),
  UNIQUE INDEX `ifx_acct_no_UNIQUE` (`ifx_acct_no` ASC),
  INDEX `fk_ifxaccount_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_ifxaccount_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 21491
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_deposit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_deposit` (
  `user_deposit_id` INT(11) NOT NULL AUTO_INCREMENT,
  `trans_id` VARCHAR(15) NOT NULL,
  `ifxaccount_id` INT(11) NOT NULL,
  `exchange_rate` DECIMAL(10,2) NOT NULL,
  `dollar_ordered` DECIMAL(10,2) NOT NULL,
  `naira_equivalent_dollar_ordered` DECIMAL(10,2) NOT NULL,
  `naira_service_charge` DECIMAL(10,2) NOT NULL,
  `naira_vat_charge` DECIMAL(10,2) NOT NULL,
  `naira_stamp_duty` DECIMAL(10,2) NOT NULL,
  `naira_total_payable` DECIMAL(10,2) NOT NULL,
  `client_naira_notified` DECIMAL(10,2) NULL DEFAULT NULL,
  `client_pay_date` DATE NULL DEFAULT NULL,
  `client_pay_method` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9') NULL DEFAULT NULL COMMENT '1 - WebPay 2 - Internet Transfer 3 - ATM Transfer 4 - Bank Transfer 5 - Mobile Money Transfer 6 - Cash Deposit 7 - Office Funding 8 - Not Listed 9 - USSD',
  `client_reference` VARCHAR(255) NULL DEFAULT NULL,
  `client_comment` VARCHAR(255) NULL DEFAULT NULL,
  `client_comment_response` ENUM('1', '2') NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `client_notified_date` DATETIME NULL DEFAULT NULL,
  `real_naira_confirmed` DECIMAL(10,2) NULL DEFAULT NULL,
  `real_dollar_equivalent` DECIMAL(10,2) NULL DEFAULT NULL,
  `points_claimed_id` INT(11) NULL DEFAULT NULL,
  `transfer_reference` TEXT NULL DEFAULT NULL COMMENT 'Deposit Transaction Reference Details From Instaforex After Completing The Transaction',
  `deposit_origin` ENUM('1', '2', '3') NULL DEFAULT '1' COMMENT '1 - Online\n2 - Diamond Office\n3 - Ikota Office',
  `status` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10') NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated\n2 - Notified\n3 - Confirmation In Progress\n4 - Confirmation Declined\n5 - Confirmed\n6 - Funding In Progress\n7 - Funding Declined\n8 - Funded / Completed\n9 - Payment Failed\n10 - Expired',
  `order_complete_time` TIMESTAMP NULL DEFAULT NULL COMMENT 'Time order status was changed to complete',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_deposit_id`),
  UNIQUE INDEX `trans_id_UNIQUE` (`trans_id` ASC),
  UNIQUE INDEX `deposit_id_UNIQUE` (`user_deposit_id` ASC),
  UNIQUE INDEX `points_claimed_id_UNIQUE` (`points_claimed_id` ASC),
  INDEX `fk_deposit_ifxaccount1_idx` (`ifxaccount_id` ASC),
  CONSTRAINT `fk_deposit_ifxaccount1`
    FOREIGN KEY (`ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 41919
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `deposit_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `deposit_comment` (
  `deposit_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `trans_id` VARCHAR(15) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL COMMENT 'For comments entered by Admin\nAlso for system comments, e.g. Admin change to confirmed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`deposit_comment_id`),
  UNIQUE INDEX `deposit_comment_id_UNIQUE` (`deposit_comment_id` ASC),
  INDEX `fk_deposit_comment_user_deposit1_idx` (`trans_id` ASC),
  INDEX `fk_deposit_comment_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_deposit_comment_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_deposit_comment_user_deposit1`
    FOREIGN KEY (`trans_id`)
    REFERENCES `user_deposit` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11511
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `deposit_monitoring`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `deposit_monitoring` (
  `deposit_monitoring_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `trans_id` VARCHAR(15) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_monitoring_id`),
  UNIQUE INDEX `transaction_monitoring_id_UNIQUE` (`deposit_monitoring_id` ASC),
  INDEX `fk_transaction_monitoring_admin1_idx` (`admin_code` ASC),
  INDEX `fk_deposit_user_deposit1` (`trans_id` ASC),
  CONSTRAINT `fk_deposit_monitoring_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_deposit_user_deposit1`
    FOREIGN KEY (`trans_id`)
    REFERENCES `user_deposit` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10076
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dinner_2016`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dinner_2016` (
  `id_dinner_2016` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(11) NOT NULL,
  `interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `invite` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `type` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - early 2 - late',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_dinner_2016`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `id_dinner_2016_UNIQUE` (`id_dinner_2016` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 107
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dinner2016_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dinner2016_comment` (
  `dinner2016_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `dinner_id` INT(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`dinner2016_comment_id`),
  INDEX `fk_dinner2016_comment_dinner_20161_idx` (`dinner_id` ASC),
  CONSTRAINT `fk_dinner2016_comment_dinner_20161`
    FOREIGN KEY (`dinner_id`)
    REFERENCES `dinner_2016` (`id_dinner_2016`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 306
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `edu_course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `edu_course` (
  `edu_course_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `course_code` VARCHAR(5) NOT NULL,
  `course_order` INT(11) NOT NULL DEFAULT '1',
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `difficulty_level` ENUM('1', '2', '3', '4') NOT NULL COMMENT '1 - Beginner\n2 - Intermediate\n3 - Advanced\n4 - Expert',
  `display_image` VARCHAR(255) NULL DEFAULT NULL,
  `intro_video_url` VARCHAR(20) NULL DEFAULT NULL,
  `time_required` VARCHAR(45) NULL DEFAULT NULL,
  `course_fee` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Free\n2 - Paid',
  `course_cost` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` DATETIME NOT NULL,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`edu_course_id`),
  UNIQUE INDEX `code_UNIQUE` (`course_code` ASC),
  UNIQUE INDEX `edu_course_id_UNIQUE` (`edu_course_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `edu_lesson`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `edu_lesson` (
  `edu_lesson_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `course_id` INT(11) NOT NULL,
  `lesson_order` INT(11) NOT NULL DEFAULT '1',
  `title` VARCHAR(255) NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `time_required` VARCHAR(45) NULL DEFAULT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Draft2 - Published',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`edu_lesson_id`),
  UNIQUE INDEX `edu_lesson_id_UNIQUE` (`edu_lesson_id` ASC),
  INDEX `fk_edu_lesson_edu_course1_idx` (`course_id` ASC),
  CONSTRAINT `fk_edu_lesson_edu_course1`
    FOREIGN KEY (`course_id`)
    REFERENCES `edu_course` (`edu_course_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `edu_lesson_exercise`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `edu_lesson_exercise` (
  `edu_lesson_exercise_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `lesson_id` INT(11) NOT NULL,
  `question` TEXT NOT NULL,
  `option_a` TEXT NOT NULL,
  `option_b` TEXT NOT NULL,
  `option_c` TEXT NULL DEFAULT NULL,
  `option_d` TEXT NULL DEFAULT NULL,
  `right_option` ENUM('A', 'B', 'C', 'D') NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`edu_lesson_exercise_id`),
  UNIQUE INDEX `edu_lesson_exercise_id_UNIQUE` (`edu_lesson_exercise_id` ASC),
  INDEX `fk_edu_lesson_exercise_edu_lesson1_idx` (`lesson_id` ASC),
  CONSTRAINT `fk_edu_lesson_exercise_edu_lesson1`
    FOREIGN KEY (`lesson_id`)
    REFERENCES `edu_lesson` (`edu_lesson_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 34
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `event_reg`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_reg` (
  `event_reg_id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `email_address` VARCHAR(45) NOT NULL,
  `ifx_acct_no` VARCHAR(11) NOT NULL,
  `phone` VARCHAR(11) NOT NULL,
  `comment` VARCHAR(255) NULL DEFAULT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`event_reg_id`),
  UNIQUE INDEX `event_reg_id_UNIQUE` (`event_reg_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `exchange_rate_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `exchange_rate_log` (
  `exchange_rate_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `change_date` DATE NOT NULL,
  `deposit_ilpr` DECIMAL(10,2) NOT NULL,
  `deposit_nonilpr` DECIMAL(10,2) NOT NULL,
  `withdraw` DECIMAL(10,2) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`exchange_rate_log_id`),
  UNIQUE INDEX `exchange_rate_log_id_UNIQUE` (`exchange_rate_log_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facility_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `facility_category` (
  `facility_category_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`facility_category_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facility_station`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `facility_station` (
  `facility_station_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`facility_station_id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facility_inventory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `facility_inventory` (
  `facility_inventory_id` INT(11) NOT NULL AUTO_INCREMENT,
  `station_id` INT(11) NOT NULL,
  `category_id` INT(11) NULL DEFAULT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `cost` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `purchase_date` DATETIME NOT NULL,
  `remark` TEXT NULL DEFAULT NULL,
  `inventory_date` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Good Condition\n2 - Bad Condition\n3 - Write off',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`facility_inventory_id`),
  INDEX `fk_facility_inventory_facility_station1_idx` (`station_id` ASC),
  INDEX `fk_facility_inventory_facility_category1_idx` (`category_id` ASC),
  INDEX `fk_facility_inventory_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_facility_inventory_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_facility_inventory_facility_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `facility_category` (`facility_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_facility_inventory_facility_station1`
    FOREIGN KEY (`station_id`)
    REFERENCES `facility_station` (`facility_station_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facility_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `facility_request` (
  `facility_request_id` INT(11) NOT NULL AUTO_INCREMENT,
  `author` VARCHAR(5) NOT NULL,
  `station_id` INT(11) NOT NULL,
  `category_id` INT(11) NULL DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `status` ENUM('1', '2', '3', '4', '5', '6') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Confirmed\n3 - Confirmation Declined\n4 - Approved\n5 - Approval Declined\n6 - Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`facility_request_id`),
  INDEX `fk_facility_request_facility_station1_idx` (`station_id` ASC),
  INDEX `fk_facility_request_admin1_idx` (`author` ASC),
  INDEX `fk_facility_request_facility_category1_idx` (`category_id` ASC),
  CONSTRAINT `fk_facility_request_admin1`
    FOREIGN KEY (`author`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_facility_request_facility_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `facility_category` (`facility_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_facility_request_facility_station1`
    FOREIGN KEY (`station_id`)
    REFERENCES `facility_station` (`facility_station_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facility_request_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `facility_request_comment` (
  `facility_request_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `request_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`facility_request_comment_id`),
  INDEX `fk_facility_request_comment_admin1_idx` (`admin_code` ASC),
  INDEX `fk_facility_request_comment_facility_request1_idx` (`request_id` ASC),
  CONSTRAINT `fk_facility_request_comment_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_facility_request_comment_facility_request1`
    FOREIGN KEY (`request_id`)
    REFERENCES `facility_request` (`facility_request_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `forum_participant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `forum_participant` (
  `forum_participant_id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `middle_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `email` VARCHAR(200) CHARACTER SET 'utf8' NOT NULL,
  `phone` VARCHAR(11) CHARACTER SET 'utf8' NOT NULL,
  `venue` ENUM('1', '2') CHARACTER SET 'utf8' NOT NULL COMMENT '1 - Diamond Estate\n2 - Eastline Complex',
  `forum_activate` ENUM('1', '2') CHARACTER SET 'utf8' NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`forum_participant_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `forum_registrations_archive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `forum_registrations_archive` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(200) CHARACTER SET 'utf8' NOT NULL,
  `phone` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `venue` VARCHAR(45) CHARACTER SET 'utf8' NOT NULL,
  `date` DATETIME NOT NULL,
  `comment` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `forum_registrations_archive2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `forum_registrations_archive2` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(200) CHARACTER SET 'utf8' NOT NULL,
  `phone` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `venue` VARCHAR(45) CHARACTER SET 'utf8' NOT NULL,
  `date` DATETIME NOT NULL,
  `comment` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 86
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `free_training_campaign`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `free_training_campaign` (
  `free_training_campaign_id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(25) NOT NULL,
  `last_name` VARCHAR(25) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(11) NOT NULL,
  `state_id` INT(11) NULL DEFAULT NULL,
  `training_interest` ENUM('1', '2') NULL DEFAULT '1' COMMENT '1 - No\n2 - Yes',
  `training_centre` ENUM('1', '2', '3') NULL DEFAULT NULL COMMENT '1 - Diamond Estate2 - Ikota Office3 - Online',
  `attendant` ENUM('1', '2') NOT NULL DEFAULT '1',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`free_training_campaign_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 10681
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `free_training_campaign_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `free_training_campaign_comment` (
  `free_training_campaign_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `training_campaign_id` INT(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`free_training_campaign_comment_id`),
  UNIQUE INDEX `free_training_campaign_comment_id_UNIQUE` (`free_training_campaign_comment_id` ASC),
  INDEX `fk_free_training_campaign_comment_free_training_campaign1_idx` (`training_campaign_id` ASC),
  CONSTRAINT `fk_free_training_campaign_comment_free_training_campaign1`
    FOREIGN KEY (`training_campaign_id`)
    REFERENCES `free_training_campaign` (`free_training_campaign_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10903
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `lekki_office_training`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lekki_office_training` (
  `id_new_office_warming` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(11) NOT NULL,
  `interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_new_office_warming`),
  UNIQUE INDEX `id_dinner_2016_UNIQUE` (`id_new_office_warming` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `log_of_dates`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `log_of_dates` (
  `log_of_dates_id` INT(11) NOT NULL AUTO_INCREMENT,
  `date_of_day` DATE NOT NULL,
  PRIMARY KEY (`log_of_dates_id`),
  UNIQUE INDEX `log_of_dates_id_UNIQUE` (`log_of_dates_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 6210
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `miss_tourism_lagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `miss_tourism_lagos` (
  `miss_tourism_lagos_id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `age` INT(2) NOT NULL,
  `school` VARCHAR(255) NOT NULL,
  `height` VARCHAR(4) NOT NULL,
  `hobby` VARCHAR(255) NULL DEFAULT NULL,
  `fav_food` VARCHAR(255) NULL DEFAULT NULL,
  `ambition` TEXT NULL DEFAULT NULL,
  `contest_id` INT(2) NOT NULL,
  `image_name` VARCHAR(100) NOT NULL,
  `image_count` INT(2) NOT NULL,
  `created` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`miss_tourism_lagos_id`),
  UNIQUE INDEX `contest_id_UNIQUE` (`contest_id` ASC),
  UNIQUE INDEX `miss_tourism_lagos_id_UNIQUE` (`miss_tourism_lagos_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `new_office_warming`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `new_office_warming` (
  `id_new_office_warming` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(11) NOT NULL,
  `interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet\n2 - Yes\n3 - No\n4 - Maybe',
  `admin_interest` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - Not Yet2 - Yes3 - No4 - Maybe',
  `attended` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - No 2 - Yes',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_new_office_warming`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `id_dinner_2016_UNIQUE` (`id_new_office_warming` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 92
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner` (
  `partner_id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_code` VARCHAR(5) NOT NULL,
  `user_code` VARCHAR(11) NOT NULL,
  `settlement_ifxaccount_id` INT(11) NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Active\n3 - Inactive\n4 - Suspended',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`partner_id`),
  UNIQUE INDEX `partner_code_UNIQUE` (`partner_code` ASC),
  UNIQUE INDEX `partners_id_UNIQUE` (`partner_id` ASC),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  INDEX `fk_partner_user_ifxaccount1_idx` (`settlement_ifxaccount_id` ASC),
  CONSTRAINT `fk_partner_user_ifxaccount1`
    FOREIGN KEY (`settlement_ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner_balance`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner_balance` (
  `partner_balance_id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_code` VARCHAR(5) NOT NULL,
  `type` ENUM('1', '2') NOT NULL COMMENT '1 - Trading\n2 - Financial',
  `balance` DECIMAL(10,2) NOT NULL,
  `createad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`partner_balance_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner_financial_activity_commission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner_financial_activity_commission` (
  `partner_financial_activity_commission_id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_code` VARCHAR(5) NOT NULL,
  `reference_trans_id` VARCHAR(15) NOT NULL COMMENT 'Gets input from the trans_id column of the following tables\n\nuser_deposit\nuser_withdrawal\npartner_payment',
  `amount` DECIMAL(10,2) NOT NULL,
  `trans_type` ENUM('1', '2', '3') NOT NULL COMMENT '1 - Credit On User_deposit \n2 - Credit On User_withdrawal \n3 - Debit',
  `balance` DECIMAL(10,2) NOT NULL,
  `date` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`partner_financial_activity_commission_id`),
  UNIQUE INDEX `partner_financial_activity_commission_id_UNIQUE` (`partner_financial_activity_commission_id` ASC),
  INDEX `fk_partner_financial_activity_commission_partner1_idx` (`partner_code` ASC),
  CONSTRAINT `fk_partner_financial_activity_commission_partner1`
    FOREIGN KEY (`partner_code`)
    REFERENCES `partner` (`partner_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner_payment` (
  `partner_pay_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(10) NULL DEFAULT NULL,
  `partner_code` VARCHAR(50) NULL DEFAULT NULL,
  `account_id` VARCHAR(30) NULL DEFAULT NULL,
  `amount` DOUBLE(10,2) NULL DEFAULT NULL,
  `comment` TEXT NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_pay_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner_trading_commission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner_trading_commission` (
  `partner_trading_commission_id` INT(11) NOT NULL AUTO_INCREMENT,
  `partner_code` VARCHAR(5) NOT NULL,
  `reference_trans_id` VARCHAR(15) NOT NULL COMMENT 'Gets input from the trans_id column of the following tables\n\ntrading commission\npartner_payment',
  `amount` DECIMAL(10,2) NOT NULL,
  `trans_type` ENUM('1', '2') NOT NULL COMMENT '1 - Credit for Trading Commission\n2 - Debit',
  `balance` DECIMAL(10,2) NOT NULL,
  `date` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`partner_trading_commission_id`),
  UNIQUE INDEX `partner_payment_id_UNIQUE` (`partner_trading_commission_id` ASC),
  UNIQUE INDEX `reference_trans_id_UNIQUE` (`reference_trans_id` ASC),
  INDEX `fk_partner_payment_trading_commission_partner1_idx` (`partner_code` ASC),
  CONSTRAINT `fk_partner_payment_trading_commission_partner1`
    FOREIGN KEY (`partner_code`)
    REFERENCES `partner` (`partner_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `partner_withdrawal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `partner_withdrawal` (
  `partner_payment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` VARCHAR(15) NOT NULL,
  `admin_code` VARCHAR(5) NULL DEFAULT NULL,
  `partner_code` VARCHAR(5) NOT NULL,
  `account_id` INT(11) NOT NULL COMMENT 'ifxacccount_id, bank_account_id',
  `amount` DECIMAL(10,2) NOT NULL,
  `trans_type` ENUM('1', '2') NULL DEFAULT NULL COMMENT '1 - IFX Payment \n2 - Bank Payment',
  `transfer_reference` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3') NOT NULL COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `comment` TEXT NOT NULL,
  PRIMARY KEY (`partner_payment_id`),
  UNIQUE INDEX `partner_payment_id_UNIQUE` (`partner_payment_id` ASC),
  UNIQUE INDEX `transaction_id_UNIQUE` (`transaction_id` ASC),
  INDEX `fk_partner_payment_admin1_idx` (`admin_code` ASC),
  INDEX `fk_partner_payment_user_ifxaccount1_idx` (`account_id` ASC),
  INDEX `fk_partner_payment_partner1_idx` (`partner_code` ASC),
  CONSTRAINT `fk_partner_withdrawal_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_partner_withdrawal_user_ifxaccount1`
    FOREIGN KEY (`account_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_partner_withdrawalt_partner1`
    FOREIGN KEY (`partner_code`)
    REFERENCES `partner` (`partner_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `point_based_claimed`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `point_based_claimed` (
  `point_based_claimed_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `point_claimed` DECIMAL(10,2) NOT NULL,
  `dollar_amount` DECIMAL(10,2) NOT NULL,
  `rate_used` DECIMAL(10,2) NOT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Completed\n3 - Failed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`point_based_claimed_id`),
  UNIQUE INDEX `point_based_claimed_id_UNIQUE` (`point_based_claimed_id` ASC),
  INDEX `fk_point_based_claimed_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_point_based_claimed_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 212
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `point_based_reward`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `point_based_reward` (
  `point_based_reward_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `point_earned` DECIMAL(10,2) NOT NULL,
  `rate_used` DECIMAL(10,2) NOT NULL,
  `type` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Deposit\n2 - Trading',
  `reference` INT(11) NOT NULL,
  `is_active` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Yes 2 - No',
  `date_earned` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`point_based_reward_id`),
  INDEX `fk_points_based_reward_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_points_based_reward_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 46378
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `point_ranking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `point_ranking` (
  `point_ranking_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `year_earned` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `year_earned_archive` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `year_rank` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `month_earned` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `month_earned_archive` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `month_rank` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `point_claimed` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`point_ranking_id`),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 14153
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `point_ranking_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `point_ranking_log` (
  `point_ranking_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `position` INT(2) NOT NULL,
  `point_earned` DECIMAL(10,2) NOT NULL,
  `type` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly\n2 - Yearly',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`point_ranking_log_id`),
  INDEX `fk_point_ranking_log_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_point_ranking_log_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 81
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `point_season`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `point_season` (
  `point_season_id` INT(11) NOT NULL AUTO_INCREMENT,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `type` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Monthly 2 - Yearly',
  `is_active` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`point_season_id`),
  UNIQUE INDEX `point_season_id_UNIQUE` (`point_season_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sales_contact_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sales_contact_comment` (
  `sales_contact_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_comment_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 197
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sales_contact_email`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sales_contact_email` (
  `sales_contact_email_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_email_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sales_contact_sms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sales_contact_sms` (
  `sales_contact_sms_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `content` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_sms_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `signal_symbol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `signal_symbol` (
  `signal_symbol_id` INT(11) NOT NULL AUTO_INCREMENT,
  `symbol` VARCHAR(10) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`signal_symbol_id`),
  UNIQUE INDEX `symbol_UNIQUE` (`symbol` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `signal_daily`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `signal_daily` (
  `signal_daily_id` INT(11) NOT NULL AUTO_INCREMENT,
  `symbol_id` INT(11) NOT NULL,
  `order_type` VARCHAR(4) NOT NULL,
  `price` VARCHAR(10) NOT NULL,
  `take_profit` VARCHAR(10) NOT NULL,
  `stop_loss` VARCHAR(10) NOT NULL,
  `signal_date` DATE NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`signal_daily_id`),
  INDEX `fk_signal_daily_signal_symbol1_idx` (`symbol_id` ASC),
  CONSTRAINT `fk_signal_daily_signal_symbol1`
    FOREIGN KEY (`symbol_id`)
    REFERENCES `signal_symbol` (`signal_symbol_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 307
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `snappy_help`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `snappy_help` (
  `snappy_help_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('1', '2', '3') NOT NULL COMMENT '1 - Active\n2 - Inactive\n3 - Draft',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`snappy_help_id`),
  UNIQUE INDEX `snappy_help_id_UNIQUE` (`snappy_help_id` ASC),
  INDEX `fk_snappy_help_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_snappy_help_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `state`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `state` (
  `state_id` INT(11) NOT NULL AUTO_INCREMENT,
  `state` VARCHAR(30) NOT NULL,
  `capital` VARCHAR(20) NOT NULL,
  `alias` VARCHAR(20) NOT NULL,
  `country_id` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  UNIQUE INDEX `state_id_UNIQUE` (`state_id` ASC),
  INDEX `fk_state_country1_idx` (`country_id` ASC),
  CONSTRAINT `fk_state_country1`
    FOREIGN KEY (`country_id`)
    REFERENCES `country` (`country_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 38
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `system_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `system_message` (
  `system_message_id` INT(11) NOT NULL AUTO_INCREMENT,
  `constant` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NULL DEFAULT NULL,
  `value` TEXT NOT NULL,
  `type` ENUM('1', '2') NOT NULL COMMENT '1 - Email\n2 - SMS',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`system_message_id`),
  UNIQUE INDEX `constant_UNIQUE` (`constant` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `system_setting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `system_setting` (
  `system_setting_id` INT(11) NOT NULL AUTO_INCREMENT,
  `constant` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `type` ENUM('1', '2') NOT NULL COMMENT '1 - Rates\n2 - Schedules',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`system_setting_id`),
  UNIQUE INDEX `constant_UNIQUE` (`constant` ASC),
  UNIQUE INDEX `system_setting_id_UNIQUE` (`system_setting_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 23
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `trading_commission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trading_commission` (
  `trading_commission_id` INT(11) NOT NULL AUTO_INCREMENT,
  `ifx_acct_no` VARCHAR(11) NOT NULL,
  `volume` DECIMAL(10,2) NOT NULL,
  `commission` DECIMAL(10,2) NOT NULL,
  `currency_id` INT(11) NOT NULL,
  `date_earned` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`trading_commission_id`),
  UNIQUE INDEX `trading_commission_id_UNIQUE` (`trading_commission_id` ASC),
  INDEX `fk_user_trading_commission_currency1_idx` (`currency_id` ASC),
  CONSTRAINT `fk_user_trading_commission_currency1`
    FOREIGN KEY (`currency_id`)
    REFERENCES `currency` (`currency_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 41890
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `train_questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `train_questions` (
  `question_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `course_id` INT(11) NOT NULL,
  `lesson_id` INT(11) NULL DEFAULT NULL,
  `question` TEXT NOT NULL,
  `right_option` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`),
  INDEX `fk_train_questions_edu_course1_idx` (`course_id` ASC),
  INDEX `fk_train_questions_edu_lesson1_idx` (`lesson_id` ASC),
  CONSTRAINT `fk_train_questions_edu_course1`
    FOREIGN KEY (`course_id`)
    REFERENCES `edu_course` (`edu_course_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_train_questions_edu_lesson1`
    FOREIGN KEY (`lesson_id`)
    REFERENCES `edu_lesson` (`edu_lesson_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `train_question_options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `train_question_options` (
  `option_id` INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `option` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`option_id`),
  INDEX `fk_train_question_options_train_questions1_idx` (`question_id` ASC),
  CONSTRAINT `fk_train_question_options_train_questions1`
    FOREIGN KEY (`question_id`)
    REFERENCES `train_questions` (`question_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_account_flag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_account_flag` (
  `user_account_flag_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `ifxaccount_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_account_flag_id`),
  UNIQUE INDEX `user_account_flag_id_UNIQUE` (`user_account_flag_id` ASC),
  INDEX `fk_user_account_flag_user_ifxaccount1_idx` (`ifxaccount_id` ASC),
  INDEX `fk_user_account_flag_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_user_account_flag_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_account_flag_user_ifxaccount1`
    FOREIGN KEY (`ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_bank`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_bank` (
  `user_bank_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `bank_acct_name` VARCHAR(100) NOT NULL,
  `bank_acct_no` VARCHAR(10) NOT NULL,
  `bank_id` INT(11) NOT NULL,
  `remark` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Yes\n2 - No',
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_bank_id`),
  UNIQUE INDEX `bank_id_UNIQUE` (`user_bank_id` ASC),
  INDEX `fk_bank_user1_idx` (`user_code` ASC),
  INDEX `fk_user_bank_banks1_idx` (`bank_id` ASC),
  CONSTRAINT `fk_bank_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_bank_nigerian_banks1`
    FOREIGN KEY (`bank_id`)
    REFERENCES `bank` (`bank_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1699
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_bonus_deposit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_bonus_deposit` (
  `user_bonus_deposit_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `user_deposit_id` INT(11) NOT NULL,
  `ifxaccount_id` INT(11) NOT NULL,
  `comment` VARCHAR(255) NULL DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_bonus_deposit_id`),
  UNIQUE INDEX `user_deposit_deposit_id_UNIQUE` (`user_deposit_id` ASC),
  INDEX `fk_user_bonus_deposit_user_deposit1_idx` (`user_deposit_id` ASC),
  INDEX `fk_user_bonus_deposit_user_ifxaccount1` (`ifxaccount_id` ASC),
  CONSTRAINT `fk_user_bonus_deposit_user_deposit1`
    FOREIGN KEY (`user_deposit_id`)
    REFERENCES `user_deposit` (`user_deposit_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_bonus_deposit_user_ifxaccount1`
    FOREIGN KEY (`ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_credential`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_credential` (
  `user_credential_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `idcard` VARCHAR(255) NULL DEFAULT NULL,
  `passport` VARCHAR(255) NULL DEFAULT NULL COMMENT '1 - Passport\n2 - ID Card\n3 - Signature',
  `signature` VARCHAR(255) NULL DEFAULT NULL,
  `doc_status` ENUM('000', '001', '010', '011', '100', '101', '110', '111') NULL DEFAULT '000' COMMENT '000 - None Approved\n001 - Signature Approved\n010 - Passport Approved\n011 - Passport and Signature Approved\n100 - Idcard Approved\n101 - Idcard and Signature Approved\n110 - Idcard and Passport Approved\n111 - All Approved',
  `remark` VARCHAR(255) NULL DEFAULT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation\n2 - Moderated',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_credential_id`),
  UNIQUE INDEX `credential_id_UNIQUE` (`user_credential_id` ASC),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  INDEX `fk_credential_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_credential_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_deposit_meta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_deposit_meta` (
  `user_deposit_meta_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_deposit_id` INT(11) NOT NULL,
  `trans_status_code` VARCHAR(10) NOT NULL,
  `trans_status_message` VARCHAR(255) NOT NULL,
  `trans_amount` DECIMAL(10,2) NOT NULL,
  `trans_currency` VARCHAR(3) NOT NULL DEFAULT '566',
  `gateway_name` VARCHAR(10) NOT NULL,
  `full_verify_hash` VARCHAR(128) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_deposit_meta_id`),
  UNIQUE INDEX `iduser_deposit_meta_UNIQUE` (`user_deposit_meta_id` ASC),
  UNIQUE INDEX `user_deposit_id_UNIQUE` (`user_deposit_id` ASC),
  INDEX `fk_user_deposit_meta_user_deposit1_idx` (`user_deposit_id` ASC),
  CONSTRAINT `fk_user_deposit_meta_user_deposit1`
    FOREIGN KEY (`user_deposit_id`)
    REFERENCES `user_deposit` (`user_deposit_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1377
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_deposits`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_deposits` (
  `user_edu_deposits_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `trans_id` VARCHAR(15) NOT NULL,
  `course_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `stamp_duty` DECIMAL(10,2) NULL DEFAULT NULL,
  `gateway_charge` DECIMAL(10,2) NULL DEFAULT NULL,
  `pay_method` ENUM('1', '2', '3', '4', '5', '6', '7', '8') NOT NULL DEFAULT '1' COMMENT '1 - WebPay\n2 - Internet Transfer\n3 - ATM Transfer\n4 - Bank Transfer\n5 - Mobile Money Transfer\n6 - Cash Deposit\n7 - Office Payment\n8 - Not Listed',
  `deposit_origin` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - Online\n2 - Diamond Office\n3 - Eastline Office',
  `status` ENUM('1', '2', '3', '4', '5') NOT NULL DEFAULT '1' COMMENT '1 - Deposit Initiated\n2 - Notified\n3 - Confirmed\n4 - Declined\n5 - Failed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_deposits_id`),
  UNIQUE INDEX `user_edu_deposits_id_UNIQUE` (`user_edu_deposits_id` ASC),
  UNIQUE INDEX `trans_id_UNIQUE` (`trans_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_deposits_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_deposits_comment` (
  `user_edu_deposits_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `trans_id` VARCHAR(15) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_deposits_comment_id`),
  UNIQUE INDEX `user_edu_deposits_comment_id_UNIQUE` (`user_edu_deposits_comment_id` ASC),
  INDEX `fk_user_edu_deposits_comment_user_edu_deposits1_idx` (`trans_id` ASC),
  CONSTRAINT `fk_user_edu_deposits_comment_user_edu_deposits1`
    FOREIGN KEY (`trans_id`)
    REFERENCES `user_edu_deposits` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_exercise_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_exercise_log` (
  `user_edu_exercise_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `lesson_id` INT(11) NOT NULL,
  `exercise_id` INT(11) NOT NULL,
  `answer` ENUM('A', 'B', 'C', 'D') NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_exercise_log_id`),
  UNIQUE INDEX `user_edu_exercise_log_id_UNIQUE` (`user_edu_exercise_log_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 92
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_fee_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_fee_payment` (
  `user_edu_fee_payment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `reference` VARCHAR(15) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `user_code` VARCHAR(11) NOT NULL,
  `course_id` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_fee_payment_id`),
  UNIQUE INDEX `user_edu_fee_payment_id_UNIQUE` (`user_edu_fee_payment_id` ASC),
  UNIQUE INDEX `reference_UNIQUE` (`reference` ASC),
  CONSTRAINT `fk_user_edu_fee_payment_user_edu_deposits1`
    FOREIGN KEY (`reference`)
    REFERENCES `user_edu_deposits` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_student_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_student_log` (
  `user_edu_student_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `last_login` DATETIME NOT NULL,
  `last_lesson` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_student_log_id`),
  UNIQUE INDEX `user_edu_student_log_id_UNIQUE` (`user_edu_student_log_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_support_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_support_request` (
  `user_edu_support_request_id` INT(11) NOT NULL AUTO_INCREMENT,
  `support_request_code` VARCHAR(20) NOT NULL,
  `user_code` VARCHAR(11) NOT NULL,
  `lesson_id` INT(11) NOT NULL,
  `course_id` INT(11) NOT NULL,
  `request` TEXT NOT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Open\n2 - Closed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_support_request_id`),
  UNIQUE INDEX `support_request_code_UNIQUE` (`support_request_code` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 16
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_edu_support_answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_edu_support_answer` (
  `user_edu_support_answer_id` INT(11) NOT NULL AUTO_INCREMENT,
  `author` VARCHAR(11) NOT NULL,
  `category` ENUM('1', '2') NOT NULL,
  `request_id` INT(11) NOT NULL,
  `response` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_edu_support_answer_id`),
  INDEX `fk_user_edu_support_answer_user_edu_support_request1_idx` (`request_id` ASC),
  CONSTRAINT `fk_user_edu_support_answer_user_edu_support_request1`
    FOREIGN KEY (`request_id`)
    REFERENCES `user_edu_support_request` (`user_edu_support_request_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_ilpr_enrolment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_ilpr_enrolment` (
  `user_ilpr_enrolment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `ifxaccount_id` INT(11) NOT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Moderated',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_ilpr_enrolment_id`),
  INDEX `fk_user_ilpr_enrolment_user_ifxaccount1_idx` (`ifxaccount_id` ASC),
  CONSTRAINT `fk_user_ilpr_enrolment_user_ifxaccount1`
    FOREIGN KEY (`ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4748
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_meta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_meta` (
  `user_meta_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `address` VARCHAR(255) NULL DEFAULT NULL,
  `address2` VARCHAR(255) NULL DEFAULT NULL,
  `city` VARCHAR(50) NULL DEFAULT NULL,
  `state_id` INT(11) NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_meta_id`),
  UNIQUE INDEX `meta_id_UNIQUE` (`user_meta_id` ASC),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  INDEX `fk_user_meta_state1_idx` (`state_id` ASC),
  CONSTRAINT `fk_user_meta_state1`
    FOREIGN KEY (`state_id`)
    REFERENCES `state` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_meta_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1794
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_subscription`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_subscription` (
  `user_subscription_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `campaign_category_id` INT(11) NOT NULL,
  `campaign_email_id` INT(11) NULL DEFAULT NULL,
  `campaign_sms_id` INT(11) NULL DEFAULT NULL,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_subscription_id`),
  UNIQUE INDEX `user_subscription_id_UNIQUE` (`user_subscription_id` ASC),
  INDEX `fk_subscription_user1_idx` (`user_code` ASC),
  INDEX `fk_user_subscription_campaign_category1_idx` (`campaign_category_id` ASC),
  CONSTRAINT `fk_subscription_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_subscription_campaign_category1`
    FOREIGN KEY (`campaign_category_id`)
    REFERENCES `campaign_category` (`campaign_category_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_val_2017`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_val_2017` (
  `user_val_2017_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `val_pics` VARCHAR(255) NOT NULL,
  `val_message` TEXT NULL DEFAULT NULL,
  `admin_comment` TEXT NULL DEFAULT NULL,
  `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Disapproved',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`user_val_2017_id`),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  UNIQUE INDEX `val_pics_UNIQUE` (`val_pics` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_verification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_verification` (
  `verification_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NULL DEFAULT NULL,
  `phone_code` VARCHAR(6) NULL DEFAULT NULL,
  `phone_status` ENUM('1', '2') NULL DEFAULT '1' COMMENT '1 - New2 - Used',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`verification_id`),
  UNIQUE INDEX `verification_id_UNIQUE` (`verification_id` ASC),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  INDEX `fk_user_verification_user1_idx` (`user_code` ASC),
  CONSTRAINT `fk_user_verification_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4726
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_withdrawal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_withdrawal` (
  `withdrawal_id` INT(11) NOT NULL AUTO_INCREMENT,
  `trans_id` VARCHAR(15) NOT NULL,
  `ifxaccount_id` INT(11) NOT NULL,
  `exchange_rate` DECIMAL(10,2) NULL DEFAULT NULL,
  `dollar_withdraw` DECIMAL(10,2) NOT NULL,
  `naira_equivalent_dollar_withdraw` DECIMAL(10,2) NOT NULL,
  `naira_service_charge` DECIMAL(10,2) NOT NULL,
  `naira_vat_charge` DECIMAL(10,2) NOT NULL,
  `naira_total_withdrawable` DECIMAL(10,2) NOT NULL,
  `client_phone_password` VARCHAR(30) NOT NULL,
  `client_comment` VARCHAR(255) NULL DEFAULT NULL,
  `transfer_reference` TEXT NULL DEFAULT NULL,
  `status` ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10') NOT NULL DEFAULT '1' COMMENT '1 - Withdrawal Initiated\n2 - Account Check In Progress\n3 - Account Check Failed\n4 - Account Check Successful\n5 - Withdrawal In Progress\n6 - Withdrawal Declined\n7 - Withdrawal Successful\n8 - Payment In Progress\n9 - Payment Declined\n10 - Payment Made / Completed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`withdrawal_id`),
  UNIQUE INDEX `trans_id_UNIQUE` (`trans_id` ASC),
  UNIQUE INDEX `withdrawal_id_UNIQUE` (`withdrawal_id` ASC),
  INDEX `fk_withdrawal_ifxaccount1_idx` (`ifxaccount_id` ASC),
  CONSTRAINT `fk_withdrawal_ifxaccount1`
    FOREIGN KEY (`ifxaccount_id`)
    REFERENCES `user_ifxaccount` (`ifxaccount_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11434
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `withdrawal_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `withdrawal_comment` (
  `withdrawal_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `trans_id` VARCHAR(15) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL COMMENT 'For comments entered by Admin\nAlso for system comments, e.g. Admin change to funded',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`withdrawal_comment_id`),
  UNIQUE INDEX `withdrawal_comment_id_UNIQUE` (`withdrawal_comment_id` ASC),
  INDEX `fk_withdrawal_comment_user_withdrawal1_idx` (`trans_id` ASC),
  INDEX `fk_withdrawal_comment_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_withdrawal_comment_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_withdrawal_comment_user_withdrawal1`
    FOREIGN KEY (`trans_id`)
    REFERENCES `user_withdrawal` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6382
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `withdrawal_monitoring`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `withdrawal_monitoring` (
  `withdrawal_monitoring_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `trans_id` VARCHAR(15) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`withdrawal_monitoring_id`),
  UNIQUE INDEX `transaction_monitoring_id_UNIQUE` (`withdrawal_monitoring_id` ASC),
  INDEX `fk_transaction_monitoring_admin1_idx` (`admin_code` ASC),
  INDEX `fk_withdrawal_user_deposit10_idx` (`trans_id` ASC),
  CONSTRAINT `fk_withdrawal_user_deposit10`
    FOREIGN KEY (`trans_id`)
    REFERENCES `user_withdrawal` (`trans_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_withdrawalt_monitoring_admin10`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6382
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sales_contact_client_interest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sales_contact_client_interest` (
  `sales_contact_client_interest_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `interest_training` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_funding` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_bonus` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_investment` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_services` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `interest_other` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Not Interested\n2 - Interested',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sales_contact_client_interest_id`),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  CONSTRAINT `fk_sales_contact_client_interest_user1`
    FOREIGN KEY (`user_code`)
    REFERENCES `user` (`user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_biodata`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_biodata` (
  `career_user_biodata_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `email_address` VARCHAR(200) NOT NULL,
  `cu_password` VARCHAR(128) NOT NULL,
  `pass_salt` VARCHAR(64) NOT NULL,
  `first_name` VARCHAR(150) NOT NULL,
  `last_name` VARCHAR(150) NOT NULL,
  `other_names` VARCHAR(200) NULL,
  `phone_number` VARCHAR(11) NOT NULL,
  `sex` ENUM('M', 'F') NOT NULL COMMENT 'M - Male\nF - Female',
  `marital_status` ENUM('S', 'M') NOT NULL COMMENT 'S - Single\nM - Married',
  `state_of_origin` INT(11) NOT NULL,
  `dob` DATE NOT NULL,
  `address` TEXT NULL,
  `state` INT(11) NULL,
  `is_active` ENUM('1', '2') NULL COMMENT '1 - Yes\n2 - No',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_biodata_id`),
  UNIQUE INDEX `email_address_UNIQUE` (`email_address` ASC),
  UNIQUE INDEX `phone_number_UNIQUE` (`phone_number` ASC),
  UNIQUE INDEX `cu_user_code_UNIQUE` (`cu_user_code` ASC),
  INDEX `fk_career_user_biodata_state1_idx` (`state_of_origin` ASC),
  INDEX `fk_career_user_biodata_state2_idx` (`state` ASC),
  CONSTRAINT `fk_career_user_biodata_state1`
    FOREIGN KEY (`state_of_origin`)
    REFERENCES `state` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_career_user_biodata_state2`
    FOREIGN KEY (`state`)
    REFERENCES `state` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_education`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_education` (
  `career_user_education_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `institution` VARCHAR(255) NOT NULL,
  `field_of_study` VARCHAR(255) NOT NULL,
  `degree` VARCHAR(255) NOT NULL,
  `grade` VARCHAR(150) NOT NULL,
  `date_from` DATE NOT NULL,
  `date_to` DATE NOT NULL,
  `description` TEXT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_education_id`),
  INDEX `fk_career_user_education_career_user_biodata1_idx` (`cu_user_code` ASC),
  CONSTRAINT `fk_career_user_education_career_user_biodata1`
    FOREIGN KEY (`cu_user_code`)
    REFERENCES `career_user_biodata` (`cu_user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_work_experience`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_work_experience` (
  `career_user_work_experience_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `job_title` VARCHAR(200) NOT NULL,
  `company` VARCHAR(255) NOT NULL,
  `location` INT(11) NOT NULL,
  `date_from` DATE NOT NULL,
  `date_to` DATE NOT NULL,
  `description` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_work_experience_id`),
  INDEX `fk_career_user_work_experience_state1_idx` (`location` ASC),
  INDEX `fk_career_user_work_experience_career_user_biodata1_idx` (`cu_user_code` ASC),
  CONSTRAINT `fk_career_user_work_experience_state1`
    FOREIGN KEY (`location`)
    REFERENCES `state` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_career_user_work_experience_career_user_biodata1`
    FOREIGN KEY (`cu_user_code`)
    REFERENCES `career_user_biodata` (`cu_user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_skill`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_skill` (
  `career_user_skill_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `skill_title` VARCHAR(255) NOT NULL,
  `competency` ENUM('1', '2', '3', '4', '5') NOT NULL DEFAULT '1' COMMENT '1 - Beginner\n2 - Advanced\n3 - Professional\n4 - Master\n5 - Certified',
  `description` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_skill_id`),
  INDEX `fk_career_user_skill_career_user_biodata1_idx` (`cu_user_code` ASC),
  CONSTRAINT `fk_career_user_skill_career_user_biodata1`
    FOREIGN KEY (`cu_user_code`)
    REFERENCES `career_user_biodata` (`cu_user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_achievement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_achievement` (
  `career_user_achievement_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `achieve_title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `category` ENUM('1', '2', '3', '4') NOT NULL COMMENT '1 - Certification\n2 - Course\n3 - Honour / Award\n4 - Project',
  `achieve_date` DATE NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_achievement_id`),
  INDEX `fk_career_user_achievement_career_user_biodata1_idx` (`cu_user_code` ASC),
  CONSTRAINT `fk_career_user_achievement_career_user_biodata1`
    FOREIGN KEY (`cu_user_code`)
    REFERENCES `career_user_biodata` (`cu_user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_user_application`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_user_application` (
  `career_user_application_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cu_user_code` VARCHAR(11) NOT NULL,
  `job_code` VARCHAR(6) NOT NULL,
  `status` ENUM('1', '2', '3', '4', '5', '6', '7') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Submitted\n3 - Review\n4 - No Review\n5 - Interviewed\n6 - Employed\n7 - Not Employed',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_user_application_id`),
  INDEX `fk_career_user_application_career_user_biodata1_idx` (`cu_user_code` ASC),
  INDEX `fk_career_user_application_career_jobs1_idx` (`job_code` ASC),
  UNIQUE INDEX `cu_user_code_UNIQUE` (`cu_user_code` ASC),
  CONSTRAINT `fk_career_user_application_career_user_biodata1`
    FOREIGN KEY (`cu_user_code`)
    REFERENCES `career_user_biodata` (`cu_user_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_career_user_application_career_jobs1`
    FOREIGN KEY (`job_code`)
    REFERENCES `career_jobs` (`job_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pencil_comedy_reg`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pencil_comedy_reg` (
  `pencil_comedy_reg_id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(13) NOT NULL,
  `email_address` VARCHAR(200) NOT NULL,
  `client_comment` TEXT NOT NULL,
  `state_of_residence` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pencil_comedy_reg_id`),
  UNIQUE INDEX `email_address_UNIQUE` (`email_address` ASC),
  INDEX `fk_pencil_comedy_reg_state1_idx` (`state_of_residence` ASC),
  CONSTRAINT `fk_pencil_comedy_reg_state1`
    FOREIGN KEY (`state_of_residence`)
    REFERENCES `state` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `career_application_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_application_comment` (
  `career_application_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`career_application_comment_id`),
  INDEX `fk_career_application_comment_career_user_application1_idx` (`application_id` ASC),
  INDEX `fk_career_application_comment_admin1_idx` (`admin_code` ASC),
  CONSTRAINT `fk_career_application_comment_career_user_application1`
    FOREIGN KEY (`application_id`)
    REFERENCES `career_user_application` (`career_user_application_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_career_application_comment_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prospect_source`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prospect_source` (
  `prospect_source_id` INT(11) NOT NULL AUTO_INCREMENT,
  `source_name` VARCHAR(250) NOT NULL,
  `source_description` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prospect_source_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prospect_biodata`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prospect_biodata` (
  `prospect_biodata_id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_code` VARCHAR(5) NOT NULL,
  `email_address` VARCHAR(200) NOT NULL,
  `first_name` VARCHAR(150) NOT NULL,
  `last_name` VARCHAR(150) NOT NULL,
  `other_names` VARCHAR(200) NOT NULL,
  `phone_number` VARCHAR(11) NOT NULL,
  `prospect_source` INT(11) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prospect_biodata_id`),
  UNIQUE INDEX `email_address_UNIQUE` (`email_address` ASC),
  INDEX `fk_prospect_biodata_admin1_idx` (`admin_code` ASC),
  INDEX `fk_prospect_biodata_prospect_source1_idx` (`prospect_source` ASC),
  CONSTRAINT `fk_prospect_biodata_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prospect_biodata_prospect_source1`
    FOREIGN KEY (`prospect_source`)
    REFERENCES `prospect_source` (`prospect_source_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prospect_sales_contact`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prospect_sales_contact` (
  `prospect_sales_contact_id` INT(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` INT(11) NOT NULL,
  `admin_code` VARCHAR(5) NOT NULL,
  `comment` TEXT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prospect_sales_contact_id`),
  INDEX `fk_prospect_sales_contact_admin1_idx` (`admin_code` ASC),
  INDEX `fk_prospect_sales_contact_prospect_biodata1_idx` (`prospect_id` ASC),
  CONSTRAINT `fk_prospect_sales_contact_admin1`
    FOREIGN KEY (`admin_code`)
    REFERENCES `admin` (`admin_code`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prospect_sales_contact_prospect_biodata1`
    FOREIGN KEY (`prospect_id`)
    REFERENCES `prospect_biodata` (`prospect_biodata_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
