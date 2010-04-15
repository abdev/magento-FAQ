<?php
class PWS_RelatedProductSets_Model_Observer
{
    
    /**
     * Observes controller_action_postdispatch_adminhtml_catalog_product_action_attribute_save
     *
     * Save related product sets
     *
     * @param   Varien_Event_Observer $observer
     * @return  PWS_RelatedProductSets_Model_Observer
     */
    public function saveRelatedProductSetsMassAction(Varien_Event_Observer $observer)
    {  
		$controller_action = $observer->getControlerAction();
		
		//var_dump($observer->getControllerAction()->getRequest()->getParams());

		$product_set_id_add = $observer->getControllerAction()->getRequest()->getParam('product_set_id_add',null);
		$position_add = $observer->getControllerAction()->getRequest()->getParam('position_add',null);
		$product_set_id_remove = $observer->getControllerAction()->getRequest()->getParam('product_set_id_remove',null);

		$selectedProductsIds = Mage::getSingleton('adminhtml/session')->getProductIds();
		$store_id = (int) $observer->getControllerAction()->getRequest()->getParam('store', 0);
		
		// build data array
		$data_add =  array();
		if($product_set_id_add){
			for($i=0; $i<count($product_set_id_add);$i++){				
				$data_add[$product_set_id_add[$i]]['position'] = (isset($position_add[$i]))? $position_add[$i]: 0;
			}
		}
		
		$data_remove =  array();
		if($product_set_id_remove){
			for($i=0; $i<count($product_set_id_remove);$i++){				
				$data_remove[] = $product_set_id_remove[$i];
			}
		}

		//var_dump($data_add);
		//var_dump($data_remove);
		
		foreach ($selectedProductsIds as $_selectedProductId) {
			$selectedProduct = Mage::getModel('catalog/product');
			$selectedProduct->setData(array());
			$selectedProduct->setStoreId($store_id)
				->load($_selectedProductId)
				->setIsMassupdate(true)
				->setExcludeUrlRewrite(true);
				
			if (!$selectedProduct->getId()) {
				continue;
			}
			
			$relatedProductSetsModel = Mage::getModel('pws_relatedproductsets/relatedproductsets');  
			if($data_add){  
				$relatedProductSetsModel->saveRelatedSetProducts($selectedProduct->getId(), $data_add); 
			}
			
			if($data_remove){				
				$relatedProductSetsModel->removeRelatedSetProducts($selectedProduct->getId(), $data_remove); 
			}
		}
    }
    
    
    /**
     * Observes catalog_product_save_after
     *
     * Saves product related_product_sets
     *
     * @param  Varien_Event_Observer  $observer array($this→_eventObject => $this) 
     * @return PWS_RelatedProductSets_Model_Observer
     */
    public function saveRelatedProductSets($observer)
    {
    	$product = $observer->getEvent()->getProduct();  
    	if(!is_null($product->getData('related_product_sets'))){   
		    $related_product_sets = $this->_decodeInput($product->getData('related_product_sets'));
		    $relatedProductSetsModel = Mage::getModel('pws_relatedproductsets/relatedproductsets');
		    $related_product_sets = (empty($related_product_sets))? 'empty': $related_product_sets;
		    $relatedProductSetsModel->saveRelatedSetProducts($product->getId(), $related_product_sets); 
        } 
        return $this;
    }
    
    private function _decodeInput($encoded)
    {
        $data = array();
        parse_str($encoded, $data);
        foreach($data as $key=>$value) {
            parse_str(base64_decode($value), $data[$key]);
        }

        return $data;
    }
    
    
}
