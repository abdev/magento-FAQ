<?php
class PWS_FAQ_Model_Articles extends Mage_Core_Model_Abstract 
{
    protected function _construct()
    {
        $this->_init('pws_faq/articles');
    }
    
    public function isNotAllowed()
    {
    	if($this->getStatus() == 'enabled') return false;
    	return true;
    }
    
}
