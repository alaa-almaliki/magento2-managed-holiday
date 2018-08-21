<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Edit extends AbstractController
{
    /**
     * @var HolidayInterfaceFactory
     */
    protected $holidayFactory;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Edit constructor.
     *
     * @param Action\Context $context
     * @param HolidayInterfaceFactory $holidayFactory
     * @param HolidayRepositoryInterface $holidayRepository
     * @param Registry $registry
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Action\Context $context,
        HolidayInterfaceFactory $holidayFactory,
        HolidayRepositoryInterface $holidayRepository,
        Registry $registry,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->holidayFactory = $holidayFactory;
        $this->holidayRepository = $holidayRepository;
        $this->registry = $registry;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $holidayId = (int)$this->getRequest()->getParam('holiday_id', 0);
        if ($holidayId !== 0) {
            try {
                $holiday = $this->holidayRepository->getById($holidayId);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This Holiday no longer exists.'));
                /**
                 * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
                 */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }

            $this->registry->register('holiday', $holiday);
        } else {
            $holiday = $this->holidayFactory->create();
        }

        $resultPage = $this->pageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $holiday ? __('Edit Holiday') : __('New Holiday'),
            $holiday ? __('Edit Holiday') : __('New Holiday')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Holiday'));
        $resultPage->getConfig()->getTitle()->prepend($holiday->getId() ? $holiday->getTitle() : __('New Holiday'));
        return $resultPage;
    }

    /**
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage)
    {
        $resultPage->setActiveMenu('Alaa_ManagedHoliday::holiday')
            ->addBreadcrumb(__('Holiday'), __('Holiday'))
            ->addBreadcrumb(__('Holiday'), __('Holiday'));
        return $resultPage;
    }
}
