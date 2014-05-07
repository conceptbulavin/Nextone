<?php

/**
 * ISM Slider slider edit tabs
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Block_Adminhtml_Slider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('slider_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('slider')->__('Configuration'));
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('slider')->__('Slider configuration'),
            'title' => Mage::helper('slider')->__('Slider configuration'),
            'content' => $this->getLayout()
                ->createBlock('slider/adminhtml_slider_edit_tab_form')
                ->toHtml()
        ));

        $this->addTab('items_section', array(
            'label' => Mage::helper('slider')->__('Slides'),
            'title' => Mage::helper('slider')->__('Slides'),
            'content' => $this->getLayout()
                ->createBlock('slider/adminhtml_slider_edit_tab_slides','slider.slides')
                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}