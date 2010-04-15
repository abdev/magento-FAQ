<?php
class PWS_FAQ_Model_Categories extends Mage_Core_Model_Abstract 
{
    protected function _construct()
    {
        $this->_init('pws_faq/categories');
    }
    
    public function getArticles()
    {    	
    	$faqCategoriesArticlesCollection = Mage::getModel('pws_faq/categoriesArticles')->getCollection()
    				->addFieldToFilter('category_id',$this->getId()); 
    				
        return  $faqCategoriesArticlesCollection;     
    } 
    
    public function getArticlesIds()
    {
    	$articleIds = array();
    	
    	$categoryArticles = $this->getArticles();
    	if($categoryArticles){
    		foreach($categoryArticles as $article){   
    			$articleIds[] = $article->getArticleId();
    		}
    	}    	
    	
    	return $articleIds;
    }  
    
    
    public function getEnabledArticles()
    {
    	$faqCategoriesArticlesCollection = Mage::getModel('pws_faq/categoriesArticles')->getCollection()    				
    				->join('pws_faq/articles','main_table.article_id = `pws_faq/articles`.article_id')
    				->addFieldToFilter('category_id',$this->getId())
    				->addFieldToFilter('`pws_faq/articles`.status','enabled')
    				->addOrder('position','ASC'); 
    				
        return  $faqCategoriesArticlesCollection; 
    }
}
