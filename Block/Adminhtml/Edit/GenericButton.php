<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Magento\Backend\Block\Widget\Context;

/**
 * Class GenericButton
 *
 * @package Alaa\ManagedHoliday\Block\Adminhtml\Edit
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * GenericButton constructor.
     *
     * @param Context $context
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(Context $context, HolidayRepositoryInterface $holidayRepository)
    {
        $this->context = $context;
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @return int|null
     */
    public function getHolidayId(): int
    {
        try {
            return (int)$this->holidayRepository->getById(
                (int)$this->context->getRequest()->getParam('holiday_id', 0)
            )->getId();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
