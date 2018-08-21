<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\MassActive;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\CollectionFactory;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\Manager;
use Magento\Ui\Component\MassAction\Filter;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class MassActionTest
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki<alaa.almaliki@gmail.com>
 */
class MassActiveTest extends TestCase
{
    use MockTrait;
    /**
     * @var MassActive|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var CollectionFactory|MockObject
     */
    protected $holidayCollectionFactory;

    /**
     * @var RedirectFactory|MockObject
     */
    protected $redirectFactory;

    /**
     * @var Filter|MockObject
     */
    protected $filter;

    /**
     * @var HolidayRepository|MockObject;
     */
    protected $holidayRepository;

    /**
     * @var Redirect|MockObject
     */
    protected $redirect;

    /**
     * @var Http|MockObject
     */
    protected $request;

    /**
     * @var Collection|MockObject
     */
    protected $collection;

    /**
     * @var Manager|MockObject
     */
    protected $messageManager;

    /**
     * @var Holiday|MockObject
     */
    protected $holiday;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class, ['getMessageManager']);
        $this->messageManager = $this->getMock(Manager::class, ['addSuccessMessage', 'addErrorMessage']);
        $this->context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->holidayCollectionFactory = $this->getMock(CollectionFactory::class, ['create']);
        $this->collection = $this->getMock(Collection::class, ['load', 'getSize']);
        $this->holidayCollectionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->collection);
        $this->collection->expects($this->any())
            ->method('load')
            ->willReturnSelf();

        $this->redirectFactory = $this->getMock(RedirectFactory::class, ['create']);
        $this->redirect = $this->getMock(Redirect::class, ['setPath']);
        $this->redirectFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->redirect);
        $this->redirect->expects($this->any())
            ->method('setPath')
            ->willReturnSelf();

        $this->filter = $this->getMock(Filter::class, ['getCollection']);
        $this->filter->expects($this->any())
            ->method('getCollection')
            ->willReturn($this->collection);

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['save']);

        $subjectConstructorArguments = [
            $this->context,
            $this->holidayCollectionFactory,
            $this->redirectFactory,
            $this->filter,
            $this->holidayRepository
        ];
        $this->subject = $this->getMock(MassActive::class, ['getRequest'], $subjectConstructorArguments);
        $this->request = $this->getMock(Http::class);
        $this->subject->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);

        $this->holiday = $this->getMock(Holiday::class);
    }

    public function testExecute()
    {
        $this->request->setParam('status', 1);
        $this->collection->addItem($this->holiday);
        $this->collection->expects($this->any())
            ->method('getSize')
            ->willReturn(1);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }

    public function testExecuteWithException()
    {
        $this->request->setParam('status', 1);
        $this->collection->addItem($this->holiday);
        $this->collection->expects($this->any())
            ->method('getSize')
            ->willReturn(1);

        $this->holidayRepository->expects($this->any())
            ->method('save')
            ->willThrowException(new \Exception);

        $this->assertInstanceOf(Redirect::class, $this->subject->execute());
    }
}
