
CREATE TABLE `quiz_participant` (
  `participant_id` int(11) NOT NULL,
  `participant_email` varchar(255) NOT NULL,
  `total_time` float DEFAULT '0',
  `total_questions` int(11) DEFAULT '0',
  `questions_answered` varchar(1000) DEFAULT ' ',
  `questions_answered_correctly` int(11) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `average_time` float NOT NULL DEFAULT '0',
  `total_score` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
