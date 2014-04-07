<?php

/**
 * TinyBrick Commercial Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the TinyBrick Commercial Extension License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.delorumcommerce.com/license/commercial-extension
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@tinybrick.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this package to newer
 * versions in the future. 
 *
 * @category   TinyBrick
 * @package    TinyBrick_FastCatalog
 * @copyright  Copyright (c) 2010 TinyBrick Inc. LLC
 * @license    http://store.delorumcommerce.com/license/commercial-extension
 */
class TinyBrick_FastCatalog_Model_Catalog_Product_Observer {

    public function destroyCache($observer) {
        $_product = $observer->getEvent()->getProduct();
        Mage::app()->cleanCache(array(Mage_Catalog_Model_Product::CACHE_TAG . "_" . $_product->getId()));
        try {
            if (@class_exists('PageCache')) {
                $key = $_SERVER['SERVER_NAME'] . $_SERVER['HTTP_HOST'].'/' . $_product->getUrlKey() . '.html';
                $key = preg_replace('/(\?|&|&&)debug_front=1/s', '', $key);
                //$key .= '_' . PageCache::getCurrencyCode();
                //$key .= '_' . Mage::app()->getStore()->getId();
                PageCache::delete($key);
                //todo - process child products
                if ($_product->getTypeId() == 'configurable') {
                    $_children = $_product->getTypeInstance()->getUsedProductIds();
                    if (is_array($_children)) {
                        foreach ($_children as $key => $_id) {
                            $_child_product = Mage::getModel('catalog/product')
                                    ->setStoreId(Mage::app()->getStore()->getId())
                                    ->load($_id);
                            // @var $product Mage_Catalog_Model_Product
                            if ($_child_product->getId()) {
                                $_child_key = $_SERVER['SERVER_NAME'] . $_SERVER['HTTP_HOST']. '/' . $_child_product->getUrlKey() . '.html';
                                $_child_key = preg_replace('/(\?|&|&&)debug_front=1/s', '', $_child_key);
                                //$key .= '_' . PageCache::getCurrencyCode();
                                //$key .= '_' . Mage::app()->getStore()->getId();
                                PageCache::delete($_child_key);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            //do nothing, since we don't have the class
            return;
        }
    }

}