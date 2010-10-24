<?php
class PWS_FAQ_Model_Mysql4_Categories_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    private $_storeId;
    
    public function _construct()
    {
        $this->_init('pws_faq/categories');
    }
    
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
    }
    
    public function getStoreId()
    {
        if(is_null($this->_storeId)) {
            $this->_storeId = $this->getResource()->getStoreId();
        }
        
        return $this->_storeId;
    }
    
    /*
    * overrided to add store data
    */
    protected function _initSelect()
    {
         $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()));
         $this->_addStoreData();
         
         return $this;
    }
   
    
    public function filterByArticle($category_id, $joinType = 'join')
    {               
		if($joinType == 'left'){
			$this->joinLeftArticle($category_id);
		}else{
			$this->joinArticle($category_id);
		}		       

        return $this;
    }
    
    private function joinLeftArticle($article_id)
    {               
        $this->getSelect()->joinLeft(
            		array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
            		'`main_table`.`category_id`=`categories_articles`.`category_id` AND `categories_articles`.`article_id`='.(int)$article_id,
            		array('position')
        		);

        return $this;
    }
    
    private function joinArticle($article_id)
    {               
        $this->getSelect()->join(
            array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
            		'`main_table`.`category_id`=`categories_articles`.`category_id` AND `categories_articles`.`article_id`='.(int)$article_id,
            		array('position')
        );

        return $this;
    }
    
    /*
    *  add the number of articles to category collection
    */
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
    
   
   
    
    /*
    *  get only the categories with enabled articles
    */
    public function addFilterEnabledArticles()
    {  
        $this->getSelect()
        	->join(
		        array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`main_table`.category_id=`categories_articles`.category_id',
		        		array('no_of_articles' => 'COUNT(`categories_articles`.faq_category_article_id)','position' => 'position')
		    	)
		    ->join(
		        array('articles' => $this->getTable('pws_faq/categoriesArticles')),
		        		'`categories_articles`.article_id=`articles`.article_id',
		        		array()
		    	)
        	->group('main_table.category_id')
        	->order('name', 'ASC');
        	
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
    
    
    protected function _addStoreData()
    {
        $resource = Mage::getSingleton('core/resource');
        $categoriesStoresTable = $resource->getTableName('pws_faq/categories_stores');
                
        if($this->getStoreId()) {
            $this->getSelect()->joinLeft(
                    array('category_store_table' => $categoriesStoresTable),
                    'category_store_table.category_id=main_table.category_id
                     AND category_store_table.store_id = '.$this->getStoreId(),
                   array('store_id'=>'category_store_table.store_id')
                )
                ->joinLeft(
                    array('category_default_store_table' => $categoriesStoresTable),
                    'category_default_store_table.category_id=main_table.category_id
                     AND category_default_store_table.store_id = 0',
                   array('')
                )
                ->from("",array(
                            'name_default' => "category_default_store_table.name",
                            'name_store' => "category_store_table.name",
                            'name' => new Zend_Db_Expr('IF(category_store_table.name IS NULL || category_store_table.use_default =1,
                                                            category_default_store_table.name,
                                                            category_store_table.name)'),
                            'description_default' => "category_default_store_table.description",
                            'description_store' => "category_store_table.description",
                            'description' => new Zend_Db_Expr('IF(category_store_table.description IS NULL || category_store_table.use_default =1, 
                                                                category_default_store_table.description,
                                                                category_store_table.description
                                                                )')
                            )
            );
        
        } else {        
            $this->getSelect()->joinLeft(
                     array('category_store_table' => $categoriesStoresTable),
                    'category_store_table.category_id=main_table.category_id
                     AND category_store_table.store_id = '.$this->getStoreId(),
                   array('store_id'=>'category_store_table.store_id')
                )               
                ->from("",array(
                            'name' => "category_store_table.name",
                            'description' => "category_store_table.description",
                            )
            );
        }
        
        //echo $this->getSelect()->__toString();
        
        return $this;
    }
    
    
}
