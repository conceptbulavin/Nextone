<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$config = array(
    array(
        'id_new'  => 'footer_links',
        'title'   => 'Ссылки в футере',
        'content' => <<<HTML
<ul>
<li><a href="/">Главная</a></li>
<li><a href="{{store direct_url="about-us"}}">О компании</a></li>
<li><a href="{{store direct_url="payment"}}">Оплата</a></li>
<li><a href="{{store direct_url="delivery"}}">Доставка</a></li>
<li><a href="{{store direct_url="contacts"}}">Контакты</a></li>
</ul>
HTML
    ),
    array(
        'id_new'  => 'header_links',
        'title'   => 'Ссылки в шапке',
        'content' => <<<HTML
<ul>
<li><a href="{{store direct_url="contacts"}}">Контакты</a></li>
<li><a href="{{store direct_url="payment"}}">Оплата</a></li>
<li><a href="{{store direct_url="delivery"}}">Доставка</a></li>
</ul>
HTML
    ),
    array(
        'id_new'  => 'footer_social_links',
        'title'   => 'Соц. линки в футере',
        'content' => <<<HTML
<ul>
<li><a href="#" class="icon-google" title="Google Plus">Google Plus</a></li>
<li><a href="#" class="icon-dribbble" title="Dribbble">Dribbble</a></li>
<li><a href="#" class="icon-twitter" title="Twitter">Twitter</a></li>
<li><a href="#" class="icon-facebook" title="Facebook">Facebook</a></li>
</ul>
HTML
    ),
    array(
        'id_new'  => 'home_usp_list',
        'title'   => 'Уникальное торговое предложение',
        'content' => <<<HTML
<div class="usp-list">
<ul>
<li><img src="{{skin url="images/promo_1.png"}}" /><p>Гарантия<br />возврата денег</p></li>
<li><img src="{{skin url="images/promo_2.png"}}" /><p>Качественное<br />обслуживание</p></li>
<li><img src="{{skin url="images/promo_3.png"}}" /><p>Только лучшие<br />производители</p></li>
<li><img src="{{skin url="images/promo_4.png"}}" /><p>Постоянные<br />акции и скидки</p></li>
<li><img src="{{skin url="images/promo_5.png"}}" /><p>Сертифицированная<br />продукция</p></li>
</ul>
</div>
HTML
    ),
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
<a href="#">
<img src="{{skin url="images/home_banner_top.jpg"}}" />
</a>
</div>
HTML
    ),
    array(
        'id_new'  => 'home_banner_bottom',
        'title'   => 'Главная: нижний баннер',
        'content' => <<<HTML
<div class="home-banner home-banner-bottom">
<a href="#">
<img src="{{skin url="images/home_banner_bottom.jpg"}}" />
</a>
</div>
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
