<?php

/**
 * ISM Slider slides model
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Model_Slider_Slides extends Mage_Core_Model_Abstract
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