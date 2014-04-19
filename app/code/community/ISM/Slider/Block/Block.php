<?php
class ISM_Slider_Block_Block extends Mage_Core_Block_Template
{
    public function getCollection()
    {
        return $collection = Mage::getModel('slider/slider')
                ->getCollection()
                ->setOrder('slider_id','asc');

    }

    public function resizeImg($fileName, $width= '', $height = '')
    {
        $folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $imageURL = $folderURL . $fileName;

        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "resized" . DS . $fileName;
        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(TRUE);
                $imageObj->keepTransparency(TRUE);
                $imageObj->keepFrame(FALSE);
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "resized" . DS . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }
}