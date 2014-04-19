<?php

/**
 * ISM Slider slider block
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Block_Slider extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    /**
     * @var
     */
    protected $_slides;

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        if (!$this->hasData('template')) {
            $this->setData('template', 'ism/slider/slider.phtml');
        }

        return $this->getData('template');
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->_beforeToHtml() || !$sliderId = $this->getSliderId()) {
            return '';
        }

        $slider = Mage::getModel('slider/slider')->setStoreId(Mage::app()->getStore()->getStoreId())->load($sliderId);
        if (!$slider->getIsActive()) {
            return '';
        }

        $this->setSlider($slider);

        return parent::_toHtml();
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        if (empty($this->_slides)) {
            $this->_slides = $this->getSlider()->getSlides()->addFieldToFilter('is_active', 1);
        }

        return $this->_slides;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        $json = array();
        foreach($this->getSlides() as $slide){
            $slideObj = new stdClass();
            $slideObj->src = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . 'ismslider' . DS . $slide['image'];
            $slideObj->alt = $slide['title'];
            $json[] = $slideObj;
        }

        return json_encode($json);
    }
}
