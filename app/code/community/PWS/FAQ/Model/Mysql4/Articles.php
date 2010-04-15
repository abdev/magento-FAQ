<?php
class PWS_FAQ_Model_Mysql4_Articles extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('pws_faq/articles','article_id');
    } 
    
    
}

