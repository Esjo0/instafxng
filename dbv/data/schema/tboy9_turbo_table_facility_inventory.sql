
CREATE TABLE `facility_inventory` (
  `facility_inventory_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `admin_code` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `purchase_date` datetime NOT NULL,
  `remark` text,
  `inventory_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 - Good Condition\n2 - Bad Condition\n3 - Write off',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
