<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Api\HolidaySearchRepositoryInterface;
use Alaa\ManagedHoliday\Helper\Holiday;

/**
 * Class HolidayRepository
 *
 * @package Alaa\ManagedHoliday\Model\Webapi
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidaySearchRepository implements HolidaySearchRepositoryInterface
{
    /**
     * @var Holiday
     */
    protected $holidayHelper;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * HolidayRepository constructor.
     *
     * @param Holiday $holidayHelper
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(Holiday $holidayHelper, HolidayRepositoryInterface $holidayRepository)
    {
        $this->holidayHelper = $holidayHelper;
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @param int $id
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface|null
     */
    public function getById(int $id)
    {
        try {
            return $this->holidayRepository->getById($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $input
     * @param string|int|\Magento\Store\Model\Store|array $storeId
     * @return bool
     */
    public function isHoliday(string $input = null, $storeId = null): bool
    {
        return $this->holidayHelper->isHoliday($input, $storeId);
    }

    /**
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]
     */
    public function nextHolidays(): array
    {
        return $this->holidayHelper->getHolidays();
    }

    /**
     * @param string $from
     * @param string $to
     * @param int|null $storeId
     * @return \Alaa\ManagedHoliday\Api\Data\HolidayInterface[]|array
     */
    public function between(string $from, string $to, $storeId = null): array
    {
        return $this->holidayHelper->between($from, $to, $storeId);
    }
}
