<?php

/**
 * S2 Slider slides model
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        //where is the controller
        $this->_controller = 'adminhtml_slider';

        $this->_blockGroup = 'slider';
        //text in the admin header

        $this->_headerText = 'Sliders management';
        //value of the add button

        $this->_addButtonLabel = 'Add a Slider';
        parent::__construct();

    }
}
