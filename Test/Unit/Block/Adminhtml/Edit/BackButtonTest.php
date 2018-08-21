<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Block\Adminhtml\Edit\BackButton;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class BackButtonTest
 *
 * @package Alaa\ManagedHoliday\Test\Block\Adminhtml\Edit
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class BackButtonTest extends TestCase
{
    use MockTrait;

    /**
     * @var BackButton | MockObject
     */
    private $subject;

    public function setUp()
    {
        $this->subject = $this->getMock(BackButton::class, ['getUrl']);
    }

    public function testGetButtonData()
    {
        $this->assertTrue(\is_array($this->subject->getButtonData()));
    }
}
