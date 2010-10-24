<?php
class PWS_FAQ_Model_Mysql4_Articles extends Mage_Core_Model_Mysql4_Abstract
{
    private $_storeId;
    
    protected function _construct()
    {
        $this->_init('pws_faq/articles','article_id');
    }
    
    function setStoreId($storeId) {
        $this->_storeId = $storeId;
        return $this;
    }
    
    public function getStoreId()
    {
        if (is_null($this->_storeId)) {
            return Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }     
    
    /*
    *  save store view data: title and content in the corresponding table
    */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
       
        $storeArray['store_id'] = $object->getData('store_id');
        $storeArray['article_id'] = $object->getData('article_id');
        $storeArray['title'] = $object->getData('title');
        $storeArray['content'] = $object->getData('content'); 
        $storeArray['use_default'] = $object->getData('use_default');
        
        if ($object->getData('views')) {
            $storeArray['views'] = $object->getData('views');
        }
        
        if ($object->getData('store_id') == 0) {
            $storeArray['use_default'] = 1;//always 1 for the default view
        }
        
        //get default values
        if($object->getData('use_default') == 1 && $object->getData('store_id') != 0) {
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('pws_faq/articles_stores'))
                ->where('article_id = ?', $object->getId())
                ->where('store_id = ?', 0);

            if ($data = $this->_getReadAdapter()->fetchRow($select)) {
                $storeArray['title'] = $data['title'];
                $storeArray['content'] = $data['content'];
                $storeArray['use_default'] = 1;
            } 
        }
        
        //remove previous data
        $condition1 = $this->_getWriteAdapter()->quoteInto('article_id = ?', $storeArray['article_id']);
        $condition2 = $this->_getWriteAdapter()->quoteInto('store_id = ?', $storeArray['store_id']);
        $condition = $condition1.' and '.$condition2;
        $this->_getWriteAdapter()->delete($this->getTable('pws_faq/articles_stores'), $condition);
        
        $todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        
        $storeArray['updated_on'] = $todayDate;
               
        $this->_getWriteAdapter()->insert($this->getTable('pws_faq/articles_stores'), $storeArray);
       
    }
    
    /*
    *  get store view data: title and content from the corresponding table
    */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {   
        $object->setData('store_id', $object->getStoreId());               
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('pws_faq/articles_stores'))
            ->where('article_id = ?', $object->getId())
            ->where('store_id = ?', $object->getData('store_id'));

        if ($data = $this->_getReadAdapter()->fetchRow($select)) {
            $object->setData('title', $data['title']);
            $object->setData('content', $data['content']);
            $object->setData('updated_on', $data['updated_on']);
            $object->setData('views', $data['views']);
            $object->setData('use_default', $data['use_default']);
        } 
        
        //use default record
        if($object->getStoreId() != 0 && (empty($data) || $data['use_default'] == 1)) {
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('pws_faq/articles_stores'))
                ->where('article_id = ?', $object->getId())
                ->where('store_id = ?', 0);

            if ($data = $this->_getReadAdapter()->fetchRow($select)) {
                $object->setData('title', $data['title']);
                $object->setData('content', $data['content']);
                $object->setData('updated_on', $data['updated_on']);
                $object->setData('use_default', $data['use_default']);
            } 
        }
       

        return parent::_afterLoad($object);
    }
   
}

