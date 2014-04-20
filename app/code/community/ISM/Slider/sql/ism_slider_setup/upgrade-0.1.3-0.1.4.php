<?php
$installer = $this;
$installer->startSetup();

/**
 * Create table 'slider/slider_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('slider/slider_store'))
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
    ), 'Slider ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex($installer->getIdxName('slider/slider_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('slider/slider_store', 'slider_id', 'slider/slider', 'slider_id'),
        'slider_id', $installer->getTable('slider/slider'), 'slider_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('slider/slider_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Slider To Store Linkage Table');
$installer->getConnection()->createTable($table);


$installer->endSetup();
