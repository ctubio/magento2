<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/* @var $installer \Magento\Setup\Module\SetupModule */
$installer = $this;

/* @var $connection \Magento\Framework\DB\Adapter\AdapterInterface */
$connection = $installer->getConnection();

$installer->startSetup();

/**
 * Rename/Create table 'variable'
 */
if ($installer->tableExists('core_variable')) {
    $connection->renameTable('core_variable', 'variable');
} else {
    $table = $connection->newTable(
        $installer->getTable('variable')
    )->addColumn(
        'variable_id',
        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
        null,
        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
        'Variable Id'
    )->addColumn(
        'code',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        255,
        [],
        'Variable Code'
    )->addColumn(
        'name',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        255,
        [],
        'Variable Name'
    )->addIndex(
        $installer->getIdxName(
            'variable',
            ['code'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        ),
        ['code'],
        ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
    )->setComment(
        'Variables'
    );
    $connection->createTable($table);
}


/**
 * Rename/Create table 'variable_value'
 */
if ($installer->tableExists('core_variable_value')) {
    $connection->renameTable('core_variable_value', 'variable_value');
    $oldForeignKeys = $connection->getForeignKeys('variable_value');
    foreach ($oldForeignKeys as $foreignKey) {
        $connection->dropForeignKey('variable_value', $foreignKey['FK_NAME']);
    }
} else {
    $table = $connection->newTable(
        $installer->getTable('variable_value')
    )->addColumn(
        'value_id',
        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
        null,
        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
        'Variable Value Id'
    )->addColumn(
        'variable_id',
        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false, 'default' => '0'],
        'Variable Id'
    )->addColumn(
        'store_id',
        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
        null,
        ['unsigned' => true, 'nullable' => false, 'default' => '0'],
        'Store Id'
    )->addColumn(
        'plain_value',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        '64k',
        [],
        'Plain Text Value'
    )->addColumn(
        'html_value',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        '64k',
        [],
        'Html Value'
    )->addIndex(
        $installer->getIdxName(
            'variable_value',
            ['variable_id', 'store_id'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        ),
        ['variable_id', 'store_id'],
        ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
    )->addIndex(
        $installer->getIdxName('variable_value', ['store_id']),
        ['store_id']
    )->setComment(
        'Variable Value'
    );
    $connection->createTable($table);
}
$connection->addForeignKey(
    $installer->getFkName('variable_value', 'store_id', 'store', 'store_id'),
    'variable_value',
    'store_id',
    $installer->getTable('store'),
    'store_id',
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
);
$connection->addForeignKey(
    $installer->getFkName('variable_value', 'variable_id', 'variable', 'variable_id'),
    'variable_value',
    'variable_id',
    $installer->getTable('variable'),
    'variable_id',
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
);

$installer->endSetup();
