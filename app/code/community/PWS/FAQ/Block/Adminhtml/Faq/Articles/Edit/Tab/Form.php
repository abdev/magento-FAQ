<?php
class PWS_FAQ_Block_Adminhtml_Faq_Articles_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('faq_articles_form', array(
            'legend'=>Mage::helper('pws_faq')->__('Article Information')
        ));
        
        $fieldset->addField('store_id', 'hidden', array(
            'name'      => 'faq_article[store_id]',
            'label'     => '',
            'class'     => 'required-entry',
            'required'  => true
        ));

        $fieldset->addField('title', 'text', array(
            'name'      => 'faq_article[title]',
            'label'     => Mage::helper('pws_faq')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'note' => Mage::helper('pws_faq')->__('scope: [STORE VIEW]'),
        ));        
        
        $fieldset->addField('content', 'textarea', array(
            'name'      => 'faq_article[content]',
            'label'     => Mage::helper('pws_faq')->__('Content'),
            'class'     => 'required-entry',
            'required'  => true,
            'note' => Mage::helper('pws_faq')->__('scope: [STORE VIEW]'),
        ));
        
        $fieldset->addField('status', 'select', array(
                'label'     => Mage::helper('pws_faq')->__('Status'),
                'name'      => 'faq_article[status]',
                'value'		=> 'enabled',
                'values'    => array(
                	array('value'=>'enabled','label'=>'Enabled'),
                	array('value'=>'disabled','label'=>'Disabled')
                ),
                'note' => Mage::helper('pws_faq')->__('scope: [GLOBAL]'),
            ));

      
		if(Mage::registry('faq_article')){			
        	$form->setValues(Mage::registry('faq_article')->getData());
        }
        return parent::_prepareForm();
    }

    

    protected function _toHtml()
    {
        return parent::_toHtml();
    }


}
