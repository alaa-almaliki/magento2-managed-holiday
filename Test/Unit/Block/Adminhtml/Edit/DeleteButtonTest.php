<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Block\Adminhtml\Edit\DeleteButton;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class DeleteButtonTest
 *
 * @package Alaa\ManagedHoliday\Test\Block\Adminhtml\Edit
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class DeleteButtonTest extends TestCase
{
    use MockTrait;

    /**
     * @var DeleteButton|MockObject
     */
    private $subject;

    public function setUp()
    {
        $this->subject = $this->getMock(DeleteButton::class, ['getUrl', 'getHolidayId']);
    }

    public function testGetButtonData()
    {
        $this->subject->expects($this->any())
            ->method('getHolidayId')
            ->willReturn(1);

        $this->assertTrue(\is_array($this->subject->getButtonData()));
    }

    public function testGetButtonDataNoHolidayId()
    {
        $this->assertTrue(\is_array($this->subject->getButtonData()));
    }
}
