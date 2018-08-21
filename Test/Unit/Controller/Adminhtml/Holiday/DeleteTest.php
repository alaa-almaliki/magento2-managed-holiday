<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\Delete;
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
 * Class DeleteTest
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class DeleteTest extends TestCase
{
    use MockTrait;

    /**
     * @var Delete|MockObject
     */
    private $subject;

    /**
     * @var HolidayRepositoryInterface|MockObject
     */
    private $holidayRepository;

    /**
     * @var MockObject|RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var MockObject|Context
     */
    private $context;

    /**
     * @var MockObject|Http
     */
    private $request;

    /**
     * @var Redirect|MockObject
     */
    private $redirect;

    /**
     * @var HolidayInterfaceFactory|MockObject
     */
    private $holidayFactory;

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

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById', 'delete']);
        $this->redirect = $this->getMock(Redirect::class, ['setPath']);
        $this->redirect->expects($this->any())
            ->method('setPath')
            ->willReturnSelf();

        $this->redirectFactory = $this->getMock(RedirectFactory::class, ['create']);
        $this->redirectFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirect);

        $this->redirectFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirect);

        $this->holiday = $this->getMock(Holiday::class, ['getId']);
        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);
        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->subject = $this->getMock(
            Delete::class,
            ['getRequest'],
            [$this->context, $this->holidayRepository, $this->redirectFactory, $this->holidayFactory]
        );

        $this->request = $this->getMock(Http::class, ['getParam']);

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);
    }

    /**
     * @param string $class
     * @param array|null $methods
     * @param array|null $constructorArgs
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getMock(string $class, array $methods = null, array $constructorArgs = null)
    {
        $mock = $this->getMockBuilder($class);
        if (null !== $constructorArgs && \is_array($constructorArgs)) {
            $mock->setConstructorArgs($constructorArgs);
        } else {
            $mock->disableOriginalConstructor();
        }

        return $mock->setMethods($methods)->getMock();
    }

    public function testExecute()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteWithException()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->holidayRepository->expects($this->any())
            ->method('delete')
            ->willThrowException(new \Exception);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteNoHolidayIdParam()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(0);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }
}
