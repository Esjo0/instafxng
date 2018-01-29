
CREATE TABLE `accounting_system_refunds` (
  `refund_id` int(11) NOT NULL,
  `req_order_code` varchar(255) NOT NULL,
  `actual_spent` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
