
CREATE TABLE `accounting_system_budgets` (
  `budget_id` int(11) NOT NULL,
  `month_year` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
