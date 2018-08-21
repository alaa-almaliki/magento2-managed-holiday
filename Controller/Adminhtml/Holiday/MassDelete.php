<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class MassDelete extends AbstractController
{
    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var HolidayRepositoryInterface
     */
    protected $holidayRepository;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * MassDelete constructor.
     *
     * @param Action\Context $context
     * @param RedirectFactory $redirectFactory
     * @param HolidayRepositoryInterface $holidayRepository
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        HolidayRepositoryInterface $holidayRepository,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->redirectFactory = $redirectFactory;
        $this->holidayRepository = $holidayRepository;
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();

            foreach ($collection as $holiday) {
                $this->holidayRepository->delete($holiday);
            }
            $this->messageManager->addSuccessMessage(__('%1 holidays were deleted', $collectionSize));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $redirect->setPath('*/*/');
    }
}
