<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Setup;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class HolidaySetup
 *
 * @package Alaa\ManagedHoliday\Setup
 */
class HolidaySetup
{
    /**
     * @var HolidayInterfaceFactory
     */
    protected $holidayFactory;

    /**
     * @var HolidayResource
     */
    protected $holidayResource;

    /**
     * HolidaySetup constructor.
     *
     * @param HolidayInterfaceFactory $holidayFactory
     * @param HolidayResource $holidayResource
     */
    public function __construct(
        HolidayInterfaceFactory $holidayFactory,
        HolidayResource $holidayResource
    ) {
        $this->holidayFactory = $holidayFactory;
        $this->holidayResource = $holidayResource;
    }

    /**
     * @param array $data
     * @return HolidaySetup
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function insertHolidays(array $data): self
    {
        foreach ($data as $holidayData) {
            $this->insertHoliday($holidayData);
        }

        return $this;
    }

    /**
     * @param array $holidayData
     * @return HolidaySetup
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function insertHoliday(array $holidayData): self
    {
        $this->validateStoreIds($holidayData);

        $holidayStores = $holidayData['store_id'];
        if (!\is_array($holidayStores)) {
            $holidayStores = [$holidayStores];
        }

        unset($holidayData['store_id']);
        $holiday = $this->holidayFactory->create()->setData($holidayData);
        $this->holidayResource->save($holiday);
        $this->holidayResource->saveHolidayStores($holiday, $holidayStores);
        return $this;
    }

    /**
     * @param array $data
     * @return HolidaySetup
     * @throws LocalizedException
     */
    private function validateStoreIds(array $data): self
    {
        if (!\array_key_exists('store_id', $data)) {
            throw new LocalizedException(__('Holiday data must have store ids'));
        }
        return $this;
    }
}
