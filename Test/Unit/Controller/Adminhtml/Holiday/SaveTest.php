<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\Save;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\DataPersistor;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\Manager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SaveTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SaveTest extends TestCase
{
    use MockTrait;

    /**
     * @var MockObject|Save
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var RedirectFactory|MockObject
     */
    protected $redirectFactory;

    /**
     * @var HolidayInterfaceFactory|MockObject
     */
    protected $holidayFactory;

    /**
     * @var HolidayRepositoryInterface|MockObject
     */
    protected $holidayRepository;

    /**
     * @var DataPersistorInterface|MockObject
     */
    protected $dataPersister;

    /**
     * @var Redirect|MockObject
     */
    protected $redirect;

    /**
     * @var Http|MockObject
     */
    protected $request;

    /**
     * @var Holiday|MockObject
     */
    protected $holiday;

    /**
     * @var Manager|MockObject
     */
    protected $messageManager;

    public function setUp()
    {
        $this->messageManager = $this->getMock(
            Manager::class,
            ['addErrorMessage', 'addSuccessMessage', 'addExceptionMessage']
        );
        $this->context = $this->getMock(Context::class, ['getMessageManager']);
        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->redirectFactory = $this->getMock(RedirectFactory::class, ['create']);
        $this->redirect = $this->getMock(Redirect::class, ['setPath']);
        $this->redirectFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirect);
        $this->redirect->expects($this->any())
            ->method('setPath')
            ->willReturnSelf();

        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);
        $this->holiday = $this->getMock(Holiday::class, ['setHolidayDate']);
        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById', 'save']);
        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->holiday);

        $this->dataPersister = $this->getMock(DataPersistor::class, ['clear', 'set']);

        $this->subject = $this->getMock(
            Save::class, ['getRequest'], [
                $this->context,
                $this->redirectFactory,
                $this->holidayFactory,
                $this->holidayRepository,
                $this->dataPersister
            ]
        );
        $this->request = $this->getMock(Http::class, ['getParam', 'getPostValue']);
        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);
    }

    public function testExecuteWithNullPostValue()
    {
        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn(null);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteWithHolidayNoLongerExists()
    {
        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn(['holiday_id' => 1]);

        $this->holiday->setId(0);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteSuccessWithBackButton()
    {
        $postData = [
            'holiday_id' => 0,
            'title' => 'New Year',
            'reason' => 'New year',
            'holiday_date' => '2018-1-1',
            'is_active' => true,
        ];

        $this->holiday->setId(0);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(true);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteSuccess()
    {
        $postData = [
            'holiday_id' => 0,
            'title' => 'New Year',
            'reason' => 'New year',
            'holiday_date' => '2018-1-1',
            'is_active' => true,
        ];

        $this->holiday->setId(0);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(false);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteWithException()
    {
        $postData = [
            'holiday_id' => 0,
            'title' => 'New Year',
            'reason' => 'New year',
            'holiday_date' => '2018-1-1',
            'is_active' => true,
        ];

        $this->holiday->setId(0);

        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn($postData);

        $this->holidayRepository->expects($this->any())
            ->method('save')
            ->willThrowException(new \Exception);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }
}
