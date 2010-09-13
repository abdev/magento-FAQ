<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();


$faqArticlesTable = $installer->getTable('faq_articles');
$faqCategoriesTable = $installer->getTable('faq_categories');

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('faq_categories_stores')};
CREATE TABLE {$this->getTable('faq_categories_stores')} (
  `category_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL default 0,
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  PRIMARY KEY (`category_id`, `store_id`),
  CONSTRAINT `FK_FAQ_CATEGORIES_ID` FOREIGN KEY (`category_id`) 
    REFERENCES {$this->getTable('faq_categories')} (`category_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$this->getTable('faq_articles_stores')};
CREATE TABLE {$this->getTable('faq_articles_stores')} (
  `article_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL default 0,
  `title` varchar(255) NOT NULL default '',
  `content`  text NOT NULL default '', 
  `views` int(11) unsigned default '0',
  `created_on` datetime,
  `updated_on` datetime,
  PRIMARY KEY (`article_id`,`store_id`),
  CONSTRAINT `FK_FAQ_ARTICLES_ID` FOREIGN KEY (`article_id`) 
    REFERENCES {$this->getTable('faq_articles')} (`article_id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$storesData = $installer->getConnection()->fetchAll("
    SELECT
        store_id
    FROM
        {$installer->getTable('core/store')}
");

$storeId = 0;

$queryInsertStoreArticles = "INSERT INTO {$installer->getTable('faq_articles_stores')}
   (`article_id`, `store_id`, `title`, `content`, `views`, `created_on`, `updated_on`)
SELECT
   article_id,
   {$storeId},
   title,
   content,
   views,  
   created_on,
   updated_on
FROM
   {$faqArticlesTable}";

$installer->run($queryInsertStoreArticles);

$queryInsertStoreCategories = "INSERT INTO {$installer->getTable('faq_categories_stores')}
   (`category_id`, `store_id`, `name`, `description`)
SELECT
   category_id,
   {$storeId},
   name,
   description
FROM
   {$faqCategoriesTable}";
   
$installer->run($queryInsertStoreCategories);   


   
$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'title'
);  

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'content'
);

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'views'
);

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'is_helpful'
);

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'is_not_helpful'
);

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'created_on'
);

$installer->getConnection()->dropColumn(
    $faqArticlesTable,
    'updated_on'
);




$installer->getConnection()->dropColumn(
    $faqCategoriesTable,
    'name'
);  

$installer->getConnection()->dropColumn(
    $faqCategoriesTable,
    'description'
);

$installer->endSetup();
