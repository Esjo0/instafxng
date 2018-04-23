ALTER TABLE `free_training_campaign` CHANGE `campaign_period` `campaign_period` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `prospect_ilpr_biodata` CHANGE `campaign_period` `campaign_period` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;