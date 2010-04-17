<?php
class PWS_FAQ_Model_Mysql4_Categories_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    public function _construct()
    {
        $this->_init('pws_faq/categories');
    }
    
    public function addArticles()
    {               
        $this->getSelect()
        	->joinLeft(
		        array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`main_table`.category_id=`categories_articles`.category_id',
		        		array('no_of_articles' => 'COUNT(faq_category_article_id)')
		    	)
        	->group('main_table.category_id');

        return $this;
    }
    
   
    public function addEnabledArticles()
    {               
        $this->getSelect()
        	->joinLeft(
		        array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`main_table`.category_id=`categories_articles`.category_id',
		        		array('no_of_articles' => 'COUNT(faq_category_article_id)')
		    	)
		    ->joinLeft(
		        array('articles' => $this->getTable('pws_faq/articles')),
		        		'`categories_articles`.article_id=`articles`.article_id',
		        		array()
		    	)
		    ->where('`articles`.status = "enabled" or `articles`.status IS NULL')
        	->group('main_table.category_id');

        return $this;
    }
    
    
    public function addOnlyWithEnabledArticles()
    {               
        $this->getSelect()
        	->join(
		        array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`main_table`.category_id=`categories_articles`.category_id',
		        		array('no_of_articles' => 'COUNT(`categories_articles`.faq_category_article_id)','positon' => 'position')
		    	)
		    ->join(
		        array('articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`categories_articles`.article_id=`articles`.article_id',
		        		array()
		    	)
        	->group('main_table.category_id');

        return $this;
    }
    
    
    /*
    *	Override getSelectCountSql() because we have to remove grouping and join 
    *	(see getSize() in Varien_Data_Collection_Db)
    */
    public function getSelectCountSql()
    {
        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        
        
        $countSelect->reset(Zend_Db_Select::GROUP);        
        $countSelect->resetJoinLeft();

        $countSelect->from('', 'count(*)');
        
        //var_dump($countSelect->__toString());
        
        return $countSelect;
    }
    
    
}
