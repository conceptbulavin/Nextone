<?php

class Temashop_Imageupdater_Adminhtml_Imageupdater_MassactionController
    extends Mage_Adminhtml_Controller_Action
{
    
    protected function _init()
    {
        if (!$this->_validateFormKey()) {
            $this->getResponse()->setHeader('HTTP/1.1 403 Forbidden', null, true);
            return;
        }
        if (!$this->getRequest()->has('product')) {
            $this->getResponse()->setHeader('HTTP/1.1 403 Forbidden', null, true);
            return;
        }
        $productIds = array_unique(array_map('intval', $this->getRequest()->get('product')));
        if (!$productIds) {
            $this->_getSession()->addError(
                $this->_getHelper()->__('No product ids specified.')
            );
            return;
        }
        return $productIds;
    }
    
    protected function _processUpdateImage( $model, $productIds )
    {
        //skip to update products from another manufacturer
        $productIds = $model->filterIdsByManufacturer($productIds);
        $result = $model->processByProductIds($productIds, true );
        $this->_getSession()->addSuccess(
            $this->_getHelper()->__( "%s Product(s) was successfully image updated.", $result['success_count'])
        );
        $this->getResponse()->setRedirect('/admin/catalog_product/');
    }
    
    public function imageUpdateSmiffysAction()
    {
        $productIds = $this->_init();
        if(empty($productIds)){
            return;
        }
        $model = Mage::getModel('temashop_imageupdater/updater');
        /* @var $model Temashop_Imageupdater_Model_Updater */
        $this->_processUpdateImage($model, $productIds);
    }

    public function imageUpdateBeistleAction()
    {
        $productIds = $this->_init();
        if(empty($productIds)){
            return;
        }
        $model = Mage::getModel('temashop_imageupdater/updater_beistle');
        /* @var $model Temashop_Imageupdater_Model_Updater_Beistle */
        $this->_processUpdateImage($model, $productIds);
    }
    
    public function imageUpdateWilbersAction()
    {
        $productIds = $this->_init();
        if(empty($productIds)){
            return;
        }
        $model = Mage::getModel('temashop_imageupdater/updater_wilbers');
        /* @var $model Temashop_Imageupdater_Model_Updater_Wilbers  */
        $this->_processUpdateImage($model, $productIds);
    }
    
    public function imageUpdateRubiesAction()
    {
        $productIds = $this->_init();
        if(empty($productIds)){
            return;
        }
        $model = Mage::getModel('temashop_imageupdater/updater_rubies');
        /* @var $model Temashop_Imageupdater_Model_Updater_Rubies  */
        $this->_processUpdateImage($model, $productIds);
    }
    
    protected function _getHelper() {
        return Mage::helper('temashop_imageupdater');
    }
}