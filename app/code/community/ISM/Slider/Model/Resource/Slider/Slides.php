<?php

/**
 * ISM Slider slides mysql resource
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */

class ISM_Slider_Model_Resource_Slider_Slides extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */

    public function _construct()
    {
        $this->_init('slider/slider_slides', 'slide_id');
    }

}