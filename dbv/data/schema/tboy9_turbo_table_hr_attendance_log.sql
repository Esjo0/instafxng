
CREATE TABLE `hr_attendance_log` (
  `log_id` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `location` enum('1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
