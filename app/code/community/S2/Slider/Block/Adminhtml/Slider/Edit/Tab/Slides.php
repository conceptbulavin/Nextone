<?php

/**
 * S2 Slider slider edit form slides tab
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Block_Adminhtml_Slider_Edit_Tab_Slides extends Mage_Adminhtml_Block_Widget
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('s2/slider/slides.phtml');
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    public function _prepareLayout()
    {

        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label' => Mage::helper('slider')->__('Add New Slide'),
                'class' => 'add',
                'id'    => 'add_new_slide'
            ))
        );

        $this->setChild('items_section',
            $this->getLayout()->createBlock('slider/adminhtml_slider_edit_tab_slides_slide')
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    /**
     * @return string
     */
    public function getSlideBoxHtml()
    {
        return $this->getChildHtml('items_section');
    }
}
