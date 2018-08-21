<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Api\HolidayRepositoryInterface;
use Alaa\ManagedHoliday\Block\Adminhtml\Edit\GenericButton;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Url;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class GenericButtonTest
 *
 * @package Alaa\ManagedHoliday\Test\Block\Adminhtml\Edit
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class GenericButtonTest extends TestCase
{
    use MockTrait;
    /**
     * @var GenericButton|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var HolidayRepositoryInterface|MockObject
     */
    protected $holidayRepository;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class, ['getRequest', 'getUrlBuilder']);
        $request = $this->getMock(Http::class, ['getParam']);
        $urlBuilder = $this->getMock(Url::class, ['getUrl']);

        $urlBuilder->expects($this->any())
            ->method('getUrl')
            ->willReturn('http:://example.com?id=1');


        $request->expects($this->any())
            ->method('getParam')
            ->willReturn(1);

        $this->context->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($urlBuilder);

        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById']);

        $this->subject = $this->getMock(GenericButton::class, null, [$this->context, $this->holidayRepository]);
    }

    public function testGetHolidayId()
    {
        $holiday = $this->getMockBuilder(Holiday::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $holiday->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->with(1)
            ->willReturn($holiday);

        $holidayId = (int)$this->subject->getHolidayId();
        $this->assertEquals(1, $holidayId);
    }

    public function testGetHolidayIdThrowException()
    {

        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->with(1)
            ->willThrowException(new \Exception());

        $this->subject->getHolidayId();
    }

    public function testGetUrl()
    {
        $this->assertEquals('http:://example.com?id=1', $this->subject->getUrl());
    }
}
