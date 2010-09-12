<?php
class PWS_FAQ_IndexController extends Mage_Core_Controller_Front_Action
{

   //faq/
   public function indexAction()
   {
   		$this->loadLayout();
        $this->_initLayoutMessages('pws_faq/session');
               
        $this->renderLayout();
   }
   
   
   public function view_articleAction()
   {
   		        
        $articleId = (int) $this->getRequest()->getParam('article_id');
        $article = Mage::getModel('pws_faq/articles')->load($articleId);
        
               
        if (!$article->getArticleId() || $article->isNotAllowed()){
            $this->_forward('noRoute');
            return false;
        }
        
                
        Mage::register('current_article', $article);        
        
        // update number of views
        $articleViews = (int)$article->getViews()+1;
        $article->setData('views', $articleViews);
        $article->save();
              
        $this->loadLayout();
        $this->_initLayoutMessages('pws_faq/session');
        
        $this->renderLayout();
   }
   
   public function view_CategoryAction()
   {
   		        
        $articleId = (int) $this->getRequest()->getParam('article_id');
        $article = Mage::getModel('pws_faq/articles')->load($articleId);
        
               
        if (!$article->getArticleId() || $article->isNotAllowed()){
            $this->_forward('noRoute');
            return false;
        }
        
        Mage::register('current_article', $article);        
        
        // update number of views
        $articleViews = (int)$article->getViews()+1;
        $article->setViews($articleViews);
        $article->save();
              
        $this->loadLayout();
        $this->_initLayoutMessages('pws_faq/session');
        
        $this->renderLayout();
   }
}
