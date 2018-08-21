<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayStoreId;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayStoreIdTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayStoreIdTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidayStoreId|MockObject
     */
    protected $subject;

    /**
     * @var HolidayResource|MockObject
     */
    protected $holidayResource;

    /**
     * @var StoreManagerInterface|MockObject
     */
    protected $storeManager;

    /**
     * @var Store|MockObject
     */
    protected $store;

    /**
     * @var Holiday|MockObject
     */
    protected $holiday;

    public function setUp()
    {
        $this->holidayResource = $this->getMock(HolidayResource::class, ['getStoreIds']);
        $this->storeManager = $this->getMock(StoreManager::class, ['getStore']);
        $this->store = $this->getMock(Store::class, ['getId']);
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $this->holiday = $this->getMock(Holiday::class);

        $this->subject = $this->getMock(HolidayStoreId::class, null, [$this->holidayResource, $this->storeManager]);
        $this->subject->setHoliday($this->holiday);
    }

    public function testToInt()
    {
        $this->holiday->setId(1);
        $this->holidayResource->expects($this->any())
            ->method('getStoreIds')
            ->willReturn([1]);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->assertEquals(1, $this->subject->toInt());
    }

    public function testToIntFirstItems()
    {
        $this->holiday->setId(1);
        $this->holidayResource->expects($this->any())
            ->method('getStoreIds')
            ->willReturn([0]);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->assertEquals(0, $this->subject->toInt());
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @expectedException \Exception
     */
    public function testToIntThrowException()
    {
        $this->holiday->setId(1);
        $this->holidayResource->expects($this->any())
            ->method('getStoreIds')
            ->willReturn([]);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->assertEquals(0, $this->subject->toInt());
    }
}
