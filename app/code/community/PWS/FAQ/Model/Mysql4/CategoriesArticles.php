<?php
class PWS_FAQ_Model_Mysql4_CategoriesArticles extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('pws_faq/categoriesArticles','category_article_id');
    } 
    
    
    
    public function saveCategoryArticles($category_id, $data = array())
    {
    	
    	//remove previous data
    	$deleteCondition = $this->_getWriteAdapter()->quoteInto('category_id=?', $category_id);
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
    	
    	//add new data
    	if(is_array($data)){
			foreach($data as $article_id=>$article_data){
				$this->_getWriteAdapter()->insert($this->getMainTable(), array(
		            'article_id'        => $article_id,
		            'category_id' => $category_id,
		            'position'      => $article_data['position']
		        ));
			}
    	}
    }
    
    
    public function removeCategoryArticles($category_id, $data = array())
    {
    	//remove data
    	if(is_array($data)){
			foreach($data as $article_id){
				$deleteCondition = $this->_getWriteAdapter()->quoteInto('category_id=?',$category_id);
        		$deleteCondition = $this->_getWriteAdapter()->quoteInto($deleteCondition.' AND  article_id=?',$article_id);
				
				
        		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
			}
    	}
    }
    
    
    
    public function saveArticleCategories($article_id, $data = array())
    {
    	
    	//remove previous data
    	$deleteCondition = $this->_getWriteAdapter()->quoteInto('article_id=?', $article_id);
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
    	
    	//add new data
    	if(is_array($data)){
			foreach($data as $category_id=>$category_data){
				$this->_getWriteAdapter()->insert($this->getMainTable(), array(
		            'article_id'        => $article_id,
		            'category_id' => $category_id,
		            'position'      => $category_data['position']
		        ));
			}
    	}
    }
    
    
    public function removeArticleCategories($article_id, $data = array())
    {
    	//remove data
    	if(is_array($data)){
			foreach($data as $category_id){
				$deleteCondition = $this->_getWriteAdapter()->quoteInto('article_id=?',$article_id);
        		$deleteCondition = $this->_getWriteAdapter()->quoteInto($deleteCondition.' AND  category_id=?',$category_id);
				
				
        		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
			}
    	}
    }
    
}

