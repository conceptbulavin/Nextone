<?php
/** @var $config Mage_Core_Model_Config */
$config = Mage::getModel('core/config');
$config->saveConfig('catalog/search/use_layered_navigation_count', 0 );
$config->saveConfig('catalog/recently_products/compared_count', 2 );
$config->saveConfig('catalog/recently_products/viewed_count', 2 );
$config->saveConfig('design/footer/copyright', "&copy; Интернет-магазин «Некстван» 2013
" );
$config->saveConfig('sendfriend_email/email/enabled', 0 );


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$config = array(
    array(
        'id_new'  => 'delivery_and_payment',
        'title'   => 'Доставка и оплата',
        'content' => <<<HTML
Доставка и оплата
HTML
    ),
    array(
        'id_new'  => 'exchange',
        'title'   => 'Обмен',
        'content' => <<<HTML
Обмен
HTML
    ),
    array(
        'id_new'  => 'warranty',
        'title'   => 'Гарантия',
        'content' => <<<HTML
Гарантия
HTML
    ),
    array(
        'id_new'  => 'service',
        'title'   => 'Обслуживание',
        'content' => <<<HTML
Обслуживание
HTML
    ),
    array(
        'id_new'  => 'contacts',
        'title'   => 'Контакты',
        'content' => <<<HTML
<p><b>Вы можете связаться с нами различными способами:</b><br />
800 210 019 – звонки со стационарных телефонов бесплатно, с мобильных телефонов – согласно тарифам Вашего оператора
При звонках с мобильного телефона, Вы можете позвонить по любому из следующих номеров:</p>

<p>050 263 52 24 –  MTC<br />
093 263 24 24 –  Life<br />
097 242 44 50 –  Kyivstar</p>

<p><b>Кроме того, Вы можете позвонить на стационарный телефон:</b>
0412 – 48 47 32 / многоканальный /</p>

<p><b>По электронной почте Вы можете обратиться к нам с помощью онлайн-формы, либо по одному из следующих адресов e-mail:</b>
opt@gurtovka.com – по поводу оптовых закупок<br />
contact@gurtovka.com – общие вопросы, проблемы с заказами или доставкой <br />
director@gurtovka.com – на данный e-mail отправляйте руководителю проекта любые Ваши предложения или жалобы<br />
reklama@gurtovka.com – по вопросам рекламы.</p>

<p><b>Наш почтовый адрес:</b></p>

<p>GURTOVKA<br />
ул. 8 Марта, 13 a<br />
г. Житомир<br />
10029<br />
По данному почтовому адресу Вы можете отправлять нам всю корреспонденцию, а также осуществлять возврат товара, по согласованию с оператором.</p>

<p><b>Контактные данные компании ООО «Амалка Трейд» (Amálka Trade s.r.o.):</b></p>

<p>Amálka Trade s.r.o. ООО «Амалка Трейд»<br />
Holečkova 789/49 ул. Голечкова, 789/49<br />
Praha 5 г. Прага 5<br />
15000 индекс 15000<br />
Czech republic Чешская Республика<br />
info@amalkatrade.cz info@amalkatrade.cz</p>
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
