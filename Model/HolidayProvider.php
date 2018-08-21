<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Stdlib\DateTime\Timezone;

/**
 * Class HolidayIterator
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayProvider implements HolidayProviderInterface
{
    /**
     * @var HolidayInterface[]
     */
    protected $holidays = [];

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var Timezone
     */
    protected $timezone;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var ArrayUtils
     */
    protected $arrayUtils;

    /**
     * HolidayIterator constructor.
     *
     * @param HolidayRepositoryInterface $holidayRepository
     * @param Timezone $timezone
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param ArrayUtils $arrayUtils
     */
    public function __construct(
        HolidayRepositoryInterface $holidayRepository,
        Timezone $timezone,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        ArrayUtils $arrayUtils
    ) {
        $this->holidayRepository = $holidayRepository;
        $this->timezone = $timezone;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->arrayUtils = $arrayUtils;
    }

    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function getHolidays(): array
    {
        if (!$this->arrayUtils->isEmpty($this->holidays)) {
            return $this->holidays;
        }

        $this->searchCriteriaBuilder
            ->addFilter('holiday_date', $this->timezone->date()->format('y-m-d'), 'gteq')
            ->addFilter('is_active', 1)
            ->addSortOrder($this->getSortOrder())
            ->setPageSize(10);

        $holidays = $this->holidayRepository->getList($this->searchCriteriaBuilder->create());

        if ($this->arrayUtils->isEmpty($holidays)) {
            return $this->holidays;
        }

        $this->holidays = $holidays;
        return $this->holidays;
    }

    /**
     * @return SortOrder
     */
    protected function getSortOrder(): SortOrder
    {
        return $this->sortOrderBuilder
            ->setField('holiday_date')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();
    }
}
