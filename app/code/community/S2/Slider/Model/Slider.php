    <?php

/**
 * S2 Slider model
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Model_Slider extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    public function _construct()
    {

        parent::_construct();
        $this->_init('slider/slider');

    }

    /**
     * @param int $id
     * @param null $field
     * @return S2_Slider_Model_Slider
     */
    public function load($id, $field = null)
    {

        if (!intval($id) && is_string($id)) {
            $field = 'identifier';
        }
        parent::load($id, $field);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {

        return Mage::getModel('slider/slider_slides')->getCollection()
            ->addFieldToFilter('slider_id', $this->getId())
            ->addOrder('position', 'desc');
    }

}
