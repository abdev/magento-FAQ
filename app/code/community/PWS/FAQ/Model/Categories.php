<?php
class PWS_FAQ_Model_Categories extends Mage_Core_Model_Abstract 
{
    private $_storeId;
    
    protected function _construct()
    {
        $this->_init('pws_faq/categories');
    }
    
    function setStoreId($storeId) {
        $this->_storeId = $storeId;
        $this->getResource()->setStoreId($storeId);
        return $this;
    }
    
    public function getStoreId()
    {
        if (is_null($this->_storeId)) {
            return Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }    
    
       
    public function getArticlesIds()
    {
    	$articleIds = array();
    	
    	$categoryArticles = Mage::getModel('pws_faq/categoriesArticles')->getCollection()
    				->addFieldToFilter('category_id',$this->getId()); 
    				
    	if($categoryArticles){
    		foreach($categoryArticles as $article){   
    			$articleIds[] = $article->getArticleId();
    		}
    	}    	
    	
    	return $articleIds;
    }  
    
    /*
    * we use the articles collection so we can have article store view data
    */
    public function getEnabledArticles()
    {
    	$faqArticlesCollection = Mage::getModel('pws_faq/articles')->getCollection()->getCategoryEnabledArticles($this->getId());
    	
    	return $faqArticlesCollection;
    }
}
