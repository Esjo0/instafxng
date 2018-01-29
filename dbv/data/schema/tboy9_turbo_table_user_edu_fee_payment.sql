
CREATE TABLE `user_edu_fee_payment` (
  `user_edu_fee_payment_id` int(11) NOT NULL,
  `reference` varchar(15) NOT NULL,
  `admin_code` varchar(5) NOT NULL,
  `user_code` varchar(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
