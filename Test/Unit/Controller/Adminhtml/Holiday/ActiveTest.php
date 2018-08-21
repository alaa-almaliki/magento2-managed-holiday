<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\Active;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\Manager;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class ActiveTest
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ActiveTest extends TestCase
{
    use MockTrait;

    /**
     * @var MockObject|Active
     */
    private $subject;

    /**
     * @var Context|MockObject
     */
    private $context;

    /**
     * @var RedirectFactory|MockObject
     */
    private $redirectFactory;

    /**
     * @var HolidayInterfaceFactory|MockObject
     */
    private $holidayFactory;

    /**
     * @var HolidayRepositoryInterface|MockObject
     */
    private $holidayRepository;

    /**
     * @var Http|MockObject
     */
    private $request;

    /**
     * @var Redirect|MockObject
     */
    private $redirectResult;

    /**
     * @var HolidayInterface|MockObject
     */
    private $holiday;

    /**
     * @var MockObject|Manager
     */
    private $messageManager;

    public function setUp()
    {
        $this->messageManager = $this->getMock(Manager::class, ['addErrorMessage', 'addSuccessMessage']);

        $this->context = $this->getMock(Context::class, ['getMessageManager']);

        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->redirectResult = $this->getMock(Redirect::class, ['setPath']);

        $this->redirectResult->expects($this->any())
            ->method('setPath')
            ->willReturnSelf();

        $this->redirectFactory = $this->getMock(RedirectFactory::class, ['create']);

        $this->redirectFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirectResult);

        $this->holiday = $this->getMock(Holiday::class);

        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);

        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById', 'save']);

        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->holiday);

        $this->holiday->setId(1);

        $this->request = $this->getMock(Http::class, ['getParam']);

        $this->subject = $this->getMock(
            Active::class,
            ['getRequest'],
            [$this->context, $this->redirectFactory, $this->holidayFactory, $this->holidayRepository]
        );
    }

    public function testExecute()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->subject->execute());
    }

    public function testExecuteNoHolidayIdParam()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(0);

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->subject->execute());
    }

    public function testExecuteHolidayHasNoId()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->holiday->setId(0);

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->subject->execute());
    }

    public function testExecuteWithException()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->holidayRepository->expects($this->any())
            ->method('save')
            ->willThrowException(new \Exception);

        $this->assertInstanceOf(\Magento\Framework\Controller\Result\Redirect::class, $this->subject->execute());
    }
}
