<?php

/**
 * S2 Slider slides model
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Model_Slider_Slides extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    public function _construct()
    {

        parent::_construct();
        $this->_init('slider/slider_slides');

    }
}
