<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$config = Mage::getModel('core/config');

function getAllOptions($attribute_code)
{
    $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $attribute_code);
    $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
    $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
    return $attribute_table->getAllOptions(false);
}

function addAttributeOption($attribute_code, $attribute_value) {
    $attribute_model = Mage::getModel('eav/entity_attribute');
    $attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;

    $attribute_code = $attribute_model->getIdByCode('catalog_product', $attribute_code);
    $attribute = $attribute_model->load($attribute_code);

    $attribute_table = $attribute_options_model->setAttribute($attribute);
    $options = $attribute_options_model->getAllOptions(false);

    $value['option'] = array($attribute_value,$attribute_value);
    $result = array('value' => $value);
    $attribute->setData('option',$result);
    $attribute->save();
}

function deleteAttributeOption($attribute_code, $attribute_value) {
    $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', $attribute_code);
    $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
    $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
    $options = $attribute_table->getAllOptions(false);

    $_optionArr = array('value'=>array(), 'order'=>array(), 'delete'=>array());
    foreach ($options as $option){
        $_optionArr['value'][$option['value']] = array($option['label']);
        if($attribute_value == $option['label'] || $attribute_value == "*"){
            $_optionArr['delete'][$option['value']] = true;
        }
    }
    $attributeInfo->setOption($_optionArr);
    $attributeInfo->save();
}


// Clear all options
deleteAttributeOption('manufacturer','*');

$labels = array(
    'Braun' => 'manufacturers/braun.png',
    'Tefal' => 'manufacturers/tefal.png',
    'Whirlpool' => 'manufacturers/werpool.png',
    'Samsung' => 'manufacturers/samsung.png',
    'LG' => 'manufacturers/lg.png',
    'Bosch' => 'manufacturers/bosch.png',
    'Sony' => 'manufacturers/sony.png',
    'Moulinex' => 'manufacturers/moulinex.png'
);

foreach ($labels as $key => $option) {
  addAttributeOption('manufacturer', $key);
}

$options = getAllOptions('manufacturer');

foreach ($options as $key => $option) {
    $config->saveConfig('manufacturers/image/'.$option['value'], $labels[$option['label']]);
}

$config->saveConfig('catalog/navigation/max_depth', 1);
$config->saveConfig('currency/options/base', 'UAH');
$config->saveConfig('currency/options/default', 'UAH');
$config->saveConfig('currency/options/allow', 'UAH');
