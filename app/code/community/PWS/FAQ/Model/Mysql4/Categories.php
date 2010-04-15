<?php
class PWS_FAQ_Model_Mysql4_Categories extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('pws_faq/categories','category_id');
    } 
    
    
}

