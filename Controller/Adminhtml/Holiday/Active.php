<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;

/**
 * Class Active
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Active extends AbstractController
{
    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var HolidayInterfaceFactory
     */
    protected $holidayFactory;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * Active constructor.
     *
     * @param Action\Context $context
     * @param RedirectFactory $redirectFactory
     * @param HolidayInterfaceFactory $holidayFactory
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        HolidayInterfaceFactory $holidayFactory,
        HolidayRepositoryInterface $holidayRepository
    ) {
        parent::__construct($context);
        $this->redirectFactory = $redirectFactory;
        $this->holidayFactory = $holidayFactory;
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $holidayId = (int)$this->getRequest()->getParam('holiday_id', 0);
        $redirectResult = $this->redirectFactory->create();
        if ($holidayId) {
            $holiday = $this->holidayRepository->getById($holidayId);
            if ($holiday->getId()) {
                try {
                    $isActive = (bool)$this->getRequest()->getParam('active');
                    $holiday->setIsActive($isActive);
                    $this->holidayRepository->save($holiday);
                    $this->messageManager->addSuccessMessage(
                        __('Holiday is %1', ($isActive ? 'Activated' : 'Deactivated'))
                    );
                    return $redirectResult->setPath('*/*/');
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    return $redirectResult->setPath('*/*/');
                }
            }
        }
        $this->messageManager->addErrorMessage(__('Holiday is no longer exist'));
        return $redirectResult->setPath('*/*/');
    }
}
