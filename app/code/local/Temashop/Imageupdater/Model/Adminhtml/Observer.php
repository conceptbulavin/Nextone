<?php

class Temashop_Imageupdater_Model_Adminhtml_Observer
{
    const ADMINHTML_PRODUCT_CONTROLLER_NAME = 'catalog_product';
    
    public function onPrepareMassaction($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction
            && $block->getRequest()->getControllerName() == self::ADMINHTML_PRODUCT_CONTROLLER_NAME ) {
            
            $block->addItem('temashop_separator_30', array(
                'label'=> $this->_getHelper()->__('----Image Updater Block----'),
            ));
            $block->addItem('image_update_script_beistle', array(
                'label' => $this->_getHelper()->__('Imagescript Update (Beistle)'),
                'url' => $block->getUrl('*/imageupdater_massaction/imageUpdateBeistle')
            ));
            $block->addItem('image_update_script_rubies', array(
                'label' => $this->_getHelper()->__('Imagescript Update (Rubies)'),
                'url' => $block->getUrl('*/imageupdater_massaction/imageUpdateRubies')
            ));
            $block->addItem('image_update_script_smiffys', array(
                'label' => $this->_getHelper()->__('Imagescript Update (Smiffys)'),
                'url' => $block->getUrl('*/imageupdater_massaction/imageUpdateSmiffys')
            ));
            $block->addItem('image_update_script_wilbers', array(
                'label' => Mage::helper('temashop_imageupdater')->__('Imagescript Update (Wilbers)'),
                'url' => $block->getUrl('*/imageupdater_massaction/imageUpdateWilbers')
            ));
            $block->addItem('temashop_separator_31', array(
                'label'=> $this->_getHelper()->__('----End Image Updater----'),
            ));
        }
    }
    
    protected function _getHelper() {
        return Mage::helper('temashop_imageupdater');
    }
}