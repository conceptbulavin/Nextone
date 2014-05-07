<?php

class S2_Nextone_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getActionPrice($price, $specialPrice)
    {
        return round(($price - $specialPrice)/$price*100, 2);
    }
}
