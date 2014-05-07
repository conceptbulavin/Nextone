<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$config = array(
    array(
        'title'   => 'Slider 1',
        'image' =>'slide_0.jpg'
    ),
    array(
        'title'   => 'Slider 2',
        'image' =>'slide_0.jpg'
    )
);

$slider_id = Mage::getModel('slider/slider')->load('slider', 'identifier')->getId();

// Delete all slides before creation new
$slides = Mage::getModel('slider/slider_slides')->load($slider_id, 'slider_id')->getCollection();
foreach ($slides as $index => $slide) {
    $slide->delete();
}

/* Create cms pages for specified config */
foreach ($config as $item) {
    $slide = Mage::getModel('slider/slider_slides');
    $data = array(
        'title'             =>  $item['title'],
        'image'             =>  $item['image'],
        'slider_id'         =>  $slider_id,
        'is_active'         =>  1
    );

    $slide->addData($data);
    $slide->save();
};
