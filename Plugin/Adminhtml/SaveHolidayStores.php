<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Plugin\Adminhtml;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Magento\Framework\App\RequestInterface;

/**
 * Class SaveHolidayStores
 *
 * @package Alaa\ManagedHoliday\Plugin\Adminhtml
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SaveHolidayStores
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HolidayResource
     */
    protected $holidayResource;

    /**
     * SaveHolidayStores constructor.
     *
     * @param RequestInterface $request
     * @param HolidayResource $holidayResource
     */
    public function __construct(RequestInterface $request, HolidayResource $holidayResource)
    {
        $this->request = $request;
        $this->holidayResource = $holidayResource;
    }

    /**
     * @param HolidayRepositoryInterface $subject
     * @param \Closure $proceed
     * @param HolidayInterface $holiday
     */
    public function aroundSave(HolidayRepositoryInterface $subject, \Closure $proceed, HolidayInterface $holiday)
    {
        $proceed($holiday);
        $postData = $this->request->getPostValue();
        if (!empty($postData) && \array_key_exists('store_id', $postData)) {
            $storeIds = $postData['store_id'];
            $this->holidayResource->saveHolidayStores($holiday, $storeIds);
        }
    }
}
