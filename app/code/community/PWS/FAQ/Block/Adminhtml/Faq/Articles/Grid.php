<?php
class PWS_FAQ_Block_Adminhtml_Faq_Articles_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('faqArticlesGrid');
        //$this->setDefaultSort('title');
        //$this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    { 
        $storeId = $this->getRequest()->getParam('store', 0);
        
        $collection = Mage::getModel('pws_faq/articles')->setStoreId($storeId)
            ->getCollection(); 
                     
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
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
        
        $this->addColumn('views', array(
            'header'    => Mage::helper('pws_faq')->__('Views #'),
            'align'     =>'left',
            'index'     => 'views',
        )); 
        
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'store' => $this->getRequest()->getParam('store', 0)));
    }
    
   

}
