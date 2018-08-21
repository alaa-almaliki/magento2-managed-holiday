<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

/**
 * Interface SettingsInterface
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface SettingsInterface
{
    const XML_PATH_MANAGED_HOLIDAY_SETTINGS = 'managed_holiday/settings/%s';

    /**
     * @return bool
     */
    public function isEnabled(): bool;
}
