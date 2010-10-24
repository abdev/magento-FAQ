<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();


$faqArticlesStoreTable = $installer->getTable('faq_articles_stores');
$faqCategoriesStoreTable = $installer->getTable('faq_categories_stores');

$installer->getConnection()->addColumn($faqArticlesStoreTable, 'use_default', 'tinyint(1) default 1');
$installer->getConnection()->addColumn($faqCategoriesStoreTable, 'use_default', 'tinyint(1) default 1');

//set default 0 for articles with distinct title and content from the default one
$sql = "
    SELECT fas2.article_id, fas2.store_id
    FROM `{$faqArticlesStoreTable}` fas1
    JOIN `{$faqArticlesStoreTable}` fas2 ON fas1.article_id = fas2.article_id
    WHERE fas1.store_id = 0 AND fas2.store_id != 0
    AND (fas1.title != fas2.title
    OR fas1.content != fas2.content)
";
$articlesStoresData = $installer->getConnection()->fetchAll($sql);

if (is_array($articlesStoresData)) {   
    foreach ($articlesStoresData as $row) {
        $bind  = array(
         'use_default' => 0
        );
      
        $where = array(
            $installer->getConnection()->quoteInto('article_id=?', $row['article_id']),
            $installer->getConnection()->quoteInto('store_id=?', $row['store_id']),
        );
             
        //$installer->getConnection()->getProfiler()->setEnabled(true);
        $installer->getConnection()->update($faqArticlesStoreTable, $bind, $where);
        //print $installer->getConnection()->getProfiler()->getLastQueryProfile()->getQuery();
        //print_r($installer->getConnection()->getProfiler()->getLastQueryProfile()->getQueryParams());
        //$installer->getConnection()->getProfiler()->setEnabled(false);

    }
}

//set default 0 for categories with distinct name and description from the default one
$categoriesStoresData = $installer->getConnection()->fetchAll("
    SELECT fcs2.category_id, fcs2.store_id
    FROM `{$faqCategoriesStoreTable}` fcs1
    JOIN `{$faqCategoriesStoreTable}` fcs2 ON fcs1.category_id = fcs2.category_id
    WHERE fcs1.store_id = 0 AND fcs2.store_id != 0
    AND (fcs1.name != fcs2.name
    OR fcs1.description != fcs2.description)
");

if (is_array($categoriesStoresData)) {   
    foreach ($categoriesStoresData as $row) {
        $bind  = array(
         'use_default' => 0
        );
        
        $where = array(
            $installer->getConnection()->quoteInto('category_id=?', $row['category_id']),
            $installer->getConnection()->quoteInto('store_id=?', $row['store_id']),
        );
        
        $installer->getConnection()->update($faqCategoriesStoreTable, $bind, $where);
    }
}

$installer->endSetup();
