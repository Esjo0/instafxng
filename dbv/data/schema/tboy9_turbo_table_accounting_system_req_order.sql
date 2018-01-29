
CREATE TABLE `accounting_system_req_order` (
  `req_order_id` int(11) NOT NULL,
  `req_order_code` varchar(255) NOT NULL,
  `req_order` text NOT NULL,
  `req_order_total` varchar(255) NOT NULL,
  `author_code` varchar(255) NOT NULL,
  `admin_code` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('1','2','3') DEFAULT '1' COMMENT '1-PENDING   2-APPROVED  3-DECLINED',
  `comments` varchar(255) DEFAULT NULL,
  `payment_status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1-PENDING   2-PAID',
  `location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
