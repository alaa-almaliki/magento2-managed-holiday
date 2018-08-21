<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Api\Data;

/**
 * Interface HolidayInterface
 *
 * @package Alaa\ManagedHoliday\Api\Data
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
interface HolidayInterface
{
    const TABLE_NAME = 'alaa_managed_holiday';

    const ATTRIBUTE_ID = 'holiday_id';
    const ATTRIBUTE_TITLE = 'title';
    const ATTRIBUTE_REASON = 'reason';
    const ATTRIBUTE_HOLIDAY_DATE = 'holiday_date';
    const ATTRIBUTE_STORE_ID = 'store_id';
    const ATTRIBUTE_IS_ACTIVE = 'is_active';

    /**
     * @param int $id
     * @return HolidayInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $title
     * @return HolidayInterface
     */
    public function setTitle(string $title): HolidayInterface;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $reason
     * @return HolidayInterface
     */
    public function setReason(string $reason): HolidayInterface;

    /**
     * @return string
     */
    public function getReason(): string;

    /**
     * @param string $date
     * @return HolidayInterface
     */
    public function setHolidayDate(string $date): HolidayInterface;

    /**
     * @return string
     */
    public function getHolidayDate(): string;

    /**
     * @param int $storeId
     * @return HolidayInterface
     */
    public function setStoreId(int $storeId): HolidayInterface;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param bool $isActive
     * @return HolidayInterface
     */
    public function setIsActive(bool $isActive): HolidayInterface;

    /**
     * @return bool
     */
    public function getIsActive(): bool;
}
