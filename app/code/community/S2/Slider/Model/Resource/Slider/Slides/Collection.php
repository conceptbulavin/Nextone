<?php

/**
 * S2 Slider slides collection
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Model_Resource_Slider_Slides_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
