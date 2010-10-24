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
        
        if (Mage::getSingleton('adminhtml/session')->getFaqCategoryData()) {
            $faqCategory->setData(Mage::getSingleton('adminhtml/session')->getFaqCategoryData());
            Mage::getSingleton('adminhtml/session')->setFaqCategoryData(false);
        }
        
        Mage::register('store_id', $this->getRequest()->getParam('store', 0));
        Mage::register('faq_category', $faqCategory);
        Mage::register('current_faq_category', $faqCategory);
               

        $this->_setActiveMenu('cms/pws_faq/categories');
        $this->_addBreadcrumb(Mage::helper('pws_faq')->__('Manage FAQ'), Mage::helper('pws_faq')->__('Manage FAQ Categories'));

        $this->_addContent($this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit'))
            ->_addLeft($this->getLayout()->createBlock('adminhtml/store_switcher'))
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
              
                $faq_category_data = $this->getRequest()->getPost('faq_category');
                $faq_category_data['use_default'] = isset($faq_category_data['use_default'])? 1 : 0;
                $faqCategoriesModel->setName($faq_category_data['name'])
                      ->setDescription($faq_category_data['description'])
                      ->setId($this->getRequest()->getParam('id'))
                      ->setData('store_id', $faq_category_data['store_id'])
                      ->setUseDefault($faq_category_data['use_default']) 
                      ->save();
                      
                $storeId = (empty($faq_category_data['store_id']))? 0: $faq_category_data['store_id'];      
                
                // the selected articles (are serialized in a hidden input), null means the grid was not loaded  
                if(!is_null($this->getRequest()->getParam('faq_category_articles'))) {               
                    $category_articles = $this->_decodeInput($this->getRequest()->getParam('faq_category_articles'));
                    $category_articles = empty($category_articles)? 'empty':$category_articles;
                    
                    $categoryArticlesModel = Mage::getModel('pws_faq/categoriesArticles');
                    $categoryArticlesModel->saveCategoryArticles($faqCategoriesModel->getId(), $category_articles);
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_faq')->__('Faq Category have been successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFaqCategoryData(false);
                
                if ($this->getRequest()->getParam('continue')) {                                
                	$this->_redirect('*/*/edit', array('id' => $faqCategoriesModel->getId(), 'store'=> $storeId));
                	return;
                }	

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFaqCategoryData($this->getRequest()->getPost('faq_category'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'),  'store'=> $storeId));
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
    
    
    /**
     * Get articles grid and serializer block
     */
    public function categoriesAction()
    { 
		$articleId  = (int) $this->getRequest()->getParam('id');
        $faqArticle   = Mage::getModel('pws_faq/articles')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqArticle->load($articleId);
        
        Mage::register('faq_article', $faqArticle);
        Mage::register('current_faq_article', $faqArticle);       
		
		if(Mage::registry('faq_article')->getId()){     
			$faq_categories = Mage::getModel('pws_faq/categories')->setStoreId($this->getRequest()->getParam('store', 0))
			                    ->getCollection()->filterByArticle(Mage::registry('faq_article')->getId());
        }else{
        	 $faq_categories = null;
        }
        
        /*$block = 'pws_faq/adminhtml_faq_categories_edit_tab_categories';
        $block = Mage::getConfig()->getBlockClassName($block);
        var_dump($fileName = mageFindClassFile($block));
        
        var_dump($this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_tab_categories'));*/
        
        $gridBlock = $this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_tab_categories')
        	->setGridUrl($this->getUrl('*/*/categoriesGridOnly', array('_current' => true)));
        // holds the selected rows ids	
        $serializerBlock = $this->_createSerializerBlock('faq_category_articles', $gridBlock, $faq_categories);

        $this->_outputBlocks($gridBlock, $serializerBlock);
    }
    
    
    /**
     * Get specified tab grid (we don't add the serialize block); used for filtering/searching in grid
     */
    public function categoriesGridOnlyAction()
    {
        $articleId  = (int) $this->getRequest()->getParam('id');
        $faqArticle   = Mage::getModel('pws_faq/articles')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqArticle->load($articleId);
        
        Mage::register('faq_article', $faqArticle);
        Mage::register('current_faq_article', $faqArticle);  
        
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('pws_faq/adminhtml_faq_categories_edit_tab_categories')
                ->toHtml()
        );
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
