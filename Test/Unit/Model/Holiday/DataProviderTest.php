<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model\Holiday;

use Alaa\ManagedHoliday\Model\Holiday\DataProvider;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\App\Request\DataPersistor;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class DataProviderTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class DataProviderTest extends TestCase
{
    use MockTrait;

    /**
     * @var DataProvider|MockObject
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
     * @var DataPersistor|MockObject
     */
    protected $dataPersister;

    /**
     * @var Holiday|MockObject
     */
    protected $holidayResource;

    /**
     * @var \Alaa\ManagedHoliday\Model\Holiday|MockObject
     */
    protected $holiday;

    public function setUp()
    {
        $name = 'Managed Holiday';
        $primaryFieldName = 'holiday_id';
        $requestFieldName = 'holiday_id';
        $this->collectionFactory = $this->getMock(CollectionFactory::class, ['create']);
        $this->collection = $this->getMock(Collection::class, ['addFieldToFilter', 'getItems', 'getNewEmptyItem']);
        $this->collectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);

        $this->dataPersister = $this->getMock(DataPersistor::class, ['get', 'set', 'clear']);
        $this->holidayResource = $this->getMock(Holiday::class, ['getStoreIds']);

        $constructorArgs = [
            $name,
            $primaryFieldName,
            $requestFieldName,
            $this->collectionFactory,
            $this->dataPersister,
            $this->holidayResource
        ];

        $this->holiday = $this->getMock(\Alaa\ManagedHoliday\Model\Holiday::class);
        $this->holiday->setData(
            [
                'holiday_id' => 1,
                'title' => 'New Year Holiday',
                'reason' => 'New Year Holiday',
                'store_id' => 1,
                'holiday_date' => '2018-01-01',
                'is_active' => 1
            ]
        );

        $this->subject = $this->getMock(DataProvider::class, ['getCollection'], $constructorArgs);
        $this->subject->expects($this->any())
            ->method('getCollection')
            ->willReturn($this->collection);
    }

    public function testAddFilter()
    {
        $filter = new \Magento\Framework\Api\Filter;
        $filter->setField('holiday_id');
        $this->subject->addFilter($filter);
    }

    public function testAddFilterNoHolidayIdField()
    {
        $this->subject->addFilter(new \Magento\Framework\Api\Filter);
    }

    public function testGetData()
    {
        $this->collection->expects($this->any())
            ->method('getItems')
            ->willReturn([$this->holiday]);

        $this->holidayResource->expects($this->any())
            ->method('getStoreIds')
            ->willReturn([1]);

        $this->dataPersister->expects($this->any())
            ->method('get')
            ->willReturn([]);

        $this->assertNotEmpty($this->subject->getData());
    }

    public function testGetDataPersisted()
    {
        $this->collection->expects($this->any())
            ->method('getItems')
            ->willReturn([$this->holiday]);

        $this->collection->expects($this->any())
            ->method('getNewEmptyItem')
            ->willReturn($this->holiday);

        $this->holidayResource->expects($this->any())
            ->method('getStoreIds')
            ->willReturn([1]);

        $this->dataPersister->expects($this->any())
            ->method('get')
            ->willReturn(
                [
                    'holiday_id' => 1,
                    'title' => 'New Year Holiday',
                    'reason' => 'New Year Holiday',
                    'store_id' => 1,
                    'holiday_date' => '2018-01-01',
                    'is_active' => 1
                ]
            );

        $this->assertNotEmpty($this->subject->getData());
    }
}
