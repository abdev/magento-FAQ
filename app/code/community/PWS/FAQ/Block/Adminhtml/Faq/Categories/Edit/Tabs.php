<?php
class PWS_FAQ_Block_Adminhtml_Faq_Categories_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('faq_categories_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('pws_faq')->__('Category Information'));
    }
    
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')
            ->addJs('pws/faq/productLink.js');

        parent::_prepareLayout();
    }
   

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('pws_faq')->__('Category Information'),
            'title'     => Mage::helper('pws_faq')->__('Category Information'),
            'content'   => $this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_tab_form')->toHtml(),
        ));
        
        $this->addTab('faq_categories_articles', array(
                'label'     => Mage::helper('pws_faq')->__('Set Articles'),
                'title'     => Mage::helper('pws_faq')->__('Set Articles'),
                'url'       => $this->getUrl('*/faqArticles/articles', array('_current' => true)),
                'class'     => 'ajax',                
        ));
        return parent::_beforeToHtml();
    }
}
