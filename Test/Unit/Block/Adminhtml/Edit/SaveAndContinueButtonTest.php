<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Block\Adminhtml\Edit\SaveAndContinueButton;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class SaveAndContinueButtonTest extends TestCase
{
    use MockTrait;

    /**
     * @var MockObject|SaveAndContinueButton
     */
    private $subject;

    public function setUp()
    {
        $this->subject = $this->getMock(SaveAndContinueButton::class, ['getHolidayId']);
    }

    public function testGetButtonData()
    {
        $this->subject->expects($this->any())
            ->method('getHolidayId')
            ->willReturn(1);
        $this->assertTrue(\is_array($this->subject->getButtonData()));
    }

    public function testGetButtonDataEmptyData()
    {
        $this->subject->expects($this->any())
            ->method('getHolidayId')
            ->willReturn(0);
        $this->assertTrue(empty($this->subject->getButtonData()));
    }
}
