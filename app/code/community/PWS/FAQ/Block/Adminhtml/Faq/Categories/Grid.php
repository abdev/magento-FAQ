<?php
class PWS_FAQ_Block_Adminhtml_Faq_Categories_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('faqCategoriesGrid');
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    { 
        $collection = Mage::getModel('pws_faq/categories')->getCollection()->addArticles();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
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
        
        $this->addColumn('no_of_articles', array(
            'header'    => Mage::helper('pws_faq')->__('Articles #'),
            'align'     =>'left',
            'index'     => 'no_of_articles',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
