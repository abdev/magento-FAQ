<?php
class PWS_FAQ_Block_Adminhtml_Faq_Categories_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('faq_categories_form', array(
            'legend'=>Mage::helper('pws_faq')->__('Category Information')
        ));
        
        $fieldset->addField('store_id', 'hidden', array(
            'name'      => 'faq_category[store_id]',
            'label'     => '',
            'class'     => 'required-entry',
            'required'  => true
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'faq_category[name]',
            'label'     => Mage::helper('pws_faq')->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'note' => Mage::helper('pws_faq')->__('scope: [STORE VIEW]'),

        ));
        
        $fieldset->addField('description', 'textarea', array(
            'name'      => 'faq_category[description]',
            'label'     => Mage::helper('pws_faq')->__('Description'),
            'note' => Mage::helper('pws_faq')->__('scope: [STORE VIEW]'),
        ));
        
     

		if(Mage::registry('faq_category')){			
        	$form->setValues(Mage::registry('faq_category')->getData());
        }
        return parent::_prepareForm();
    }

    

    protected function _toHtml()
    {
        return parent::_toHtml();
    }


}
