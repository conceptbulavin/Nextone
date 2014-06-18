<?php

class Nextone_Page_Helper_Data extends Mage_Core_Helper_Data
{
    const XML_PATH_BASE = 'nextone_loc';
    const CATEGORY_INC = 50;
    
    public function getCategoryInc()
    {
        return self::CATEGORY_INC;
    }
    
    protected function _getStoreConfig( $path )
    {
        return Mage::getStoreConfig( self::XML_PATH_BASE . DS . $path);
    }
}