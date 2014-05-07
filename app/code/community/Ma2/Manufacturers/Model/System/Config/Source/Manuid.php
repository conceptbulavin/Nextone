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
/* $Id: Manuid.php 2 2014-01-13 07:35:22Z linhnt $ */

class Ma2_Manufacturers_Model_System_Config_Source_Manuid
{
    public function toOptionArray()
    {
    
      $manu_code = Mage::getStoreConfig('manufacturers/general/attr_code');
      if (empty($manu_code)) return array();
        
      $attribute = Mage::getModel('catalog/product')->getResource()->getAttribute($manu_code);
      if (!$attribute) return array();
      
      $manufacturers = $attribute->getSource()->getAllOptions(false);
      if (!$manufacturers) return array();
      
      return $manufacturers;
    }
}

?>