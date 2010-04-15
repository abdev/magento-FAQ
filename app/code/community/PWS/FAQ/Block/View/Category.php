<?php
class PWS_FAQ_Block_View_Category extends Mage_Core_Block_Template
{
       
        
    public function getCategory()
    {
    	return Mage::registry('current_article');
    }
    
    
    public function getCategoryUrl($category_id)
    {
    	return Mage::getUrl('faq/index/view/category',array('category_id'=>$category_id));
    }
    
    
    protected function _prepareLayout()
    {
        
        $category = $this->getCategory();
        $title = $category->getName();

        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('faq_category', array(
                'label' => $title,
                'title' => Mage::helper('pws_faq')->getFAQTitle().' - '.$title,
            ));
        }
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('pws_faq')->getFAQTitle().' - '.$title);

        return parent::_prepareLayout();
    }

   
}
