<?php
class PWS_FAQ_Block_List extends Mage_Core_Block_Template
{
       
        
    public function getCategories()
    {
    	
    	if(Mage::helper('pws_faq')->showCategoriesWithoutArticles()){
    		$collection = Mage::getModel('pws_faq/categories')->getCollection()->addArticles();
    	}else{
    		$collection = Mage::getModel('pws_faq/categories')->getCollection()->addFilterEnabledArticles();
    	}
    	
    	//echo $collection->getSelect()->__toString();
    	
    	return $collection;
    }
    
    
    public function getArticleUrl($article_id)
    {
    	return Mage::getUrl('faq/index/view_article',array('article_id'=>$article_id));
    }
    
    
    protected function _prepareLayout()
    {
        
        $article = $this->getArticle();
        $title = Mage::helper('pws_faq')->getFAQTitle();

        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('faq_categories', array(
                'label' => $title,
                'title' => $title
            ));
        }
        $this->getLayout()->getBlock('head')->setTitle($title);

        return parent::_prepareLayout();
    }

   
}
