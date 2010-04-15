<?php
class PWS_FAQ_Model_CategoriesArticles extends Mage_Core_Model_Abstract 
{
    protected function _construct()
    {
        $this->_init('pws_faq/categoriesArticles');
    } 
    
    public function saveCategoryArticles($category_id, $data)
    {
    	if($data){
    		$this->_getResource()->saveCategoryArticles($category_id, $data);
    	}
    	return $this;
    }
    
    public function removeCategoryArticles($category_id, $data)
    {
    	if($data){
    		$this->_getResource()->removeCategoryArticles($category_id, $data);
    	}
    	return $this;
    }
    
    
    
      
}
