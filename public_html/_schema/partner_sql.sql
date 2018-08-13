DROP TABLE `partner`, `partner_balance`, `partner_financial_activity_commission`, `partner_payment`, `partner_trading_commission`, `partner_withdrawal`;

CREATE TABLE IF NOT EXISTS `partner` (
 `partner_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `password` VARCHAR(255) NULL DEFAULT NULL,
 `partner_code_alias` VARCHAR(15) NULL,
 `earning_balance` DECIMAL(10,2) NOT NULL DEFAULT 0,
 `first_name` VARCHAR(45) NOT NULL,
 `middle_name` VARCHAR(45) NOT NULL,
 `last_name` VARCHAR(45) NOT NULL,
 `email_address` VARCHAR(100) NOT NULL,
 `phone_number` VARCHAR(11) NOT NULL,
 `full_address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
 `state_id` INT(11) NOT NULL,
 `phone_code` VARCHAR(6) NOT NULL,
 `phone_code_status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Used',
 `status` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - New \n2 - Active \n3 - Inactive \n4 - Suspended ',
 `type` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Individual\n2 - Company',
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_id`),
 UNIQUE INDEX `partner_code_UNIQUE` (`partner_code` ASC),
 UNIQUE INDEX `partner_code_alias_UNIQUE` (`partner_code_alias` ASC),
 UNIQUE INDEX `email_address_UNIQUE` (`email_address` ASC),
 UNIQUE INDEX `phone_number_UNIQUE` (`phone_number` ASC),
 CONSTRAINT `fk_partner_state1`
 FOREIGN KEY (`state_id`)
 REFERENCES `state` (`state_id`)
 ON DELETE NO ACTION
 ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `partner_company` (
 `partner_company_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `business_name` VARCHAR(100) NOT NULL,
 `register_number` INT(10) NOT NULL,
 `incorporation_date` DATE NOT NULL,
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_company_id`),
 UNIQUE INDEX `partner_code_UNIQUE` (`partner_code` ASC),
 UNIQUE INDEX `business_name_UNIQUE` (`business_name` ASC),
 CONSTRAINT `fk_partner_company_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `partner_bank` (
 `partner_bank_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `bank_acct_name` VARCHAR(100) NOT NULL,
 `bank_acct_no` VARCHAR(10) NOT NULL,
 `bank_id` INT(11) NOT NULL,
 `remark` VARCHAR(255) NOT NULL,
 `is_active` ENUM('1', '2') NOT NULL DEFAULT '2' COMMENT '1 - Yes\n2 - No',
 `status` ENUM('1', '2', '3') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Approved\n3 - Not Approved',
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_bank_id`),
 INDEX `fk_partner_bank_partner1_idx` (`partner_code` ASC),
 CONSTRAINT `fk_partner_bank_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `partner_commission` (
 `partner_commission_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `amount` DECIMAL(10,2) NOT NULL,
 `reference_id` INT(11) NOT NULL,
 `trans_type` ENUM('1', '2', '3') NOT NULL COMMENT '1 - Trading Commission\n2 - Deposit Commission\n3 - Withdrawal Commission',
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_commission_id`),
 UNIQUE INDEX `partner_code_UNIQUE` (`partner_code` ASC),
 CONSTRAINT `fk_partner_commission_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `partner_sales_comment` (
 `partner_sales_comment_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `user_code` VARCHAR(5) NOT NULL,
 `comment` TEXT NOT NULL,
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_sales_comment_id`),
 INDEX `fk_partner_sales_comment_partner1_idx` (`partner_code` ASC),
 CONSTRAINT `fk_partner_sales_comment_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `partner_transaction_comment` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `admin_code` VARCHAR(5) NOT NULL,
 `partner_code` VARCHAR(5) NOT NULL,
 `comment` TEXT NOT NULL,
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `update` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 INDEX `fk_partner_transaction_comment_partner1_idx` (`partner_code` ASC),
 CONSTRAINT `fk_partner_transaction_comment_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `partner_withdrawal` (
 `partner_withdrawal_id` INT(11) NOT NULL AUTO_INCREMENT,
 `transaction_id` VARCHAR(15) NOT NULL,
 `partner_code` VARCHAR(5) NOT NULL,
 `partner_bank_id` INT(11) NOT NULL,
 `amount` DECIMAL(10,2) NOT NULL,
 `transfer_reference` TEXT NOT NULL,
 `status` ENUM('1', '2', '3', '4') NOT NULL DEFAULT '1' COMMENT '1 - New\n2 - Confirmed\n3 - Declined\n4 - Payment Successful',
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_withdrawal_id`),
 UNIQUE INDEX `transaction_id_UNIQUE` (`transaction_id` ASC),
 INDEX `fk_partner_withdrawal_partner1_idx` (`partner_code` ASC),
 CONSTRAINT `fk_partner_withdrawal_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `partner_credential` (
 `partner_credential_id` INT(11) NOT NULL AUTO_INCREMENT,
 `partner_code` VARCHAR(5) NOT NULL,
 `idcard` VARCHAR(255) NULL,
 `passport` VARCHAR(255) NULL,
 `signature` VARCHAR(255) NULL,
 `doc_status` ENUM('000', '001', '010', '011', '100', '101', '110', '111') NOT NULL DEFAULT '000' COMMENT '000 - None Approved\n001 - Signature Approved\n010 - Passport Approved\n011 - Passport and Signature Approved\n100 - Idcard Approved\n101 - Idcard and Signature Approved\n110 - Idcard and Passport Approved\n111 - All Approved',
 `remark` TEXT NULL,
 `status` ENUM('1', '2') NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation\n2 - Moderated',
 `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY (`partner_credential_id`),
 INDEX `fk_partner_credential_partner1_idx` (`partner_code` ASC),
 CONSTRAINT `fk_partner_credential_partner1`
 FOREIGN KEY (`partner_code`)
 REFERENCES `partner` (`partner_code`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
 ENGINE = InnoDB;


ALTER TABLE `partner` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `user` ADD `partner_code` VARCHAR(5) NOT NULL DEFAULT 'BBLR' AFTER `user_code`;