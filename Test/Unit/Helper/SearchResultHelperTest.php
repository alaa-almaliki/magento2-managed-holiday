<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit;

use Alaa\ManagedHoliday\Helper\SearchResultHelper;
use Alaa\ManagedHoliday\Model\HolidaySearchResult;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Data\SearchResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SearchResultHelperTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SearchResultHelperTest extends TestCase
{
    use MockTrait;
    /**
     * @var SearchResultHelper|MockObject
     */
    protected $subject;

    /**
     * @var SearchCriteria|MockObject
     */
    protected $searchCriteria;

    /**
     * @var AbstractCollection|MockObject
     */
    protected $collection;

    public function setUp()
    {
        $this->subject = $this->getMock(SearchResultHelper::class);

        $this->searchCriteria = $this->getMock(
            SearchCriteria::class, [
                'getFilterGroups',
                'setOrder',
            ]
        );

        $this->collection = $this->getMock(AbstractCollection::class, ['getSize', 'getItems', 'addFieldToFilter']);
    }

    public function testAddFiltersToCollection()
    {
        $filter = new \Magento\Framework\Api\Filter();
        $filter->setConditionType('eq')
            ->setValue(1)
            ->setField('id');
        $filterGroup = new \Magento\Framework\Api\Search\FilterGroup();
        $filterGroup->setFilters([$filter]);
        $filterGroups = [$filterGroup];
        $this->searchCriteria->expects($this->any())
            ->method('getFilterGroups')
            ->willReturn($filterGroups);

        $this->assertInstanceOf(
            SearchResultHelper::class,
            $this->subject->addFiltersToCollection($this->searchCriteria, $this->collection)
        );
    }

    public function testAddSortOrderToCollection()
    {
        $sortOrder = new SortOrder();
        $sortOrder->setField('id')
            ->setDirection(SortOrder::SORT_ASC);
        $this->searchCriteria->setSortOrders([$sortOrder]);
        $this->assertInstanceOf(
            SearchResultHelper::class,
            $this->subject->addSortOrderToCollection($this->searchCriteria, $this->collection)
        );
    }

    public function testAddPagingToCollection()
    {
        $this->searchCriteria->setPageSize(1);
        $this->searchCriteria->setCurrentPage(1);
        $this->assertInstanceOf(
            SearchResultHelper::class,
            $this->subject->addPagingToCollection($this->searchCriteria, $this->collection)
        );
    }

    public function testBuildSearchResult()
    {
        $this->collection->expects($this->any())
            ->method('getSize')
            ->willReturn(3);
        $this->collection->expects($this->any())
            ->method('getItems')
            ->willReturn(['1', '2', '3']);

        $searchResult = $this->subject->buildSearchResult(
            new HolidaySearchResult(),
            $this->searchCriteria,
            $this->collection
        );

        $this->assertInstanceOf(SearchResultInterface::class, $searchResult);
    }
}
