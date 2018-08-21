<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassActivate
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class MassActive extends AbstractController
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * MassActivate constructor.
     *
     * @param Action\Context $context
     * @param CollectionFactory $collectionFactory
     * @param RedirectFactory $redirectFactory
     * @param Filter $filter
     * @param HolidayRepositoryInterface $holidayRepository
     */
    public function __construct(
        Action\Context $context,
        CollectionFactory $collectionFactory,
        RedirectFactory $redirectFactory,
        Filter $filter,
        HolidayRepositoryInterface $holidayRepository
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->redirectFactory = $redirectFactory;
        $this->filter = $filter;
        $this->holidayRepository = $holidayRepository;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $redirectResult = $this->redirectFactory->create();
        try {
            $isActive = (bool)$this->getRequest()->getParam('status');
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();

            /**
             * @var HolidayInterface $holiday
             */
            foreach ($collection as $holiday) {
                $holiday->setIsActive($isActive);
                $this->holidayRepository->save($holiday);
            }

            $this->messageManager->addSuccessMessage(
                __('%1 Holidays were %2', $collectionSize, ($isActive ? 'activated' : 'deactivated'))
            );
            return $redirectResult->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $redirectResult->setPath('*/*/');
        }
    }
}
