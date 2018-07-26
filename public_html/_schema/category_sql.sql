ALTER TABLE `campaign_category` CHANGE `client_group` `client_group`
ENUM('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'
,'21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39'
,'40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55')
CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '1 - All Clients 2 - Last Month New Clients ' ||
 '3 - Free Training Campaign Clients 4 - Level 1 Clients 5 - Level 2 Clients 6 - Level 3 Clients 7 - Unverified Clients ' ||
  '8 - Dinner Clients 9 - Lagos Clients 10 - Online Training Students 11 - Lekki Students 12 - Diamond Students';