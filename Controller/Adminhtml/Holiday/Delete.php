<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;

/**
 * Class Delete
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Delete extends AbstractController
{
    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var HolidayInterfaceFactory
     */
    protected $holidayFactory;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param HolidayRepositoryInterface $holidayRepository
     * @param RedirectFactory $redirectFactory
     * @param HolidayInterfaceFactory $holidayFactory
     */
    public function __construct(
        Action\Context $context,
        HolidayRepositoryInterface $holidayRepository,
        RedirectFactory $redirectFactory,
        HolidayInterfaceFactory $holidayFactory
    ) {
        parent::__construct($context);
        $this->holidayRepository = $holidayRepository;
        $this->redirectFactory = $redirectFactory;
        $this->holidayFactory = $holidayFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $holidayId = (int)$this->getRequest()->getParam('holiday_id', 0);
        $redirectResult = $this->redirectFactory->create();
        if ($holidayId > 0) {
            try {
                $this->holidayRepository->delete($this->holidayFactory->create()->setId($holidayId));
                $this->messageManager->addSuccessMessage(
                    __('Holiday with id %1 has been deleted successfully', $holidayId)
                );
                return $redirectResult->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $redirectResult->setPath('*/*/edit', ['holiday_id', $holidayId]);
            }
        }

        $this->messageManager->addErrorMessage(__('Holiday with id %1 is no longer exist', $holidayId));
        return $redirectResult->setPath('*/*/');
    }
}
