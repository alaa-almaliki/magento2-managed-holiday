<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

/**
 * Interface HolidayProviderInterface
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface HolidayProviderInterface
{
    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function getHolidays(): array;
}
