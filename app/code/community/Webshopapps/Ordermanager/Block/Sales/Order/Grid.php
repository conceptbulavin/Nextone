<?php
/**
 * Magento Webshopapps Order Export Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Webshopapps
 * @package    Webshopapps_OrderExport
 * @copyright  Copyright (c) 2010 Zowta Ltd (http://www.webshopapps.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Genevieve Eddison <sales@webshopapps.com>
 * */
class Webshopapps_Ordermanager_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
    	if (Mage::getStoreConfig("order_export/export_orders/active")) {
    		
	        $this->getMassactionBlock()->addItem('ordermanager', array(
	             'label'=> Mage::helper('sales')->__('Export orders'),
	             'url'  => $this->getUrl('*/sales_order_export/csvexport'),
	        ));
    	}
    }
}
?>