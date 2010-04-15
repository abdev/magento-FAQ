<?php
class PWS_FAQ_Block_Adminhtml_Faq_Categories_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = 'faqCategories';

        $this->_updateButton('save', 'label', Mage::helper('pws_faq')->__('Save Category'));
        $this->_updateButton('delete', 'label', Mage::helper('pws_faq')->__('Delete Category'));

        if( $this->getRequest()->getParam($this->_objectId) ) {

            $faqCategoryData = Mage::getModel('pws_faq/categories')->load($this->getRequest()->getParam($this->_objectId));

            Mage::register('faq_category_data', $faqCategoryData);
        }


    }

    public function getHeaderText()
    {
        if( Mage::registry('faq_category_data') && Mage::registry('faq_category_data')->getId() ) {
            return Mage::helper('pws_faq')->__("Edit Category", $this->htmlEscape(Mage::registry('faq_category_data')->getName()));
        } else {
            return Mage::helper('pws_faq')->__('New Category');
        }
    }
    
    
    /*
    * Overrided method because the way the name of the block form is constructed is wrong for local/community modules
    * Eg: $this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form' => adminhtml/faqCategories_edit_form
    * we need 'pws_faq/adminhtml_faq_categories_edit_form'
    */    
    protected function _prepareLayout()
    { 
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_form'));
        }
        return parent::_prepareLayout();
    }
}
