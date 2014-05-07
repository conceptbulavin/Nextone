<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');

$config->saveConfig('design/pagination/pagination_frame', 3 );
$config->saveConfig('design/pagination/pagination_frame_skip', 2 );
