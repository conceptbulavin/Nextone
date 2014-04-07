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
 * @package    TinyBrick_LightSpeed
 * @copyright  Copyright (c) 2010 TinyBrick Inc. LLC
 * @license    http://store.delorumcommerce.com/license/commercial-extension
 */
class TinyBrick_LightSpeed_Block_Page_Html extends Mage_Page_Block_Html {

    protected function _construct() {
        if (isset($_GET['debug_back']) && $_GET['debug_back'] == '1') {
            $this->setIsDebugMode(true);
        }
        return parent::_construct();
    }

    public function cachePage($x0b='', $x0c='', $x0d='') {
        Mage::log('met disqualifier processing:' . $x0d);
        $this->setCachePage(true);
        $this->setExpires(($x0b) ? $x0b : false);
        $this->setDisqualifiers($x0c);
        $this->setDisqualifiedContentPath($x0d);
        $this->setAggregateTags(array('MAGE'));
        return $this;
    }

    protected function x0b() {
        if ($x0e = $this->getLayout()->getAllBlocks()) {
            $x0f = $this->getAggregateTags();
            foreach ($x0e as $x10) {
                $x11 = $x10->getCacheTags();
                if (!is_array($x11)) {
                    $x11 = array($x11);
                }
                foreach ($x11 as $x12) {
                    $x12 = strtoupper($x12);
                    if (!in_array($x12, $x0f)) {
                        $x0f[] = $x12;
                    }
                }
            }
            $this->setAggregateTags($x0f);
        }
    }


    public function getPageSaveKey($requestUri = false)
    {
        if (!$requestUri) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }
        $x14 = $_SERVER['SERVER_NAME'] . $_SERVER['HTTP_HOST'] . $requestUri;

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $x14 = 'SECURE_' . $x14;
        }

        $x14 = preg_replace('/(\?|&|&&)debug_back=1/s', '', $x14);
        if ($this->isMultiCurrencyEnabled()) {
            $x14 .= '_' . Mage::app()->getStore()->getCurrentCurrencyCode();
        }
        //$x14 .= '_' . Mage::app()->getStore()->getId();
        return $x14;
    }

    protected function _afterToHtml($x13) {
        $response = $this->getRequest();
        if ($this->getCachePage()) {
            if (Mage::app()->useCache('lightspeed') && @class_exists('PageCache')) {
                if (!Mage::getSingleton('customer/session')->isLoggedIn() && !PageCache::$_messageExists) {
                    if ($this->x0f()) {
                        if (Mage::getSingleton('checkout/cart')->getItemsCount() < 1) {
                            if (!$this->x0c()) {
                                $x14 = $this->getPageSaveKey();
                                Mage::app()->setUseSessionVar(false);
                                $x13 = Mage::getSingleton('core/url')->sessionUrlVar($x13);
                                $isNoRoute = $this->getRequest()->getActionName() == 'noRoute';
                                $x15 = array((string) $x13, $this->getDisqualifiers(), $this->getDisqualifiedContentPath(), $isNoRoute);
                                $this->x0b();
                                $this->x10("saving page with key: $x14", true);
                                Mage::getSingleton('lightspeed/server')->save($x14, $x15, $this->getExpires(), $this->getAggregateTags());
                            } else {
                                $this->x10("found items in the compare", true);
                            }
                        } else {
                            $this->x10("found items in the cart", true);
                        }
                    } else {
                        $this->x10("invalid registration", true);
                    }
                } else {
                    $this->x10("customer is logged in", true);
                }
            } else {
                $this->x10("please enable the 'whole pages' cache checkbox in the cache management panel", true);
            }
        } else {
            $this->x10("this page is not set to be cached according to the layout", true);
        }
        $x13 = preg_replace('/\<!\-\- +nocache.+?\-\-\>/si', "", $x13);
        $x13 = preg_replace('/\<!\-\- endnocache \-\-\>/si', "", $x13);
        return parent::_afterToHtml($x13);
    }

    protected function x0c() {
        $x16 = false;
        if (Mage::getSingleton('catalog/session')->getCatalogCompareItemsCount()) {
            if (Mage::getSingleton('catalog/session')->getCatalogCompareItemsCount() > 0) {
                $x16 = true;
            }
        }
        return $x16;
    }

    protected function x0d($x14) {
        return Mage::getStoreConfig($x14);
    }

    protected function isMultiCurrencyEnabled() {
        if ($x17 = Mage::getConfig()->getNode('lightspeed/global/multi_currency')) {
            if ($x17 == '1') {
                return true;
            }
        }
        return false;
    }

    private function x0f()
    {
        return true;
    }

    private function x10($x1b, $x1c=false) {
        if ($this->getIsDebugMode()) {
            echo "$x1b<br />";
            if ($x1c) {
                exit;
            }
        }
    }
}