<?php

class S2_ImageZoom_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getJsLiteral($image, $thumbImage, $imageWidth, $imageHeight)
    {
        list($_imgWidth, $_imgHeight) =
            (!(strpos((string) $image, '/catalog/product/placeholder/')) && $thumbImage->getPath())
                ? getimagesize($thumbImage->getPath())
                : array(0,0);
        $imageUrl = ($_imgWidth > $imageWidth && $_imgHeight > $imageHeight) ? $image : NULL;
        return '{image: "'. ($imageUrl ? $imageUrl : 'javascript: void(0)') .'", thumb: "'. $image->resize($imageWidth, $imageHeight) .'", alt: "'. $this->jsQuoteEscape($this->htmlEscape($thumbImage->getLabel())) .'", loaded: false}';
    }

    public function getJson(array $array)
    {
        return  '['.join(',', $array).']';
    }

}