<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Block\Adminhtml\Edit;

use Alaa\ManagedHoliday\Block\Adminhtml\Edit\SaveButton;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class SaveButtonTest extends TestCase
{
    use MockTrait;

    public function testGetButtonData()
    {
        /**
         * @var MockObject|SaveButton $subject
         */
        $subject = $this->getMock(SaveButton::class);
        $this->assertTrue(\is_array($subject->getButtonData()));
    }
}
