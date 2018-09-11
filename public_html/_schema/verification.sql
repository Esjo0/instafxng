ALTER TABLE `user_credential` CHANGE `status` `status` ENUM('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '1 - Awaiting Moderation2 - Moderated3 - Pending';

CREATE TABLE `user_credential_remark` (
 `credential_id` varchar(100) NOT NULL,
 `remark` text NOT NULL,
 `admin` varchar(100) NOT NULL,
 `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1