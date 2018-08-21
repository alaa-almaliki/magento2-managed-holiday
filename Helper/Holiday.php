<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Helper;


use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Model\ArrayUtils;
use Alaa\ManagedHoliday\Model\HolidayProvider;
use Alaa\ManagedHoliday\Model\HolidayProviderInterface;
use Alaa\ManagedHoliday\Model\SettingsInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\Timezone;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Holiday
 *
 * @package Alaa\ManagedHoliday\Helper
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Holiday extends AbstractHelper
{
    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var Timezone
     */
    protected $timezone;

    /**
     * @var ArrayUtils
     */
    protected $arrayUtils;

    /**
     * @var HolidayProvider
     */
    protected $holidayProvider;

    /**
     * @var SettingsInterface
     */
    protected $settings;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Holiday constructor.
     *
     * @param Context $context
     * @param HolidayRepositoryInterface $holidayRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Timezone $timezone
     * @param ArrayUtils $arrayUtils
     * @param HolidayProviderInterface $holidayProvider
     * @param SettingsInterface $settings
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        HolidayRepositoryInterface $holidayRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Timezone $timezone,
        ArrayUtils $arrayUtils,
        HolidayProviderInterface $holidayProvider,
        SettingsInterface $settings,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->holidayRepository = $holidayRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timezone = $timezone;
        $this->arrayUtils = $arrayUtils;
        $this->holidayProvider = $holidayProvider;
        $this->settings = $settings;
        $this->storeManager = $storeManager;
    }

    /**
     * Gets whether the given date is a holiday
     * If no date provided, gets the current date whether is a holiday
     *
     * @param  string|null $input date
     * @param  $storeId
     * @return bool
     */
    public function isHoliday(string $input = null, $storeId = null): bool
    {
        $holiday = $this->getHoliday($input, $storeId);
        if (null === $holiday) {
            return false;
        }

        return $holiday->getId() > 0;
    }

    /**
     * @param string|null $input
     * @param null $storeId
     * @return HolidayInterface|null
     */
    public function getHoliday(string $input = null, $storeId = null)
    {
        $this->searchCriteriaBuilder
            ->addFilter('is_active', 1)
            ->addFilter('store_id', $this->getStoreIds($storeId), 'in')
            ->addFilter('holiday_date', $this->timezone->date($input)->format('Y-m-d'));

        $searchCriteria = $this->searchCriteriaBuilder
            ->create()
            ->setCurrentPage(1)
            ->setPageSize(1);

        $holidays = $this->holidayRepository->getList($searchCriteria);
        if ($this->arrayUtils->isEmpty($holidays)) {
            return null;
        }

        $currentHoliday = \current($holidays);
        if (!($currentHoliday instanceof HolidayInterface && $currentHoliday->getId())) {
            return null;
        }

        return $currentHoliday;
    }

    /**
     * @param null|string|int|Store|array $storeId
     * @return array
     */
    protected function getStoreIds($storeId)
    {
        $storeIds = [];
        if (null === $storeId) {
            $storeIds = [$this->storeManager->getStore()->getId()];
        } else {
            if (\is_string($storeId) || \is_numeric($storeId)) {
                $storeIds = [$this->storeManager->getStore($storeId)->getId()];
            } else {
                if ($storeId instanceof Store) {
                    $storeIds = [$storeId->getId()];
                } else {
                    if (\is_array($storeId)) {
                        $storeIds = $storeId;
                    }
                }
            }
        }

        if (!\in_array(Store::DEFAULT_STORE_ID, $storeIds)) {
            $storeIds[] = Store::DEFAULT_STORE_ID;
        }

        return $storeIds;
    }

    /**
     * @param string $from
     * @param string $to
     * @param null|string|int|Store|array $storeId
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function between(string $from, string $to, $storeId = null): array
    {
        $fromDate = $this->timezone->date($from)->format('Y-m-d');
        $toDate = $this->timezone->date($to)->format('Y-m-d');

        $this->searchCriteriaBuilder
            ->addFilter('is_active', 1)
            ->addFilter('store_id', $this->getStoreIds($storeId), 'in')
            ->addFilter('holiday_date', $fromDate, 'gteq')
            ->addFilter('holiday_date', $toDate, 'lt');

        return $this->holidayRepository->getList($this->searchCriteriaBuilder->create());
    }

    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function getHolidays(): array
    {
        return $this->holidayProvider->getHolidays();
    }
}
