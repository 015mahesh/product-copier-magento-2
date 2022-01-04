<?php
/**
 * @package     MP\ProductCopier
 * @version     1.0.0
 * @author      Mahesh Patel
 * @copyright   Copyright © 2021 Mahesh Patel.
 */
namespace MP\ProductCopier\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package MP\ProductCopier\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mp_product_copier_queue_list')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
             'identity' => true,
             'nullable' => false,
             'primary'  => true,
            ],
            'ID'
        )->addColumn(
            'entity_type',
            Table::TYPE_TEXT,
            50,
            ['nullable' => true],
            'Type'
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            12,
            ['nullable' => true],
            'Id'
        )->addColumn(
            'response',
            Table::TYPE_TEXT,
            500,
            ['nullable' => true],
            'Message'
        )->addColumn(
            'attempt',
            Table::TYPE_INTEGER,
            1,
            ['nullable' => true],
            'Attempt'
        )->addColumn(
            'datetime',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Updated At'
        )->addColumn(
            'status',
            Table::TYPE_INTEGER,
            1,
            ['nullable' => true],
            'Status'
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
