<?php

class Temashop_Imageupdater_Model_Updater_Wilbers extends Temashop_Imageupdater_Model_Updater
{
    protected $_wilbersUrlPrefix = "http://webshop.wilberskarnaval.nl/SupplyImages/WF0000";
    protected $_wilbersUrlSuffix = '.jpg.ashx?preset=large';
    
    protected $_wilbersSkuPrefix = 'W';
    protected $_range = 9;
    protected $_after_ = 2;
    
    protected $_attributeValue = 'Wilbers';
    
    public function processProductModel($product, $massUpdater )
    {
        try {
            $correctImages = 1;
            $error = false;
            if( $product->getId()){
                
                $productCleanSku = substr( $product->getSku(), strlen($this->_wilbersSkuPrefix));
                
                $this->setProduct($product);
                $filesArray = $this->_getImagesPath( $productCleanSku );
                if(empty($filesArray)){
                    return false;
                }
                
                $firstImage = true;
                $correctImages = 0;
                foreach ($filesArray as $fileName) {
                    $this->getImageFromSource($fileName , $productCleanSku)
                        ->addImage( $firstImage )
                    ;
                    $firstImage = false;
                    $correctImages++;
                }
                $this->save();
                
                $message = 'Product ID: ' . $product->getId() . ' - SKU: <b>'
                . $product->getSku() . '</b> correctly updated with <b>'
                . $correctImages . ' new images.</b>';
                if(!$massUpdater){
                    echo $message . '<br />';
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
    
    /**
     * 
     * @param string $sku
     * @return array
     */
    protected function _getImagesPath( $sku )
    {
        $filesArray = array();
        $altImagesCount = $this->_range;
        $altImagesCountAfter_ = $this->_after_;
        
        for ($i = 0; $i <= $altImagesCount; $i++) {
            $filePath = $this->_wilbersUrlPrefix . $i . DS . $sku . $this->_wilbersUrlSuffix;
            //check path like http://webshop.wilberskarnaval.nl/SupplyImages/WF00002/{$sku}.jpg.ashx?preset=main
            if( @getimagesize($filePath) ) {
                $filesArray[] = $filePath;
            }
            //check path like http://webshop.wilberskarnaval.nl/SupplyImages/WF00002/{$sku}_1.jpg.ashx?preset=main
            for($j = 0; $j <= $altImagesCountAfter_; $j++ ) {
                $filePath = $this->_wilbersUrlPrefix . $i . DS . $sku . '_'. $j .$this->_wilbersUrlSuffix;
                if( @getimagesize($filePath) ) {
                    $filesArray[] = $filePath;
                }
            }
            if(!empty($filesArray)){
                break;
            }
            //if sku ended on A, check path like http://webshop.wilberskarnaval.nl/SupplyImages/WF00002/{$sku}-A.jpg.ashx?preset=main
            $diff = explode( (int)$sku, $sku );
            if(!empty($diff[1])){
                $filePath = $this->_wilbersUrlPrefix . $i . DS . (int)$sku . '-' . $diff[1] . $this->_wilbersUrlSuffix;
                if( @getimagesize($filePath) ) {
                    $filesArray[] = $filePath;
                }
            }
            if(!empty($filesArray)){
                break;
            }
        }
        return $filesArray;
    }
}