<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('fme_usersurvey')};
CREATE TABLE {$this->getTable('fme_usersurvey')} (
  `question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `qtitle` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `sort_order` text NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

 DROP TABLE IF EXISTS {$this->getTable('fme_answers')};
CREATE TABLE {$this->getTable('fme_answers')} (
  `answer_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(15) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort_order` int(15) DEFAULT NULL,
  `votes` int(15),
  `value` varchar(255) NOT NULL,
  `answer_type` varchar(255) NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `FK_USERSURVEY_QUESSTION_ANSWER` (`question_id`),
  CONSTRAINT `FK_USERSURVEY_QUESSTION_ANSWER` FOREIGN KEY (`question_id`) REFERENCES `fme_usersurvey` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

DROP TABLE IF EXISTS {$this->getTable('fme_questionset')};
CREATE TABLE {$this->getTable('fme_questionset')} (
  `set_id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `question_ids` varchar(255) DEFAULT NULL,
  `sort_order` int(15) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `stores` varchar(255) NOT NULL,
  `locations` varchar(255) NOT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

    ");

$installer->endSetup(); 