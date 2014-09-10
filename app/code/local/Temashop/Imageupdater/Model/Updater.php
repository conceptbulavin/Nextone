<?php

class Temashop_Imageupdater_Model_Updater
{
    protected $_mediaArray = array(
        'thumbnail',
        'small_image',
        'image',
    );
    
    protected $_attributeCodeManufacturer = 'manufacturer';
    protected $_attributeValue = 'Smiffys';

    protected $_copyPath = null;
    protected $_imagePath = null;
    protected $_product = null;

    public function  __construct() {
        $this->_init();
        $this->_copyPath = Mage::getBaseDir('media') . DS . 'tmp'. DS;
        if(!file_exists($this->_copyPath)){
            if(!mkdir($this->_copyPath)){
                throw new Exception('Cannot create directory: "'.$this->_copyPath.'". Please create this directory with 0777 permissions.');
            }
        }
    }

    public function getAttributeCodeManuf()
    {
        return $this->_attributeCodeManufacturer;
    }
    
    public function getAttributeValueManufacturer()
    {
        return $this->_attributeValue;
    }

    public function setImagePath( $path )
    {
        $this->_imagePath = $path;
    }

    protected function _init()
    {
        @set_time_limit(0);
        @ini_set('max_execution_time', 0);
        @ini_set('display_errors', 1);
        @ini_set('memory_limit', '512M');
        @error_reporting(E_ALL);
        @umask(0);
    }
    /**
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     * @throws Exception
     */
    public function getProductCollection($limit = null, $offset = null)
    {
        $collection = $this->_getBaseProductCollection();
        if( !$limit ){
            $offset = null;
        }
        return $collection->getAllIds($limit, $offset );
    }
    
    protected function _getBaseProductCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        return $collection;
    }
    
    public function filterIdsByManufacturer( $productIds )
    {
        $collection = $this->_getBaseProductCollection();
        $collection->getSelect()->where('e.entity_id IN (?)', $productIds);
        return $collection->getAllIds();
    }
    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Imagescript_ImageUpdater
     */
    public function setProduct( $product)
    {
        $this->_product = $product;
        return $this;
    }
    /**
     *
     * @param type $sku
     * @return Mage_Catalog_Model_Product
     * @throws Exception
     */
    public function getProductBySku($sku)
    {
        $product = Mage::getModel('catalog/product');
        $id = $product->getIdBySku($sku);
        $product->load($id);
        if (!$product) {
            throw new Exception('No such product widh SKU: '.$sku );
        }
        $this->_product = $product;
        return $product;
    }

    public function getProductBySkuAndManufacturer($sku, $manufacturerOptionTitle)
    {
        $product = Mage::getModel('catalog/product');
        $id = $product->getIdBySku($sku);
        $product->load($id);
        if (!$product) {
            throw new Exception('No such product widh SKU: '.$sku );
        }
        $attr = $product->getResource()->getAttribute($this->getAttributeCodeManuf());
        $attrId = null;

        if ($attr->usesSource()) {
            $attrId = $attr->getSource()->getOptionId($manufacturerOptionTitle);
        }
        if (!$attrId) {
            throw new Exception('Cant find option id for: '.$manufacturerOptionTitle);
        }
        if ((int)$product->getData($this->getAttributeCodeManuf()) !== (int) $attrId) {
            throw new Exception("Product manufacturer doesn't match with script one");
        }
        $this->_product = $product;
        return $product;
    }
    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $feedPath file path to image on other server
     * @param integer $productCleanSku
     * @return Imagescript_ImageUpdater
     * @throws Exception
     */
    public function getImageFromSource( $feedPath, $productCleanSku )
    {
        /* Check if image is found from the feed */
        if (@getimagesize($feedPath )) {
            if (copy($feedPath, $this->_copyPath . $this->_product->getSku() . '.jpg')) {
                $this->_imagePath =  $this->_copyPath . $this->_product->getSku() . '.jpg';
            }
            else
                throw new Exception('Copy from feed failed for file : ' . $productCleanSku . '.jpg' . ' <br />');
        }
        return $this;
    }

    /**
     *
     * @return Imagescript_ImageUpdater
     * @throws Exception
     * @param boolean $first set this to TRUE for delete previous images from product gallery
     */
    public function addImage( $first = false, $needUnlink = true )
    {
        $filePath = $this->_imagePath;
        if ( file_exists($filePath) ) {
            try {
                /* @var $this->_product Mage_Catalog_Model_Product */
                //remove all previous images on add first new image, skip on add next images
                if( $first ){
                    $images = $this->_product->getMediaGalleryImages();
                    if($images->count()){
                        $attributes = $this->_product->getTypeInstance(true)->getSetAttributes($this->_product);
                        if (isset($attributes['media_gallery'])) {
                            // From this point forward, the gallery is represented by $attributes
                            $gallery = $attributes['media_gallery'];
                        }

                        foreach ($images as $image ){
                            if($image !== null && $gallery->getBackend()->getImage($this->_product, $image['file'])) {
                                $gallery->getBackend()->removeImage($this->_product, $image['file']);
                                @unlink ($image['path']);
                            }
                        }
                    }
                }

                $this->_product
                    ->addImageToMediaGallery($filePath, $first ? $this->_mediaArray : '' , false, false )
                ;
                if($needUnlink){
                    @unlink($this->_imagePath);
                }
                $this->_imagePath = null;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new Exception("Product ID: " . $this->_product->getId() . ' - SKU: <b>' . $this->_product->getSku() . "</b>  does not have an image or the path is incorrect. Path was: {$filePath}<br/>");
        }
        return $this;
    }

    public function save()
    {
        $this->_product->save();
        return $this;
    }

    public function secondsToTime($ss) {
        $s = $ss%60;
        $m = floor(($ss%3600)/60);
        $h = floor(($ss%86400)/3600);
        return "$h hours, $m minutes, $s seconds";
    }

    public function processByProductIds($productIds, $massUpdater = false )
    {
        $result = array('success_count' => 0, 'failed_count' => 0);
        if(!empty( $productIds )){
            foreach ($productIds as $productId) {
                $product = Mage::getModel('catalog/product')->load($productId);
                if (!($product && $product->getId())) {
                    if(!$massUpdater){
                        echo 'Can not load product with id: '.$productId.'<br/>';
                    }
                    continue;
                }

                //we need skip simple product, if it is part of configurable
                $parents = Mage::getModel('catalog/product_type_configurable')
                                ->getParentIdsByChild( $product->getId() );
                if ( $product->getTypeID() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE 
                    && !empty($parents)){
                    if(!$massUpdater){
                        echo 'Product with id: '.$productId.' is part of configurable product. Skipped.<br/>';
                    }
                    continue;
                }

                $productRes = $this->processProductModel($product, $massUpdater );
                $result[$productRes ? 'success_count' : 'failed_count']++;
            }
        }
        return $result;
    }


    protected $_imagesMask = false;
    
    protected function _getImagesMask()
    {
        if (false === $this->_imagesMask) {
            $altImagesMask = '_A';
            $altImagesCount = 20;
            $images = array( '', '_b', '_s', '_p' );
            for ($i = $altImagesCount; $i >= 1; $i--) {
                $images[] = $altImagesMask.$i;
            }
            $this->_imagesMask = $images;
        }
        return $this->_imagesMask;
    }
    
    public function processProductModel($product, $massUpdater)
    {
        //$defaultPath = Mage::getBaseDir('media').'/catalog/product/';
        $defaultPath = Mage::getBaseDir().'/web/images/';
        try {
            $error = false;
            $productCleanSku = $product->getSku();
            $firstImage = true;
            $correctImages = 0;
            $this->setProduct($product);
            $filePath = $defaultPath . $productCleanSku . '_big.jpg';
            if ( file_exists($filePath) ) {
                $this->setImagePath($filePath);
                $this->addImage( $firstImage, false );
                $firstImage = false;
                $correctImages++;
            }
            $this->save();
            if(!$massUpdater){
                echo 'Product ID: ' . $product->getId() . ' - SKU: <b>'
                . $product->getSku() . '</b> correctly updated with <b>'
                . $correctImages . ' new images.</b><br />';
            }
        }  catch (Exception $e ){
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
     * Retrieve adminhtml session model object
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
    
    /**
     * 
     * @return Temashop_Imageupdater_Helper_Data
     */
    protected function _getHelper() {
        return Mage::helper('temashop_imageupdater');
    }
}