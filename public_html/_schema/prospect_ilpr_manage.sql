CREATE TABLE `prospect_ilpr_biodata` (
 `biodata_id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(255) NOT NULL,
 `f_name` varchar(100) NOT NULL,
 `m_name` varchar(100) DEFAULT NULL,
 `l_name` varchar(100) NOT NULL,
 `phone` varchar(20) NOT NULL,
 `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `status` enum('1','2') NOT NULL DEFAULT '1',
 `campaign_period` varchar(50) NOT NULL DEFAULT 'March 2018',
 PRIMARY KEY (`biodata_id`),
 UNIQUE KEY `phone` (`phone`),
 UNIQUE KEY `email` (`email`)
) 