<?php

class Mana_Filters_Model_Catalogsearch_Layer extends Mage_CatalogSearch_Model_Layer 
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
