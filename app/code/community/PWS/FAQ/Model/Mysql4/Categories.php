<?php
class PWS_FAQ_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{
    private $_storeId;
    
    protected function _construct()
    {
        $this->_init('pws_faq/categories','category_id');
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
    *  save store view data: name and description in the corresponding table
    */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
       
        $storeArray['store_id'] = $object->getData('store_id');
        $storeArray['category_id'] = $object->getData('category_id');
        $storeArray['name'] = $object->getData('name');
        $storeArray['description'] = $object->getData('description'); 
        
        //remove previous data
        $condition1 = $this->_getWriteAdapter()->quoteInto('category_id = ?', $storeArray['category_id']);
        $condition2 = $this->_getWriteAdapter()->quoteInto('store_id = ?', $storeArray['store_id']);
        $condition = $condition1.' and '.$condition2;
        $this->_getWriteAdapter()->delete($this->getTable('pws_faq/categories_stores'), $condition);
        
               
        $this->_getWriteAdapter()->insert($this->getTable('pws_faq/categories_stores'), $storeArray);
       
    }
    
    /*
    *  get store view data: name and description from the corresponding table
    */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {   
        $object->setData('store_id', $object->getStoreId());               
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('pws_faq/categories_stores'))
            ->where('category_id = ?', $object->getId())
            ->where('store_id = ?', $object->getData('store_id'));

        if ($data = $this->_getReadAdapter()->fetchRow($select)) {
            $object->setData('name', $data['name']);
            $object->setData('description', $data['description']);
        } 
        
        //use default record
        if($object->getStoreId() != 0 && empty($data)) {
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('pws_faq/categories_stores'))
                ->where('category_id = ?', $object->getId())
                ->where('store_id = ?', 0);

            if ($data = $this->_getReadAdapter()->fetchRow($select)) {
                $object->setData('name', $data['name']);
                $object->setData('description', $data['description']);
            } 
        }

        return parent::_afterLoad($object);
    }    
    
    
}

