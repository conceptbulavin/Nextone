<?php
/**
 * Create tables
 */

/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

/**
 * Create table 'slider/slider'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('slider/slider'))
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Slider ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slider Title')
    ->addColumn('identifier', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slider String Identifier')
    ->addColumn('width', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default' => '480',
        ), 'Slider Width')
    ->addColumn('height', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default' => '200',
        ), 'Slider Height')
    ->addColumn('effect', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Transition Effect')
    ->addColumn('class', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Container Class')
    ->addColumn('duration', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default' => '400',
        ), 'Duration')
    ->addColumn('frequency', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default' => '4000',
        ), 'Frequency')
    ->addColumn('autoslide', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
        ), 'Is Auto Start')
    ->addColumn('controls', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
        ), 'Enable Controls')
    ->addColumn('pagination', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
        ), 'Enable Pagination')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
        ), 'Is Slider Active')
    ->setComment('ISM Slider Table');
$installer->getConnection()->createTable($table);

/**
 * Create table 'slider/slider_slides'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('slider/slider_slides'))
    ->addColumn('slide_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity' => true,
        'nullable' => false,
        'primary' => true,
        ), 'Slide ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slide Title')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slide Image')
    ->addColumn('link', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slide URL Link')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
        ), 'Slide Description')
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '0',
        ), 'Slider ID')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '0',
        ), 'Sort Order')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable' => false,
        'default' => '1',
        ), 'Is Slide Active')
    ->setComment('ISM Slider Slides');
$installer->getConnection()->createTable($table);

$installer->endSetup();
