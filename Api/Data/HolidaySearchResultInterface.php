<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Api\Data;

use Magento\Framework\Data\SearchResultInterface;

/**
 * Interface HolidaySearchResultInterface
 *
 * @package Alaa\ManagedHoliday\Api\Data
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
interface HolidaySearchResultInterface extends SearchResultInterface
{
    /**
     * @param \Alaa\ManagedHoliday\Api\Data\HolidayInterface[] $items
     * @return \Alaa\ManagedHoliday\Api\Data\HolidaySearchResultInterface
     */
    public function setItems(array $items);

    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]
     */
    public function getItems();
}
