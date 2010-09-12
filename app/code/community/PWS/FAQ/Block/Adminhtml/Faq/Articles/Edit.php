<?php
class PWS_FAQ_Block_Adminhtml_Faq_Articles_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'faqArticles';

        $this->_updateButton('save', 'label', Mage::helper('pws_faq')->__('Save Article'));
        $this->_updateButton('delete', 'label', Mage::helper('pws_faq')->__('Delete Article'));
        
        $this->_addButton('savecontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save and Continue Editing'),
            'onclick' => "$('edit_form').action += 'continue/true/'; editForm.submit();",
            'class' => 'save',
        ), -1);
    }

    public function getHeaderText()
    {
        if( Mage::registry('faq_article') && Mage::registry('faq_article')->getId() ) {
            return Mage::helper('pws_faq')->__("Edit Article");
        } else {
            return Mage::helper('pws_faq')->__('New Article');
        }
    }
    
    
    /*
    * Overrided method because the way the name of the block form is constructed is wrong for local/community modules
    * Eg: $this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form' => adminhtml/faqArticles_edit_form
    * we need 'pws_faq/adminhtml_faq_articles_edit_form'
    */    
    protected function _prepareLayout()
    { 
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit_form'));
        }
        return parent::_prepareLayout();
    }
}
