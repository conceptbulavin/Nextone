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
/* $Id: Form.php 2 2014-01-13 07:35:22Z linhnt $ */

class Ma2_Manufacturers_Block_Adminhtml_Default_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
	}

  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('manufacturers_');
    $this->setForm($form);
    $fieldset = $form->addFieldset('upload_image', array('legend'=>Mage::helper('manufacturers')->__('Manufacturers and images'), 'class' => 'grid'));
    
    $manuOptions = Mage::getModel("manufacturers/system_config_source_manuid")->toOptionArray();
    if ($manuOptions){
      foreach ($manuOptions as $manuOption){
        $imgUrl = Mage::getStoreConfig('manufacturers/image/'. $manuOption['value']) ? Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Mage::getStoreConfig('manufacturers/image/'. $manuOption['value']) : '';
        
        $fieldset->addField('image_'.$manuOption['value'], 'image', array(
          'label'     => $manuOption['label'] . ' (ID: ' . $manuOption['value'] . ')',
          'name'      => $manuOption['value'],
          'required'  => false,
          'value'     => $imgUrl
        ));
      }
    }
    
    return parent::_prepareForm();
  }
}