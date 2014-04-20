<?php

/**
 * ISM Slider slides collection
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Model_Resource_Slider_Slides_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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