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
/* $Id: Default.php 2 2014-01-13 07:35:22Z linhnt $ */

class Ma2_Manufacturers_Block_Adminhtml_Default extends Mage_Adminhtml_Block_Widget
{
  public function __construct()
  {
      parent::__construct();
      $this->setTemplate('ma2_manufacturers/default.phtml');
      $this->setTitle('Manufacturers');
      $this->setHeaderCss('manufacturers_manager');
  }
  
  protected function _prepareLayout()
    {
        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Save'),
                    'onclick'   => 'editForm.submit()',
                    'class' => 'save',
                ))
        );
        $this->setChild('form',
            $this->getLayout()->createBlock('manufacturers/adminhtml_default_form')
        );
        return parent::_prepareLayout();
    }

    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true));
    }
    

    



}