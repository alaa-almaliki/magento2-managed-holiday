<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Ui\DataProvider\Holiday;

use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class HolidayDataProvider
 *
 * @package Alaa\ManagedHoliday\Ui\DataProvider\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayDataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var HolidayResource
     */
    protected $holidayResource;

    /**
     * HolidayDataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param HolidayResource $holidayResource
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        HolidayResource $holidayResource,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->holidayResource = $holidayResource;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!$this->collection->isLoaded()) {
            $this->collection->addFieldToSelect('*');
        }

        $data = parent::getData();

        foreach ($data['items'] as &$item) {
            $item['store_id'] = $this->holidayResource->getStoreIds((int)$item['holiday_id']);
        }

        return $data;
    }
}
