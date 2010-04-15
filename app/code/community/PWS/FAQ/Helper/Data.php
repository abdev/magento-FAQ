<?php
class PWS_FAQ_Helper_Data extends Mage_Core_Helper_Abstract
{	

	public function showCategoriesWithoutArticles()
	{	
		return Mage::getStoreConfig('pws_faq/general/show_category_without_any_articles');
	}
	
	public function getFAQTitle()
	{
		return (trim(Mage::getStoreConfig('pws_faq/general/title'))=='')? $this->__('FAQ'): Mage::getStoreConfig('pws_faq/general/title');
	} 

}
