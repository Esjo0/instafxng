
CREATE TABLE `campaign_category` (
  `campaign_category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `client_group` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL COMMENT '1 - All Clients 2 - Last Month New Clients 3 - Free Training Campaign Clients 4 - Level 1 Clients 5 - Level 2 Clients 6 - Level 3 Clients 7 - Unverified Clients 8 - Dinner Clients 9 - Lagos Clients 10 - Online Training Students 11 - Lekki Students 12 - Diamond Students 13 - Past Forum Participants 14 - Clients Interested in Training 15 - Clients Interested in Funding 16 - Clients Interested in Bonuses 17 - Clients Interested in Investment 18 - Clients Interested in Services 19 - Clients Interested in Other Things 20 - Last Month Funding Clients 21 - Pencil Unbroken Reg 22 - In-house Test 23 - Top 20 Rank in Current Loyalty Year 24 - Career Application Submitted 25 - Top Traders 26 - Prospect - Pencil Comedy Event 27 - Prospect - 500 USD No-Deposit 28 - Online Trainee Not Started 29 - Point Winners (Dec ''16 - Oct ''17) 30 - Commission Clients (Dec ''16 - Oct ''17)',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 - Active\n2 - Inactive',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
