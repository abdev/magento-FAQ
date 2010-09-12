<?php
class PWS_FAQ_Block_Adminhtml_Faq_Articles_Edit_Tab_Articles extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('faq_articles_grid');
        $this->setDefaultSort('position');
        $this->setUseAjax(true);
        
        //filter articles by category
        if ($this->_getFAQCategory()->getId()) {
            $this->setDefaultFilter(array('in_articles'=>1));
        }
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getFAQCategory()
    {
        return Mage::registry('current_faq_category');
    }

    protected function _addColumnFilterToCollection($column)
    {
       
        // Set custom filter for in articles flag
        if ($column->getId() == 'in_articles') {
        	
            $articleIds = $this->_getSelectedArticles();
            if (empty($articleIds)) {
                $articleIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.article_id', array('in'=>$articleIds));
            }
            else {
                if($articleIds) {
                    $this->getCollection()->addFieldToFilter('main_table.article_id', array('nin'=>$articleIds));
                }
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    { 
        if(Mage::registry('faq_category')->getId()){
		    $collection = Mage::getModel('pws_faq/articles')->getCollection()->filterByCategory(Mage::registry('faq_category')->getId(), $joinType = 'left');	
		    
		    //var_dump($collection->toArray());	   
		}else{
			$collection = Mage::getModel('pws_faq/articles')->getCollection()->filterByCategory(Mage::registry('faq_category')->getId(), $joinType = 'left');;
		}
        

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return false;
    }

    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn('in_articles', array(
                'header_css_class' => 'a-center',
                'type'      => 'checkbox',
                'name'      => 'in_articles',
                'values'    => $this->_getSelectedArticles(),
                'align'     => 'center',
                'index'     => 'article_id'
            ));
        }
        
        $this->addColumn('article_id', array(
            'header'    => Mage::helper('pws_faq')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'article_id',
        ));

         $this->addColumn('title', array(
            'header'    => Mage::helper('pws_faq')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));
        
              
        $this->addColumn('status', array(
            'header'    => Mage::helper('pws_faq')->__('Status'),
            'align'     =>'left',
            'index'     => 'status',
        ));
        
        $this->addColumn('updated_on', array(
            'header'    => Mage::helper('pws_faq')->__('Updated On'),
            'align'     =>'left',
            'index'     => 'updated_on',
            'type'		=> 'datetime'
        ));
        

        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Position'),
            'name'      => 'position',
            'width'     => '60px',
            'type'      => 'number',
            'validate_class' => 'validate-number',
            'index'     => 'position',
            'editable'  => !$this->isReadonly(),
            'edit_only' => !$this->_getFAQCategory()->getId()
        ));



        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/faqArticles/articles', array('_current'=>true));
    }

    protected function _getSelectedArticles()
    {
        $articles = $this->getRequest()->getPost('articles', null);
        if (!is_array($articles)) {        	
            $articles = $this->_getFAQCategory()->getArticlesIds();
        }
        return $articles;
    }
    
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
}

