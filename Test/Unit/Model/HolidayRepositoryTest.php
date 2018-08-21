<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\Data\HolidaySearchResultInterfaceFactory;
use Alaa\ManagedHoliday\Helper\SearchResultHelper;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayFactory;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Model\HolidaySearchResult;
use Alaa\ManagedHoliday\Model\HolidaySearchResultFactory;
use Alaa\ManagedHoliday\Model\HolidayStoreId;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Stdlib\DateTime\Timezone;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayRepositoryTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 */
class HolidayRepositoryTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidayStoreId|MockObject
     */
    public $holidayStoreId;
    /**
     * @var HolidayRepository|MockObject
     */
    protected $subject;
    /**
     * @var HolidayResource\|MockObject
     */
    protected $holidayResource;
    /**
     * @var HolidayFactory|MockObject
     */
    protected $holidayFactory;
    /**
     * @var CollectionFactory|MockObject
     */
    protected $collectionFactory;
    /**
     * @var Collection|MockObject
     */
    protected $collection;
    /**
     * @var Holiday|MockObject
     */
    protected $holiday;
    /**
     * @var SearchResultHelper|MockObject
     */
    protected $searchResultHelper;
    /**
     * @var HolidaySearchResultFactory|MockObject
     */
    protected $holidaySearchResultFactory;
    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    protected $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder|MockObject
     */
    protected $sortOrderBuilder;
    /**
     * @var FilterBuilder|MockObject
     */
    protected $filterBuilder;
    /**
     * @var Timezone|MockObject
     */
    protected $timezone;

    public function setUp()
    {
        $this->holidayResource = $this->getMock(HolidayResource::class, ['save', 'delete', 'load', 'getStoreIds']);

        $this->holiday = $this->getMock(Holiday::class, ['save', 'delete']);
        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);
        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->collectionFactory = $this->getMock(CollectionFactory::class, ['create']);
        $this->collection = $this->getMock(Collection::class, ['load']);
        $this->collectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);

        $this->holidaySearchResultFactory = $this->getMock(HolidaySearchResultInterfaceFactory::class, ['create']);
        $this->searchResultHelper = $this->getMock(
            SearchResultHelper::class,
            ['addFiltersToCollection', 'addPagingToCollection', 'addSortOrderToCollection', 'buildSearchResult']
        );
        $this->searchCriteriaBuilder = $this->getMock(SearchCriteriaBuilder::class);
        $this->sortOrderBuilder = $this->getMock(SortOrderBuilder::class);
        $this->filterBuilder = $this->getMock(FilterBuilder::class);
        $this->timezone = $this->getMock(Timezone::class);
        $this->holidayStoreId = $this->getMock(HolidayStoreId::class, ['toInt']);

        $constructorArgs = [
            $this->holidayResource,
            $this->holidayFactory,
            $this->collectionFactory,
            $this->searchResultHelper,
            $this->holidaySearchResultFactory,
            $this->searchCriteriaBuilder,
            $this->sortOrderBuilder,
            $this->filterBuilder,
            $this->timezone,
            $this->holidayStoreId
        ];

        $this->subject = $this->getMock(HolidayRepository::class, null, $constructorArgs);
    }

    public function testSave()
    {
        $this->subject->save($this->holiday);
    }

    public function testDelete()
    {
        $this->subject->delete($this->holiday);
    }

    public function testGetById()
    {
        $this->holiday->setId(1);
        $this->holidayStoreId->expects($this->any())
            ->method('toInt')
            ->willReturn(1);
        $this->assertInstanceOf(Holiday::class, $this->subject->getById(1));
    }

    public function testGetByIdWithStoreId()
    {
        $this->holiday->setId(1);
        $this->assertInstanceOf(Holiday::class, $this->subject->getById(1, 1));
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testGetByIdWithException()
    {
        $this->assertInstanceOf(Holiday::class, $this->subject->getById(1));
    }

    public function testGetList()
    {
        $holidayResults = new HolidaySearchResult();
        $holidayResults->setItems([$this->holiday]);
        $this->holidaySearchResultFactory->expects($this->any())
            ->method('create')
            ->willReturn($holidayResults);
        $this->searchResultHelper->expects($this->any())
            ->method('buildSearchResult')
            ->willReturn($holidayResults);

        $this->assertEquals($holidayResults->getItems(), $this->subject->getList(new SearchCriteria()));
    }
}
