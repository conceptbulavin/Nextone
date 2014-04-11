<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');

$config->saveConfig('design/package/name', 'nextone');
$config->saveConfig('catalog/frontend/list_mode', 'grid');
$config->saveConfig('catalog/frontend/grid_per_page_values', '20');
$config->saveConfig('catalog/frontend/grid_per_page', '20');
$config->saveConfig('general/store_information/phone', '0412 33 33 333<br />097 222 222 22');
$config->saveConfig('advanced/modules_disable_output/Mage_Wishlist', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_Rating', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_Downloadable', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_GiftMessage', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_Tag', 1 );
$config->saveConfig('advanced/modules_disable_output/Mage_Poll', 1 );
