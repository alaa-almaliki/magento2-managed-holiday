<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Save extends AbstractController
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
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param RedirectFactory $redirectFactory
     * @param HolidayInterfaceFactory $holidayFactory
     * @param HolidayRepositoryInterface $holidayRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        RedirectFactory $redirectFactory,
        HolidayInterfaceFactory $holidayFactory,
        HolidayRepositoryInterface $holidayRepository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->redirectFactory = $redirectFactory;
        $this->holidayFactory = $holidayFactory;
        $this->holidayRepository = $holidayRepository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        $postData = $this->getRequest()->getPostValue();
        $holiday = $this->holidayFactory->create();
        if ($postData) {
            $holidayId = (int)$postData['holiday_id'];
            if (0 !== $holidayId) {
                $holiday = $this->holidayRepository->getById($holidayId);
                if (!$holiday->getId()) {
                    $this->messageManager->addErrorMessage(__('The Holiday is no longer exists'));
                    return $redirect->setPath('*/*/');
                }
            }

            $holiday->setTitle($postData['title'])
                ->setReason($postData['reason'])
                ->setHolidayDate($postData['holiday_date'])
                ->setIsActive((bool)$postData['is_active']);

            try {
                $this->holidayRepository->save($holiday);
                $this->messageManager->addSuccessMessage(
                    __(
                        'Holiday with id %1 is saved successfully',
                        $holiday->getId()
                    )
                );
                $this->dataPersistor->clear('holiday');
                if ($this->getRequest()->getParam('back')) {
                    return $redirect->setPath(
                        '*/*/edit',
                        ['holiday_id' => $this->getRequest()->getParam('holiday_id')]
                    );
                }
                return $redirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e);
            }

            $this->dataPersistor->set('holiday', $postData);
            return $redirect->setPath('*/*/edit', ['holiday_id' => $this->getRequest()->getParam('holiday_id')]);
        }

        return $redirect->setPath('*/*/');
    }
}
