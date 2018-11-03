CREATE TABLE IF NOT EXISTS `user_first_transaction` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` VARCHAR(11) NOT NULL,
  `trans_id` VARCHAR(15) NOT NULL,
  `trans_type` ENUM('1', '2') NOT NULL COMMENT '1 - Deposit\n2 - Withdrawal',
  `status` ENUM('1', '2', '3') NOT NULL COMMENT '1 - New\n2 - Approved\n3 - Declined - Goes to Refund',
  `comment` TEXT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_code_UNIQUE` (`user_code` ASC),
  UNIQUE INDEX `trans_id_UNIQUE` (`trans_id` ASC))
ENGINE = InnoDB