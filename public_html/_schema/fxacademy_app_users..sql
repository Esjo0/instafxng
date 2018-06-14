CREATE TABLE `fxacademy_app_users` (
 `fxacademy_app_user_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_token` varchar(255) NOT NULL,
 `user_code` varchar(10) NOT NULL,
 PRIMARY KEY (`fxacademy_app_user_id`),
 UNIQUE KEY `fxacademy_app_users_fxacademy_app_user_uindex` (`fxacademy_app_user_id`),
 UNIQUE KEY `fxacademy_app_users_user_code_uindex` (`user_code`)
)