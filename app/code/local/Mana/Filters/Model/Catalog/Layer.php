<?php
class Mana_Filters_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
   
   
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = $this->getCurrentCategory()->getProductCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }
		
		/*PRICE SLIDER FILTER*/
		$max=$_GET['max'];
		$min=$_GET['min'];
		
		//print_r($collection->getData());
		
		if($min && $max){
			//$collection= $collection->addAttributeToFilter('price',array('from'=>$min, 'to'=>$max)); 
			$collection->getSelect()->where(' final_price >= "'.$min.'" AND final_price <= "'.$max.'" ');
			
			//echo $collection->getSelect();exit;
		}
		
		/*PRICE SLIDER FILTER*/
		
        return $collection;
    }

    
}
?>