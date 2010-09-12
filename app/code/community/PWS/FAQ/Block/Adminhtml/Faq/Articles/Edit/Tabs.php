<?php
class PWS_FAQ_Block_Adminhtml_Faq_Articles_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('faq_articles_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('pws_faq')->__('Article Information'));
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
            'label'     => Mage::helper('pws_faq')->__('Article Information'),
            'title'     => Mage::helper('pws_faq')->__('Article Information'),
            'content'   => $this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit_tab_form')->toHtml(),
        ));
        
        $this->addTab('faq_categories', array(
                'label'     => Mage::helper('pws_faq')->__('Set Categories'),
                'title'     => Mage::helper('pws_faq')->__('Set Categories'),
                'url'       => $this->getUrl('*/faqCategories/categories', array('_current' => true)),
                'class'     => 'ajax',                
        ));
        return parent::_beforeToHtml();
    }
}
