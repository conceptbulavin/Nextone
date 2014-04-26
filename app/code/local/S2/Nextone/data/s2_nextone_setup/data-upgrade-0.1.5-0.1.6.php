<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');
$config->saveConfig('customer/captcha/enable', 1 );
$config->saveConfig('customer/captcha/forms', "contact_form" );

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;


$config = array(
    array(
        'identifier'  => 'about-us',
        'title'   => 'О компании',
        'content_heading' =>'О компании',
        'layout_update_xml' => <<<HTML
HTML
    ,
        'content' => <<<HTML
<p>Контент</p>
HTML
    ,
        'root_template' => 'one_column'
    ),

    array(
        'identifier'  => 'payment',
        'title'   => 'Оплата',
        'content_heading' =>'Оплата',
        'layout_update_xml' => <<<HTML
HTML
    ,
        'content' => <<<HTML
<p>Контент</p>
HTML
    ,
        'root_template' => 'one_column'
    ),

    array(
        'identifier'  => 'delivery',
        'title'   => 'Доставка',
        'content_heading' =>'Доставка',
        'layout_update_xml' => <<<HTML
HTML
    ,
        'content' => <<<HTML
<p>Контент</p>
HTML
    ,
        'root_template' => 'one_column'
    )
);

/* Create cms pages for specified config */
foreach ($config as $item) {
    Mage::getModel('cms/page')->load($item['identifier'], 'identifier')->delete();
    $page = Mage::getModel('cms/page');
    $data = array(
        'title'             =>  $item['title'],
        'identifier'        =>  $item['identifier'],
        'content'           =>  $item['content'],
        'content_heading'   =>  $item['content_heading'],
        'layout_update_xml' =>  $item['layout_update_xml'],
        'root_template'     =>  $item['root_template'],
        'is_active'         =>  1
    );

    $page->addData($data);
    $page->setStores(array(0));
    $page->save();
}
