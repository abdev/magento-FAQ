<?php
class PWS_FAQ_Block_Adminhtml_Faq_Categories_Edit_Tab_Categories extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('faq_categories_grid');
        $this->setDefaultSort('position');
        $this->setUseAjax(true);
        
        //filter categories by article
        if ($this->_getFAQArticle()->getId()) {
            $this->setDefaultFilter(array('in_categories'=>1));
        }
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getFAQArticle()
    {
        return Mage::registry('current_faq_article');
    }

    protected function _addColumnFilterToCollection($column)
    {
       
        // Set custom filter for in categories flag
        if ($column->getId() == 'in_categories') {
        	
            $categoryIds = $this->_getSelectedCategories();
            if (empty($categoryIds)) {
                $categoryIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.category_id', array('in'=>$categoryIds));
            }
            else {
                if($categoryIds) {
                    $this->getCollection()->addFieldToFilter('main_table.category_id', array('nin'=>$categoryIds));
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
        if(Mage::registry('faq_article')->getId()){
		    $collection = Mage::getModel('pws_faq/categories')->getCollection()->filterByArticle(Mage::registry('faq_article')->getId(), $joinType = 'left');	
		}else{
			$collection = Mage::getModel('pws_faq/categories')->getCollection();
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
            $this->addColumn('in_categories', array(
                'header_css_class' => 'a-center',
                'type'      => 'checkbox',
                'name'      => 'in_categories',
                'values'    => $this->_getSelectedCategories(),
                'align'     => 'center',
                'index'     => 'category_id'
            ));
        }
        
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('pws_faq')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'category_id',
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('pws_faq')->__('Category Name'),
            'align'     =>'left',
            'index'     => 'name',
        ));
        
      
        
        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Article Position in Category'),
            'name'      => 'position',
            'width'     => '60px',
            'type'      => 'number',
            'validate_class' => 'validate-number',
            'index'     => 'position',
            'editable'  => !$this->isReadonly(),
            'edit_only' => !$this->_getFAQArticle()->getId()
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/faqCategories/categories', array('_current'=>true));
    }

    protected function _getSelectedCategories()
    {
        $categories = $this->getRequest()->getPost('categories', null);
        if (!is_array($categories)) {        	
            $categories = $this->_getFAQArticle()->getCategoriesIds();
        }
        return $categories;
    }
   
}

