<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class HolidayStoreId
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayStoreId
{
    /**
     * @var HolidayResource
     */
    protected $holidayResource;

    /**
     * @var Holiday
     */
    protected $holiday;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * HolidayStore constructor.
     *
     * @param HolidayResource $holidayResource
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(HolidayResource $holidayResource, StoreManagerInterface $storeManager)
    {
        $this->holidayResource = $holidayResource;
        $this->storeManager = $storeManager;
    }

    /**
     * @param HolidayInterface $holiday
     * @return HolidayStoreId
     */
    public function setHoliday(HolidayInterface $holiday): self
    {
        $this->holiday = $holiday;
        return $this;
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function toInt(): int
    {
        $storeIds = $this->holidayResource->getStoreIds((int)$this->holiday->getId());
        $currentStore = $this->storeManager->getStore();
        $storesCount = \count($storeIds);

        if (\in_array($currentStore->getId(), $storeIds)) {
            return (int)$currentStore->getId();
        }

        if ($storesCount >= 1) {
            return (int)\array_shift($storeIds);
        }

        throw new LocalizedException(__('No store found for holiday with id: %1', $this->holiday->getId()));
    }
}
