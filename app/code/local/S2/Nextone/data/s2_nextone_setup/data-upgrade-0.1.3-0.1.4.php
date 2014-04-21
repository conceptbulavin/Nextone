<?php

$installer = $this;

/* Author attribute */

//Create attribute
$installer->addAttribute('catalog_product', 'delivery_period', array(
    'group'         => 'Additional Information',
    'input'         => 'textarea',
    'type'          => 'text',
    'label'         => 'Delivery Period',
    'value'         => 'Товар будет доставлен в течении 10 - 16 дней',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'global'        => 0,
    'used_in_product_listing' => 0,
));
