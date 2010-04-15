<?php
class PWS_FAQ_Adminhtml_FaqArticlesController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {         
        $this->loadLayout();

        $this->_setActiveMenu('cms/pws_faq');
        $this->_addBreadcrumb(Mage::helper('pws_faq')->__('Manage Articles'), Mage::helper('pws_faq')->__('Manage Articles'));
        $this->_addContent($this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_list'));

        $this->renderLayout();
    }

    public function editAction()
    {       
        $this->loadLayout();
        
        $articleId  = (int) $this->getRequest()->getParam('id');
        $faqArticle   = Mage::getModel('pws_faq/articles')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqArticle->load($articleId);
        
        Mage::register('faq_article', $faqArticle);
        Mage::register('current_faq_article', $faqArticle);
        

        $this->_setActiveMenu('cms/pws_faq/articles');
        $this->_addBreadcrumb(Mage::helper('pws_faq')->__('Manage FAQ'), Mage::helper('pws_faq')->__('Manage FAQ Articles'));

        $this->_addContent($this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit'))
            ->_addLeft($this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit_tabs'));
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
                $faqArticlesModel = Mage::getModel('pws_faq/articles');
              
              	$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
              
                $faq_article_data = $this->getRequest()->getParam('faq_article');
                $faqArticlesModel->setTitle($faq_article_data['title'])
                      ->setContent($faq_article_data['content'])
                      ->setStatus($faq_article_data['status'])
                      ->setId($this->getRequest()->getParam('id'))
                      ->setUpdatedOn($todayDate);
                if(!$this->getRequest()->getParam('id')){
                	$faqArticlesModel->setCreatedOn($todayDate);
                } 
                $faqArticlesModel->save();
                               
                //$product_set_products = $this->_decodeInput($this->getRequest()->getParam('product_set_products'));
                //$product_set_products = empty($product_set_products)? 'empty':$product_set_products;                
                
                //$productSetsProductsModel = Mage::getModel('pws_faq/productsetsproducts');
                //$productSetsProductsModel->saveSetProduct($productSetsModel->getId(), $product_set_products);
                

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_faq')->__('Faq Article have been successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFaqArticleData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFaqArticleData($this->getRequest()->getPost());
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
                $faqArticlesModel = Mage::getModel('pws_faq/articles');
                
                $faqArticlesModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pws_faq')->__('FAQ Article have been successfully deleted'));
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
    public function articlesAction()
    { 
		$categoryId  = (int) $this->getRequest()->getParam('id');
        $faqCategory   = Mage::getModel('pws_faq/categories')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqCategory->load($categoryId);
        
        Mage::register('faq_category', $faqCategory);
        Mage::register('current_faq_category', $faqCategory);       
		
		if(Mage::registry('faq_category')->getId()){     
			$faq_articles = Mage::getModel('pws_faq/articles')->getCollection()->filterByCategory(Mage::registry('faq_category')->getId());
        }else{
        	 $faq_articles = null;
        }    
        
        $gridBlock = $this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit_tab_articles')
        	->setGridUrl($this->getUrl('*/*/articlesGridOnly', array('_current' => true)));
        // holds the selected rows ids	
        $serializerBlock = $this->_createSerializerBlock('faq_category_articles', $gridBlock, $faq_articles);

        $this->_outputBlocks($gridBlock, $serializerBlock);
    }
    
    
    /**
     * Get specified tab grid (we don't add the serialize block); used for filtering/searching in grid
     */
    public function articlesGridOnlyAction()
    {
        $categoryId  = (int) $this->getRequest()->getParam('id');
        $faqCategory   = Mage::getModel('pws_faq/categories')->setStoreId($this->getRequest()->getParam('store', 0));
        $faqCategory->load($categoryId);
        
        Mage::register('faq_category', $faqCategory);
        Mage::register('current_faq_category', $faqCategory);      
        
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('pws_faq/adminhtml_faq_articles_edit_tab_articles')
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
