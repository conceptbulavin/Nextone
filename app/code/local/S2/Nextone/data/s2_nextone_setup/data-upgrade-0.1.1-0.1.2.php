<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');

$config->saveConfig('catalog/frontend/list_allow_all', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_Wishlist', 2 );
