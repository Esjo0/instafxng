
CREATE TABLE `reminders` (
  `reminder_id` int(11) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `effect_date` varchar(500) NOT NULL,
  `status` varchar(3) NOT NULL DEFAULT 'ON',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
