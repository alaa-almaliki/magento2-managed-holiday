<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 *
 * @package Alaa\ManagedHoliday\Model\Holiday
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var HolidayResource
     */
    protected $holidayResource;

    /**
     * ScriptDataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param HolidayResource $holidayResource
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        HolidayResource $holidayResource,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->meta = $meta;
        $this->holidayResource = $holidayResource;
    }

    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return mixed|void
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() === 'holiday_id') {
            $filter->setField('main_table.holiday_id');
        }

        parent::addFilter($filter);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /**
         * @var $holiday HolidayInterface
         */
        foreach ($items as $holiday) {
            $data = $holiday->getData();
            $data['store_id'] = $this->holidayResource->getStoreIds((int)$holiday->getId());
            $this->loadedData[$holiday->getId()] = $data;
        }

        $data = $this->dataPersistor->get('holiday');
        if (!empty($data)) {
            $holiday = $this->collection->getNewEmptyItem();
            $holiday->setData($data);
            $this->loadedData[$holiday->getId()] = $holiday->getData();
            $this->dataPersistor->clear('holiday');
        }

        return $this->loadedData;
    }
}
