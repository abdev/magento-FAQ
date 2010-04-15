<?php
class PWS_FAQ_Block_View_Article extends Mage_Core_Block_Template
{
    public function getArticle()
    {
    	return Mage::registry('current_article');
    }
    
    
    public function getBackUrl()
    {
    	return Mage::getUrl('faq/');
    }
   
   
    protected function _prepareLayout()
    {
        
        $article = $this->getArticle();
        $title = $article->getTitle();

        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            
            $breadcrumbs->addCrumb('home', array(
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            ))->addCrumb('faq_categories', array(
                'label' => Mage::helper('pws_faq')->getFAQTitle(),
                'title' => Mage::helper('pws_faq')->getFAQTitle(),
                'link'  => Mage::getUrl('faq/'),
            ))->addCrumb('faq_article', array(
                'label' => $title,
                'title' => Mage::helper('pws_faq')->getFAQTitle().' - '.$title,
            ));
        }
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('pws_faq')->getFAQTitle().' - '.$title);

        return parent::_prepareLayout();
    }

   
}
