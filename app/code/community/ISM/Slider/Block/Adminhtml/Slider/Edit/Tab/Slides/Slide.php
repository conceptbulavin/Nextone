<?php

/**
 * ISM Slider slider edit form slides tab slide item
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Block_Adminhtml_Slider_Edit_Tab_Slides_Slide extends Mage_Adminhtml_Block_Widget
{
    /**
     * @var
     */
    protected $_sliderInstance;
    /**
     * @var string
     */
    protected $_name = 'slides';
    /**
     * @var string
     */
    protected $_id = 'slide';
    /**
     * @var
     */
    protected $_values;
    /**
     * @var int
     */
    protected $_itemCount = 1;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ism/slider/slides/slide.phtml');
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        return $this->_itemCount;
    }

    /**
     * Set maximum item index
     *
     * @param $itemCount
     * @return ISM_Slider_Block_Adminhtml_Slider_Edit_Tab_Slides_Slide
     */
    public function setItemCount($itemCount)
    {
        $this->_itemCount = max($this->_itemCount, $itemCount);

        return $this;
    }


    /**
     * Get Slider instance
     *
     * @return Mage_Core_Model_Abstract|mixed
     */
    public function getSlider()
    {
        if (!$this->_sliderInstance) {
            if ($slider = Mage::registry('slider_data')) {
                $this->_sliderInstance = $slider;
            } else {
                $this->_sliderInstance = Mage::getSingleton('slider/slider');
            }
        }
        return $this->_sliderInstance;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getFieldId()
    {
        return $this->_id;
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                'label' => Mage::helper('slider')->__('Delete Slide'),
                'class' => 'delete delete-slide'
            ))
        );

        return parent::_prepareLayout();
    }

    /**
     * @return mixed
     */
    public function getAddButtonId()
    {
        $buttonId = $this->getLayout()
            ->getBlock('slider.slides')
            ->getChild('add_button')->getId();

        return $buttonId;
    }

    /**
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * @return mixed
     */
    public function getIsEnabledSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setData(array(
            'id' => $this->getFieldId().'_{{slide_id}}_is_active',
            'class' => 'select'
        ))
            ->setName($this->getFieldName().'[{{slide_id}}][is_active]')
            ->setOptions(Mage::getSingleton('adminhtml/system_config_source_yesno')
            ->toOptionArray());

        return $select->getHtml();
    }

    /**
     * Get the slides for slider
     *
     * @return array
     */
    public function getSlideValues()
    {
        if (!$this->_values) {

            $values = array();

            foreach ($this->getSlider()->getSlides() as $slide) {

                $this->setItemCount($slide['slide_id']);

                $value = array(
                    'slide_id' => $slide['slide_id'],
                    'slider_id' => $slide['slider_id'],
                    'description' => $slide['description'],
                    'deleteimage' => $slide['image'],
                    'image' => $slide['image'],
                    'link' => $slide['link'],
                    'title' => $slide['title'],
                    'position' => $slide['position'],
                    'is_active' => $slide['is_active'],
                    'item_count' => $this->getItemCount()
                );

                $values[] = new Varien_Object($value);
            }
            $this->_values = $values;
        }

        return $this->_values;
    }

}