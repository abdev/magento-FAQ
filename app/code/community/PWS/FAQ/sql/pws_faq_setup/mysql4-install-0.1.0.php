<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('faq_categories')};
CREATE TABLE {$this->getTable('faq_categories')} (
  `category_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('faq_articles')};
CREATE TABLE {$this->getTable('faq_articles')} (
  `article_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `content`  text NOT NULL default '',
  `status` enum('enabled','disabled') default 'enabled',
  `created_on` datetime,
  `updated_on` datetime,
  `views` int(11) unsigned default '0',
  `is_helpful` int(11) unsigned default '0',
  `is_not_helpful` int(11) unsigned default '0',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('faq_categories_articles')};
CREATE TABLE {$this->getTable('faq_categories_articles')} (
  `faq_category_article_id`  int(11) unsigned NOT NULL auto_increment,
  `category_id` int(11) unsigned NOT NULL default '0',
  `article_id` int(11) unsigned NOT NULL default '0',
  `position` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`faq_category_article_id`),  
  KEY (`category_id`),
  KEY (`article_id`),
  CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$this->getTable('faq_categories')} (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT FOREIGN KEY (`article_id`) REFERENCES {$this->getTable('faq_articles')} (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	");

$installer->endSetup();
