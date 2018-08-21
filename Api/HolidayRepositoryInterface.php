<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Api;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface HolidayRepositoryInterface
 *
 * @package Alaa\ManagedHoliday\Api
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
interface HolidayRepositoryInterface
{
    /**
     * @param \Alaa\ManagedHoliday\Api\Data\HolidayInterface $holiday
     * @throws \Exception
     * @return void
     */
    public function save(HolidayInterface $holiday);

    /**
     * @param HolidayInterface $holiday
     * @return mixed
     */
    public function delete(HolidayInterface $holiday);

    /**
     * @param int $id
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface
     */
    public function getById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
