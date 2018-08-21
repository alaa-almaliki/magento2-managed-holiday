<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\Edit;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class EditTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class EditTest extends TestCase
{
    use MockTrait;
    /**
     * @var Edit|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var Holiday|MockObject
     */
    protected $holiday;

    /**
     * @var HolidayRepository|MockObject
     */
    protected $holidayRepository;

    /**
     * @var Registry|MockObject
     */
    protected $registry;

    /**
     * @var PageFactory|MockObject
     */
    protected $pageFactory;

    /**
     * @var Http|MockObject
     */
    protected $request;

    /**
     * @var ManagerInterface|MockObject
     */
    protected $messageManager;

    /**
     * @var Page|MockObject
     */
    protected $resultPage;

    /**
     * @var Config|MockObject
     */
    protected $pageConfig;

    /**
     * @var Title|MockObject
     */
    protected $pageTitle;

    /**
     * @var HolidayFactory|MockObject
     */
    protected $holidayFactory;

    /**
     * @var RedirectFactory|MockObject
     */
    protected $redirectPageFactory;

    /**
     * @var Redirect|MockObject
     */
    protected $redirectPage;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class, ['getMessageManager', 'getResultRedirectFactory']);
        $this->messageManager = $this->getMock(Manager::class, ['addErrorMessage']);
        $this->redirectPage = $this->getMock(Redirect::class, ['setPath']);
        $this->redirectPageFactory = $this->getMock(Redirect::class, ['create']);
        $this->redirectPageFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirectPage);
        $this->redirectPage->expects($this->any())
            ->method('setPath')
            ->willReturnSelf();

        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);
        $this->context->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->redirectPageFactory);

        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);
        $this->holiday = $this->getMock(Holiday::class);
        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById']);
        $this->registry = $this->getMock(Registry::class, ['register']);

        $this->pageFactory = $this->getMock(PageFactory::class, ['create']);
        $this->resultPage = $this->getMock(Page::class, ['getConfig', 'setActiveMenu', 'addBreadcrumb']);
        $this->pageFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->resultPage);

        $this->resultPage->expects($this->any())
            ->method('setActiveMenu')
            ->willReturnSelf();
        $this->resultPage->expects($this->any())
            ->method('addBreadcrumb')
            ->willReturnSelf();

        $this->pageConfig = $this->getMock(Config::class, ['getTitle']);
        $this->pageTitle = $this->getMock(Title::class, ['prepend']);
        $this->resultPage->expects($this->any())
            ->method('getConfig')
            ->willReturn($this->pageConfig);

        $this->pageConfig->expects($this->any())
            ->method('getTitle')
            ->willReturn($this->pageTitle);

        $this->request = $this->getMock(Http::class, ['getParam']);

        $this->subject = $this->getMock(
            Edit::class,
            ['getRequest'],
            [$this->context, $this->holidayFactory, $this->holidayRepository, $this->registry, $this->pageFactory]
        );

        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);
    }

    public function testExecute()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->holiday);

        $this->holiday->setId(1);
        $this->holiday->setTitle('New Year');

        $this->assertInstanceOf(Page::class, $this->subject->execute());
    }

    public function testExecuteWithHolidayException()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willThrowException(new NoSuchEntityException());

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteNoHolidayParamId()
    {
        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturn(0);

        $this->assertInstanceOf(Page::class, $this->subject->execute());
    }
}
