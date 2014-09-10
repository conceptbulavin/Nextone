<?php

class Temashop_Imageupdater_Model_Updater_Beistle extends Temashop_Imageupdater_Model_Updater
{
    protected $_beistleUrlPrefix = "http://www.beistle.com/Assets/ProductImages/lg_";
    protected $_beistleUrlSuffix = '.jpg';
    protected $_beistleSkuPrefix = 'B';
    protected $_attributeValue = 'Beistle';
    
    public function processProductModel($product, $massUpdater)
    {
        try {
            $correctImages = 1;
            $error = false;
            if( $product->getId()){
                $productCleanSku = substr( $product->getSku(), strlen($this->_beistleSkuPrefix));
                $this->setProduct($product)
                    ->getImageFromSource($this->_beistleUrlPrefix. $productCleanSku .$this->_beistleUrlSuffix , $productCleanSku)
                    ->addImage( true )
                    ->save();
                if(!$massUpdater){
                    echo 'Product ID: ' . $product->getId() . ' - SKU: <b>'
                    . $product->getSku() . '</b> correctly updated with <b>'
                    . $correctImages . ' new images.</b><br />';
                }
            }
        } catch (Exception $e) {
            if($massUpdater){
                $this->_getSession()->addSuccess(
                        $this->_getHelper()->__($e->getMessage())
                    );
            }else{
                echo $e->getMessage().'<br>';
            }
            $error = true;
        }
        return !$error;
    }
}