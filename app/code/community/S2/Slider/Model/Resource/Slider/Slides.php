<?php

/**
 * S2 Slider slides mysql resource
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */

class S2_Slider_Model_Resource_Slider_Slides extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */

    public function _construct()
    {
        $this->_init('slider/slider_slides', 'slide_id');
    }

}
