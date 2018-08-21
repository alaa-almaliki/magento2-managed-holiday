<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Ui\Holiday;

use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Alaa\ManagedHoliday\Ui\DataProvider\Holiday\HolidayDataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayDataProviderTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Ui\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayDataProviderTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidayDataProvider|MockObject
     */
    protected $subject;

    /**
     * @var CollectionFactory|MockObject
     */
    protected $collectionFactory;

    /**
     * @var Collection|MockObject
     */
    protected $collection;

    /**
     * @var Holiday|MockObject;
     */
    protected $holiday;

    /**
     * @var HolidayResource|MockObject
     */
    protected $holidayResource;

    public function setUp()
    {
        $this->collectionFactory = $this->getMock(CollectionFactory::class, ['create']);
        $this->collection = $this->getMock(Collection::class, ['load', 'isLoaded', 'toArray']);
        $this->collectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);

        $this->holiday = $this->getMock(Holiday::class);
        $this->collection->expects($this->any())
            ->method('toArray')
            ->willReturn(['items' => [$this->holiday]]);

        $this->holidayResource = $this->getMock(HolidayResource::class, ['getStoreIds']);

        $this->subject = $this->getMock(
            HolidayDataProvider::class,
            null,
            ['holiday', 'holiday_id', 'holiday_id', $this->collectionFactory, $this->holidayResource]
        );

    }

    public function testGetData()
    {
        $this->collection->expects($this->any())
            ->method('isLoaded')
            ->willReturn(false);

        $this->holiday->setData('holiday_id', 1);

        $data = $this->subject->getData();
        $this->assertTrue(\is_array($data));
        $this->assertNotEmpty($data);
    }
}
