CREATE TABLE `hr_attendance_locations` (
`location_id` int(11) NOT NULL,
`location` varchar(255) NOT NULL,
`ip_address` varchar(255) NOT NULL,
`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE `hr_attendance_log` (
`log_id` int(11) NOT NULL AUTO_INCREMENT,
`date` varchar(100) NOT NULL,
`time` varchar(100) NOT NULL,
`admin_code` varchar(255) NOT NULL,
`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`location` varchar(255) NOT NULL,
PRIMARY KEY (`log_id`)
) 