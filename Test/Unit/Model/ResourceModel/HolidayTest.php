<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model\ResourceModel;

use Alaa\ManagedHoliday\Model\ResourceModel\Holiday;
use Alaa\ManagedHoliday\Model\Sql\Condition;
use Alaa\ManagedHoliday\Model\Sql\ConditionInterfaceFactory;
use Alaa\ManagedHoliday\Model\Sql\ConditionValidator;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model\ResourceModel
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayTest extends TestCase
{
    use MockTrait;

    /**
     * @var Holiday|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var StoreManagerInterface|MockObject
     */
    protected $storeManager;

    /**
     * @var Store|MockObject
     */
    protected $store;

    /**
     * @var ConditionInterfaceFactory|MockObject
     */
    protected $conditionFactory;

    /**
     * @var Condition
     */
    protected $condition;

    /**
     * @var Mysql|MockObject
     */
    protected $connection;

    /**
     * @var \Alaa\ManagedHoliday\Model\Holiday|MockObject
     */
    protected $holiday;

    /**
     * @var Select|MockObject
     */
    protected $select;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class);
        $this->storeManager = $this->getMock(StoreManager::class, ['getStore']);
        $this->store = $this->getMock(Store::class, ['getId']);
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $this->conditionFactory = $this->getMock(ConditionInterfaceFactory::class, ['create']);
        $this->condition = new Condition(new ConditionValidator());
        $this->conditionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->condition);

        $constructorArgs = [
            $this->context,
            $this->storeManager,
            $this->conditionFactory
        ];

        $this->subject = $this->getMock(Holiday::class, ['_init', 'getConnection'], $constructorArgs);
        $this->connection = $this->getMock(
            Mysql::class,
            ['delete', 'quoteInto', 'insertOnDuplicate', 'select', 'fetchCol']
        );
        $this->select = $this->getMock(Select::class, ['from', 'where']);
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($this->select);

        $this->subject->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->select->expects($this->any())
            ->method('from')
            ->willReturnSelf();

        $this->select->expects($this->any())
            ->method('where')
            ->willReturnSelf();

        $this->holiday = $this->getMock(\Alaa\ManagedHoliday\Model\Holiday::class);
        $this->holiday->setId(1);
    }

    public function testDeleteHolidayStore()
    {
        $this->assertInstanceOf(Holiday::class, $this->subject->deleteHolidayStore($this->holiday, 1));
    }

    public function testSaveHolidayStoresNoHolidayId()
    {
        $this->holiday->setId(0);
        $this->subject->saveHolidayStores($this->holiday, [0]);
    }

    public function testSaveHolidayStores()
    {
        $this->subject->saveHolidayStores($this->holiday, [0]);
    }

    public function testGetStoreIds()
    {
        $this->connection->expects($this->any())
            ->method('fetchCol')
            ->with($this->select)
            ->willReturn([1]);

        $this->assertEquals([1], $this->subject->getStoreIds(1));
    }
}
