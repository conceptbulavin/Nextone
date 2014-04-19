<?php

/**
 * ISM Slider mysql resource
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Model_Resource_Slider extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        $this->_init('slider/slider', 'slider_id');
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return ISM_Slider_Model_Resource_Slider
     */

    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('stores', $stores);

        }

        return parent::_afterLoad($object);
    }

    /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return ISM_Slider_Model_Resource_Slider
     */

    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $this->_saveSlides($object);
        $this->_saveStores($object);
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return ISM_Slider_Model_Resource_Slider
     */
    protected function _saveSlides(Mage_Core_Model_Abstract $object)
    {
        // saving slides
        if (is_array($object->getData('slides'))) {
            foreach ($object->getData('slides') as $slide) {
                $slide_model = Mage::getModel('slider/slider_slides');
                $slide['slider_id'] = $object->getSliderId();

                if (isset($slide['slide_id']) && !empty($slide['slide_id'])) {
                    $slide_model->setId($slide['slide_id']);
                } else {
                    unset($slide['slide_id']);
                }
                if (isset($slide['is_delete']) && $slide['is_delete'] == 1) {
                    $slide_model->delete();
                } else {
                    $slide_model->setData($slide)->save();
                }
            }
        }

        return $this;
    }


    /**
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */

    protected function _saveStores(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('slider/slider_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'slider_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'slider_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param $sliderId
     * @return array
     */

    public function lookupStoreIds($sliderId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('slider/slider_store'), 'store_id')
            ->where('slider_id = ?',(int)$sliderId);

        return $adapter->fetchCol($select);
    }

    /**
     * Process page data before deleting
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Cms_Model_Resource_Page
     */

    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = array(
            'slider_id = ?'     => (int) $object->getId(),
        );

        $this->_getWriteAdapter()->delete($this->getTable('slider/slider_store'), $condition);

        return parent::_beforeDelete($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Cms_Model_Page $object
     * @return Zend_Db_Select
     */

    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('slider_store' => $this->getTable('slider/slider_store')),
                $this->getMainTable() . '.slider_id = slider_store.slider_id',
                array())
                ->where('slider_store.store_id IN (?)', $storeIds)
                ->order('slider_store.store_id DESC')
                ->limit(1);
        }

        return $select;
    }

}