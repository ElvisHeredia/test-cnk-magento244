<?php

namespace Conekta\Payments\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\{InstallSchemaInterface, ModuleContextInterface, SchemaSetupInterface};
use Zend_Db_Exception;

/**
 * Class InstallSchema
 * @package Conekta\Payments\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (! $installer->tableExists('conekta_salesorder')) {
            $this->addConektaSalesOrderTable($setup);
        }
        if (! $installer->tableExists('conekta_quote')) {
            $this->addConektaOrderQuote($setup);
        }

        $installer->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function addConektaSalesOrderTable(SchemaSetupInterface $setup): void
    {
        $installer = $setup;
        $table = $installer->getConnection()->newTable(
            $installer->getTable('conekta_salesorder')
        )
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true
            ],
            'Conekta Sales Order ID'
        )
        ->addColumn(
            'conekta_order_id',
            Table::TYPE_TEXT,
            150,
            ['nullable' => false],
            'Conekta Order'
        )
        ->addColumn(
            'increment_order_id',
            Table::TYPE_TEXT,
            150,
            ['nullable' => false],
            'Sales Order Increment Id'
        )
        ->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
        )
        ->setComment('Conekta Orders Table');
        $installer->getConnection()->createTable($table);

        $installer->getConnection()->addIndex(
            $installer->getTable('conekta_salesorder'),
            $setup->getIdxName(
                $installer->getTable('conekta_salesorder'),
                ['conekta_order_id', 'increment_order_id'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['conekta_order_id', 'increment_order_id'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function addConektaOrderQuote(SchemaSetupInterface $setup): void
    {
        $installer = $setup;
        $table = $installer->getConnection()->newTable(
            $installer->getTable('conekta_quote')
        )
        ->addColumn(
            'quote_id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => false,
                'nullable' => false,
                'primary'  => true,
                'unsigned' => true
            ],
            'Conekta Quote ID'
        )
        ->addColumn(
            'conekta_order_id',
            Table::TYPE_TEXT,
            150,
            ['nullable' => false],
            'Conekta Order'
        )
        ->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
        )
        ->addForeignKey(
            $installer->getFkName(
                'conekta_quote',
                'quote_id',
                'quote',
                'entity_id'
            ),
            'quote_id',
            $installer->getTable('quote'),
            'entity_id',
            Table::ACTION_CASCADE
        )
        ->setComment('Map Table Conekta Orders and Quotes');
        $installer->getConnection()->createTable($table);

        $installer->getConnection()->addIndex(
            $installer->getTable('conekta_quote'),
            $setup->getIdxName(
                $installer->getTable('conekta_quote'),
                ['conekta_order_id'],
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['conekta_order_id'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }
}
