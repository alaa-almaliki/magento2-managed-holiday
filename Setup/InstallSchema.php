<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Setup;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Alaa\ManagedHoliday\Setup
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param  SchemaSetupInterface $setup
     * @param  ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $holidayTableName = $installer->getTable(HolidayInterface::TABLE_NAME);
        $holidayTable = $installer->getConnection()->newTable($holidayTableName)
            ->addColumn(
                HolidayInterface::ATTRIBUTE_ID,
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Holiday ID'
            )->addColumn(
                HolidayInterface::ATTRIBUTE_TITLE,
                Table::TYPE_TEXT,
                Table::DEFAULT_TEXT_SIZE,
                ['nullable' => false],
                'Holiday Title'
            )->addColumn(
                HolidayInterface::ATTRIBUTE_REASON,
                Table::TYPE_TEXT,
                Table::DEFAULT_TEXT_SIZE,
                ['nullable' => false],
                'Holiday Reason'
            )->addColumn(
                HolidayInterface::ATTRIBUTE_HOLIDAY_DATE,
                Table::TYPE_DATE,
                null,
                ['nullable' => false]
            )->addColumn(
                HolidayInterface::ATTRIBUTE_IS_ACTIVE,
                Table::TYPE_SMALLINT,
                2,
                ['nullable' => false],
                'Holiday Is Active'
            )->setComment('Managed Holiday Table');

        $holidayTable->addIndex(
            $installer->getIdxName(HolidayInterface::TABLE_NAME, ['holiday_date']),
            ['holiday_date']
        );

        $installer->getConnection()->createTable($holidayTable);

        $holidayStoreTableName = $installer->getTable('alaa_managed_holiday_store');
        $holidayStoreTable = $installer->getConnection()
            ->newTable($holidayStoreTableName)
            ->addColumn(
                'holiday_id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'primary' => true, 'nullable' => false],
                'Holiday ID'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                10,
                ['nullable' => false, 'unsigned' => true],
                'Store ID'
            )->addForeignKey(
                $installer->getFkName('alaa_managed_holiday_store', 'holiday_id', 'alaa_managed_holiday', 'holiday_id'),
                'holiday_id',
                $installer->getTable('alaa_managed_holiday'),
                'holiday_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('alaa_managed_holiday_store', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )->setComment('Holiday Store Table');

        $installer->getConnection()->createTable($holidayStoreTable);

        $installer->endSetup();
    }
}
