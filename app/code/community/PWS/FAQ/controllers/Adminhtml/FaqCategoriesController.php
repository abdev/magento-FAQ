<?php
class PWS_FAQ_Adminhtml_FaqCategoriesController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    { 
               
        $this->loadLayout();

        $this->_setActiveMenu('cms/pws_faq');
        $this->_addBreadcrumb(Mage::helper('pws_faq')->__('Manage Categories'), Mage::helper('pws_faq')->__('Manage Categories'));
        $this->_addContent($this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_list'));

        $this->renderLayout();
        
        
    }

    public function editAction()
    {       
        $this->loadLayout();
        
        $categoryId  = (int) $this->getRequest()->getParam('id');
        $faqCategory   = Mage::getModel('pws_faq/categories')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqCategory->load($categoryId);
        
        Mage::register('faq_category', $faqCategory);
        Mage::register('current_faq_category', $faqCategory);
        

        $this->_setActiveMenu('cms/pws_faq/categories');
        $this->_addBreadcrumb(Mage::helper('pws_faq')->__('Manage FAQ'), Mage::helper('pws_faq')->__('Manage FAQ Categories'));

        $this->_addContent($this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit'))
            ->_addLeft($this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_tabs'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {

        if ( $this->getRequest()->getPost() ) {
            try {
                $faqCategoriesModel = Mage::getModel('pws_faq/categories');
              
                $faq_category_data = $this->getRequest()->getParam('faq_category');
                $faqCategoriesModel->setName($faq_category_data['name'])
                      ->setDescription($faq_category_data['description'])
                      ->setId($this->getRequest()->getParam('id'))
                      ->save();
                      
                
                // the selected articles (are serialized in a hidden input)              
                $category_articles = $this->_decodeInput($this->getRequest()->getParam('faq_category_articles'));
                $category_articles = empty($category_articles)? 'empty':$category_articles;                
                
                                
                $categoryArticlesModel = Mage::getModel('pws_faq/categoriesArticles');
                $categoryArticlesModel->saveCategoryArticles($faqCategoriesModel->getId(), $category_articles);
                

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_faq')->__('Faq Category have been successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFaqCategoryData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFaqCategoryData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $faqCategoriesModel = Mage::getModel('pws_faq/categories');
                
                $faqCategoriesModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_faq')->__('FAQ Category have been successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    
    protected function _isAllowed()
    {	    
	    return true;
    }
    
    
    
	//------------------------------------------------------------------------------
	// 					HELPER FUNCTIONS
	//------------------------------------------------------------------------------
    
    /**
     * Create serializer block for a grid
     *
     * @param string $inputName
     * @param Mage_Adminhtml_Block_Widget_Grid $gridBlock
     * @param array $productsArray
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Ajax_Serializer
     */
    protected function _createSerializerBlock($inputName, Mage_Adminhtml_Block_Widget_Grid $gridBlock, $productsArray)
    {
        return $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_ajax_serializer')
            ->setGridBlock($gridBlock)
            ->setProducts($productsArray)
            ->setInputElementName($inputName)
        ;
    }
    
    /**
     * Output specified blocks as a text list
     */
    protected function _outputBlocks()
    {
        $blocks = func_get_args();
        $output = $this->getLayout()->createBlock('adminhtml/text_list');
        foreach ($blocks as $block) {
            $output->insert($block, '', true);
        }
        $this->getResponse()->setBody($output->toHtml());
    }
    
    protected function _decodeInput($encoded)
    {
        $data = array();
        parse_str($encoded, $data);
        foreach($data as $key=>$value) {
            parse_str(base64_decode($value), $data[$key]);
        }

        return $data;
    }
    
    
}    
