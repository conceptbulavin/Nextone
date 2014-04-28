<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');
$config->saveConfig('manufacturers/general/attr_code', 'manufacturer' );
$config->saveConfig('manufacturers/general/thumb_w', 110);
$config->saveConfig('manufacturers/general/thumb_h', 50 );
$config->saveConfig('manufacturers/general/dimension_spec', 0 );
$config->saveConfig('manufacturers/general/thumb_upload_resize', 0 );

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;


$config = array(
    array(
        'identifier'  => 'home',
        'title'   => 'Home page',
        'content_heading' =>'',
        'layout_update_xml' => <<<HTML
HTML
    ,
        'content' => <<<HTML
<div>
{{widget type="slider/slider" slider_id="slider"}}
{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="home_banner_top"}}
{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="home_category"}}
{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="banner_free_delivery"}}
{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="home_usp_list"}}
{{widget type="cms/widget_block" template="cms/widget/static_block/default.phtml" block_id="manufacture"}}
</div>
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
};

$config = array(
    array(
        'id_new'  => 'banner_free_delivery',
        'title'   => 'Бесплатная доставка',
        'content' => <<<HTML
<div class="banner-free-delivery">
<a href="#" title="Бесплатная доставка">
<img src="{{skin url="images/banner_free_delivary.jpg"}}" />
</a>
</div>
HTML
    ),
    array(
        'id_new'  => 'home_banner_top',
        'title'   => 'Главная: верхний баннер',
        'content' => <<<HTML
<div class="home-banner home-banner-top">
  <a href="#"> <img src="{{skin url="images/home_banner_top.jpg"}}" alt="" />
    <span>Показать</span>
  </a>
  <a href="#">
    <img src="{{skin url="images/home_banner_top2.jpg"}}" alt="" />
  </a>
</div>
HTML
    ),
    array(
        'id_new'  => 'manufacture',
        'title'   => 'Бренды',
        'content' => <<<HTML
{{block type="manufacturers/list" name="ma2.manufacturers1" template="ma2_manufacturers/slider.phtml" title="Our manufacturers - Slider" show_logo="1" grid_col="6" show_product_count="1" }}
HTML
    ),
    array(
        'id_new'  => 'home_category',
        'title'   => 'Категории',
        'content' => <<<HTML
<div>{{block type="featuredcategories/display" template="sfc_featuredcategories/display.phtml"}}</div>
HTML
    )
);

/* Create static blocks for specified config */
foreach ($config as $item) {
    Mage::getModel('cms/block')->load($item['id_new'], 'identifier')->delete();

    $block = Mage::getModel('cms/block');
    $data = array(
        'title'      => $item['title'],
        'identifier' => $item['id_new'],
        'content'    => $item['content'],
        'is_active'  => 1,
        'stores'     => array(0),
    );

    $block->addData($data);
    $block->save();
}

