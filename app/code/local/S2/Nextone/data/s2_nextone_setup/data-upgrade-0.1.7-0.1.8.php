<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$config = array(
    array(
        'title'   => 'Home Slider',
        'identifier' =>'slider',
        'width' => 730,
        'height' => 290,
        'class' => 's2',
        'controls' => 1,
        'pagination' => 0
    )
);

/* Create cms pages for specified config */
foreach ($config as $item) {
    Mage::getModel('slider/slider')->load($item['identifier'], 'identifier')->delete();
    $slider = Mage::getModel('slider/slider');
    $data = array(
        'title'             =>  $item['title'],
        'identifier'        =>  $item['identifier'],
        'width'             =>  $item['width'],
        'height'            =>  $item['height'],
        'class'             =>  $item['class'],
        'controls'          =>  $item['controls'],
        'pagination'        =>  $item['pagination'],
        'is_active'         =>  1
    );

    $slider->addData($data);
    $slider->setStores(array(0));
    $slider->save();
};
