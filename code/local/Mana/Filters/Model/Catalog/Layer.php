<?php

class Mana_Filters_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
   
    public function getProductCollection()
    {
        $collection = parent::getProductCollection();

		/*PRICE SLIDER FILTER*/
		$max = Mage::app()->getRequest()->getParam('max');
		$min = Mage::app()->getRequest()->getParam('min');
		
		if($min && $max){
			$collection->getSelect()->where(' final_price >= "'.$min.'" AND final_price <= "'.$max.'" ');
		}
		
		/*PRICE SLIDER FILTER*/
		
        return $collection;
    }
    
}