<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Api;

/**
 * Interface HolidaySearchRepositoryInterface
 *
 * @package Alaa\ManagedHoliday\Api
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface HolidaySearchRepositoryInterface
{
    /**
     * @param int $id
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface|null
     */
    public function getById(int $id);

    /**
     * @param string $input
     * @param string|int|\Magento\Store\Model\Store|array $storeId
     * @return bool
     */
    public function isHoliday(string $input = null, $storeId = null): bool;

    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function nextHolidays(): array;

    /**
     * @param string $from
     * @param string $to
     * @param string|int|\Magento\Store\Model\Store|array|null $storeId $storeId
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function between(string $from, string $to, $storeId = null): array;
}
