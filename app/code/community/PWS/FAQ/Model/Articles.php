<?php
class PWS_FAQ_Model_Articles extends Mage_Core_Model_Abstract 
{
    private $_storeId;
    
    protected function _construct()
    {
        $this->_init('pws_faq/articles');
    }
    
    public function isNotAllowed()
    {
    	if($this->getStatus() == 'enabled') return false;
    	return true;
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
           
       
    public function getCategoriesIds()
    {
    	$categoriesIds = array();
    	
    	$categoryArticles = Mage::getModel('pws_faq/categoriesArticles')->getCollection()
    				->addFieldToFilter('article_id',$this->getId()); 
    				
    	if($categoryArticles){
    		foreach($categoryArticles as $categoryArticle){   
    			$categoriesIds[] = $categoryArticle->getCategoryId();
    		}
    	}    	
    	
    	return $categoriesIds;
    }  
    
}
