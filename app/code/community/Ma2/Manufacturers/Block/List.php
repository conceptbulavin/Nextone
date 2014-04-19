<?php
/**
 * MagenMarket.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Edit or modify this file with yourown risk.
 *
 * @category    Extensions
 * @package     Ma2_Manufacturers Free
 * @copyright   Copyright (c) 2013 MagenMarket. (http://www.magenmarket.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/
/* $Id: List.php 2 2014-01-13 07:35:22Z linhnt $ */

class Ma2_Manufacturers_Block_List extends Mage_Catalog_Block_Product_Abstract {
  
  public function __construct()
  {
    parent::__construct();
    if (!$this->getTemplate()) {
      $this->setTemplate('ma2_manufacturers/default.phtml');
    }
  }
  
  public function getManufacturers()
  {
    if (is_null($this->_allManufacturers)){
      $manufacturers = Mage::getModel("manufacturers/system_config_source_manuid")->toOptionArray();
      if (!count($manufacturers)) return array();
      
      // included or excluded?
      if ($this->getIncluded() && trim($this->getIncluded()) != ''){
        $included = explode(',', str_replace(' ', '', $this->getIncluded()));
        $tmpManu = array();
        foreach($manufacturers as $manufacturer){
          if (in_array($manufacturer['value'], $included)) $tmpManu[] = $manufacturer;
        }
        
        unset($manufacturers);
        $manufacturers = $tmpManu;
        
      } else if ($this->getExcluded() && trim($this->getExcluded()) != ''){
        $excluded = explode(',', str_replace(' ', '', $this->getExcluded()));
        $tmpManu = array();
        foreach($manufacturers as $manufacturer){
          if (!in_array($manufacturer['value'], $included)) $tmpManu[] = $manufacturer;
        }
        
        unset($manufacturers);
        $manufacturers = $tmpManu;
      }
      
      
      $manu_code = Mage::getStoreConfig('manufacturers/general/attr_code');
      
      for($i = 0; $i < count($manufacturers); $i++){
        // logo
        $manufacturers[$i]['image'] = Mage::getStoreConfig('manufacturers/image/'.$manufacturers[$i]['value']);
        
        // product count
        $collection = Mage::getModel('catalog/product')->getCollection()
          ->addAttributeToFilter($manu_code, array('eq' => (int)$manufacturers[$i]['value']))
          ->load();
        
        // count as same as Magento.
        $allBundles = array();
        foreach ($collection as $pr){
          $bundles = Mage::getResourceModel('bundle/selection')->getParentIdsByChild($pr->getId());
          $allBundles = array_unique(array_merge($allBundles, $bundles));
        }
        $manufacturers[$i]['product_count'] = $collection->count() + count($allBundles);
      }
      
      $this->_allManufacturers = $manufacturers;
      
    }
    return $this->_allManufacturers;
  }  
  
  protected function _beforeToHtml()
  {
      return parent::_beforeToHtml();
  }
}
?>