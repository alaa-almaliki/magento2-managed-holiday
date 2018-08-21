<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Model\ArrayUtils;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayProvider;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Stdlib\DateTime\Timezone;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayProviderTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayProviderTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidayProvider|MockObject
     */
    protected $subject;

    /**
     * @var HolidayRepository|MockObject
     */
    protected $holidayRepository;

    /**
     * @var Timezone|MockObject
     */
    protected $timezone;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    protected $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder|MockObject
     */
    protected $sortOrderBuilder;

    /**
     * @var ArrayUtils
     */
    protected $arrayUtils;

    public function setUp()
    {
        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getList']);
        $this->timezone = $this->getMock(Timezone::class, ['date']);
        $this->timezone->expects($this->any())
            ->method('date')
            ->willReturn(new \DateTime());

        $this->searchCriteriaBuilder = $this->getMock(SearchCriteriaBuilder::class, ['addFilter', 'create']);
        $this->searchCriteriaBuilder->expects($this->any())
            ->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->any())
            ->method('create')
            ->willReturn(new \Magento\Framework\Api\SearchCriteria());

        $this->sortOrderBuilder = $this->getMock(SortOrderBuilder::class, ['create']);
        $this->sortOrderBuilder->expects($this->any())
            ->method('create')
            ->willReturn(new SortOrder());

        $this->arrayUtils = new ArrayUtils();

        $constructorArgs = [
            $this->holidayRepository,
            $this->timezone,
            $this->searchCriteriaBuilder,
            $this->sortOrderBuilder,
            $this->arrayUtils
        ];
        $this->subject = $this->getMock(HolidayProvider::class, null, $constructorArgs);
    }

    public function testGetHolidays()
    {
        $holiday = $this->getMock(Holiday::class);
        $holiday->setId(1);

        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$holiday]);

        $this->assertEquals([$holiday], $this->subject->getHolidays());
    }

    public function testGetHolidaysEmpty()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([]);

        $this->assertEmpty($this->subject->getHolidays());
    }
}
