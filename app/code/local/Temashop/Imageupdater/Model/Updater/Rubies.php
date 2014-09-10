<?php

class Temashop_Imageupdater_Model_Updater_Rubies extends Temashop_Imageupdater_Model_Updater
{
    protected $_rubiesUrlPrefix = "http://daten.rubies.de/Bilder/KatalogBilder/";
    protected $_rubiesUrlSuffix = '.jpg';
    
    protected $_rubiesUrlPrefix2 = "http://www.rubiesuk.com/sites/rubiesukb2b/files/product_images/";
    protected $_rubiesUrlSuffix2 = '/01.jpg';
    protected $_rubiesUrlSuffix2_1 = '.jpg';
    
    protected $_rubiesUrlPrefix3 = "http://rubies.com/images/worldwide/xl/";
    protected $_rubiesUrlSuffix3 = 'xl.jpg';
    
    protected $_rubiesSkuPrefix = 'R';
    
    protected $_range = 9;
    
    protected $_attributeValue = 'Rubies';
    
    public function processProductModel($product, $massUpdater )
    {
        try {
            $correctImages = 1;
            $error = false;
            if( $product->getId()){
                
                $productCleanSku = substr( $product->getSku(), strlen($this->_rubiesSkuPrefix));
                
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
        
        $filePath = $this->_rubiesUrlPrefix . $i . $sku . $this->_rubiesUrlSuffix;
        //check path like http://daten.rubies.de/Bilder/KatalogBilder/{$sku}.jpg
        if( @getimagesize($filePath) ) {
            $filesArray[] = $filePath;
        }
        if(!empty($filesArray)){
            return $filesArray;
        }
        
        for ($i = 0; $i <= $altImagesCount; $i++) {
            $filePath = $this->_rubiesUrlPrefix . $i . $sku . $this->_rubiesUrlSuffix;
            //check path like http://daten.rubies.de/Bilder/KatalogBilder/{$i}{$sku}.jpg
            
            if( @getimagesize($filePath) ) {
                $filesArray[] = $filePath;
            }
            if(!empty($filesArray)){
                return $filesArray;
            }
        }
        // check path like http://www.rubiesuk.com/sites/rubiesukb2b/files/product_images/{$sku}/01.jpg
        $filePath = $this->_rubiesUrlPrefix2 . $sku . $this->_rubiesUrlSuffix2;
        if( @getimagesize($filePath) ) {
            $filesArray[] = $filePath;
        }
        // check path like http://www.rubiesuk.com/sites/rubiesukb2b/files/product_images/{$sku}/{$sku}.jpg
        $filePath = $this->_rubiesUrlPrefix2 . $sku . DS . $sku . $this->_rubiesUrlSuffix2_1;
        if( @getimagesize($filePath) ) {
            $filesArray[] = $filePath;
        }
        //check path like http://rubies.com/images/worldwide/xl/{$sku}xl.jpg
        $filePath = $this->_rubiesUrlPrefix3 . $sku . $this->_rubiesUrlSuffix3;
        if( @getimagesize($filePath) ) {
            $filesArray[] = $filePath;
        }
        
        return $filesArray;
    }
}