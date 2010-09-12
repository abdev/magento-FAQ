<?php
class PWS_FAQ_Model_Mysql4_Articles_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    private $_storeId;
    
    
    public function _construct()
    {
        $this->_init('pws_faq/articles');
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
    
    
    public function filterByCategory($category_id, $joinType = 'join')
    {               
		if($joinType == 'left'){
			$this->joinLeftCategory($category_id);
		}else{
			$this->joinCategory($category_id);
		}		       

        return $this;
    }
    
    private function joinLeftCategory($category_id)
    {               
        $this->getSelect()->joinLeft(
            		array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
            		'`main_table`.`article_id`=`categories_articles`.`article_id` AND `categories_articles`.`category_id`='.(int)$category_id,
            		array('position')
        		);

        return $this;
    }
    
    private function joinCategory($category_id)
    {               
        $this->getSelect()->join(
            array('categories_articles' => $this->getTable('pws_faq/categoriesArticles')),
            		'`main_table`.`article_id`=`categories_articles`.`article_id` AND `categories_articles`.`category_id`='.(int)$category_id,
            		array('position')
        );

        return $this;
    }
    
    
    public function getCategoryEnabledArticles($categoryId)
    {
        $categoriesArticlesTable = Mage::getSingleton('core/resource')->getTableName('pws_faq/categoriesArticles');
        
        $this->getSelect()
            ->join(array('categories_articles' => $categoriesArticlesTable),
            'main_table.article_id = categories_articles.article_id 
            AND categories_articles.category_id = '.$categoryId
            )
            ->where('main_table.status = ?','enabled')
            ->order('categories_articles.position', 'ASC');
        
        return $this;
    }
    
    
    
    protected function _addStoreData()
    {
        $resource = Mage::getSingleton('core/resource');
        $articleStoresTable = $resource->getTableName('pws_faq/articles_stores');
                
        if($this->getStoreId()) {
            $this->getSelect()->joinLeft(
                    array('article_store_table' => $articleStoresTable),
                    'article_store_table.article_id=main_table.article_id
                     AND article_store_table.store_id = '.$this->getStoreId(),
                   array('store_id'=>'article_store_table.store_id', 'views'=>'article_store_table.views')
                )
                ->joinLeft(
                    array('article_default_store_table' => $articleStoresTable),
                    'article_default_store_table.article_id=main_table.article_id
                     AND article_default_store_table.store_id = 0',
                   array('')
                )
                ->from("",array(
                            'title_default' => "article_default_store_table.title",
                            'title_store' => "article_store_table.title",
                            'title' => new Zend_Db_Expr('IFNULL(article_store_table.title,article_default_store_table.title)'),
                            'content_default' => "article_default_store_table.content",
                            'content_store' => "article_store_table.content",
                            'content' => new Zend_Db_Expr('IFNULL(article_store_table.content,article_default_store_table.content)'),
                            'updated_default' => "article_default_store_table.content",
                            'updated_store' => "article_store_table.content",
                            'updated_on' => new Zend_Db_Expr('IFNULL(article_store_table.updated_on,article_default_store_table.updated_on)')
                            )
            );
        
        } else {        
            $this->getSelect()->joinLeft(
                    array('article_default_store_table' => $articleStoresTable),
                    'article_default_store_table.article_id=main_table.article_id
                     AND article_default_store_table.store_id = '.$this->getStoreId(),
                   array('store_id'=>'article_default_store_table.store_id')
                )               
                ->from("",array(
                            'title' => "article_default_store_table.title",
                            'content' => "article_default_store_table.content",
                            'views' => "article_default_store_table.views",
                            'updated_on' => "article_default_store_table.updated_on",
                            )
            );
        }
        
        //echo $this->getSelect()->__toString();
        
        return $this;
    }
    
    
    
}
