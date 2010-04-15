<?php
class PWS_FAQ_Model_Mysql4_Articles_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    public function _construct()
    {
        $this->_init('pws_faq/articles');
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
    
    
    
}
