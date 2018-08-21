<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\Data\HolidaySearchResultInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Helper\SearchResultHelper;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\Timezone;

/**
 * Class HolidayRepository
 *
 * @package Alaa\ManagedHoliday\Model
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class HolidayRepository implements HolidayRepositoryInterface
{
    /**
     * @var ResourceModel\Holiday
     */
    protected $holidayResource;

    /**
     * @var HolidayInterfaceFactory
     */
    protected $holidayFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchResultHelper
     */
    protected $searchResultHelper;

    /**
     * @var HolidaySearchResultInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var Timezone
     */
    protected $timezone;

    /**
     * @var HolidayStoreId
     */
    protected $holidayStoreId;

    /**
     * HolidayRepository constructor.
     *
     * @param ResourceModel\Holiday $holidayResource
     * @param HolidayInterfaceFactory $holidayFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultHelper $searchResultHelper
     * @param HolidaySearchResultInterfaceFactory $searchResultFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param FilterBuilder $filterBuilder
     * @param Timezone $timezone
     * @param HolidayStoreId $holidayStoreId
     */
    public function __construct(
        ResourceModel\Holiday $holidayResource,
        HolidayInterfaceFactory $holidayFactory,
        CollectionFactory $collectionFactory,
        SearchResultHelper $searchResultHelper,
        HolidaySearchResultInterfaceFactory $searchResultFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        FilterBuilder $filterBuilder,
        Timezone $timezone,
        HolidayStoreId $holidayStoreId
    ) {
        $this->holidayResource = $holidayResource;
        $this->holidayFactory = $holidayFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultHelper = $searchResultHelper;
        $this->searchResultFactory = $searchResultFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->timezone = $timezone;
        $this->holidayStoreId = $holidayStoreId;
    }

    /**
     * @param HolidayInterface $holiday
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @return void
     */
    public function save(HolidayInterface $holiday)
    {
        $this->holidayResource->save($holiday);
    }

    /**
     * @param HolidayInterface $holiday
     * @return mixed|void
     * @throws \Exception
     */
    public function delete(HolidayInterface $holiday)
    {
        $this->holidayResource->delete($holiday);
    }

    /**
     * @param int $id
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id)
    {
        /**
         * @var HolidayInterface $holiday
         */
        $holiday = $this->holidayFactory->create();

        $this->holidayResource->load($holiday, $id);

        if (!$holiday->getId()) {
            throw new NoSuchEntityException(__('No Such Holiday'));
        }

        $storeId = $this->holidayStoreId->setHoliday($holiday)->toInt();
        $holiday->setStoreId($storeId);

        return $holiday;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|\Magento\Framework\DataObject[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->searchResultHelper->addFiltersToCollection($searchCriteria, $collection);
        $this->searchResultHelper->addPagingToCollection($searchCriteria, $collection);
        $this->searchResultHelper->addSortOrderToCollection($searchCriteria, $collection);
        $collection->load();
        $searchResults = $this->searchResultFactory->create();
        return $this->searchResultHelper->buildSearchResult($searchResults, $searchCriteria, $collection)->getItems();
    }
}
