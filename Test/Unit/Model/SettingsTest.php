<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\App\Config;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SettingsTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SettingsTest extends TestCase
{
    use MockTrait;

    /**
     * @var \Alaa\ManagedHoliday\Model\Settings
     */
    protected $subject;

    /**
     * @var Config|MockObject
     */
    protected $scopeConfig;

    public function setUp()
    {
        $this->scopeConfig = $this->getMock(Config::class, ['isSetFlag']);
        $this->scopeConfig->expects($this->any())
            ->method('isSetFlag')
            ->willReturn(true);

        $this->subject = new \Alaa\ManagedHoliday\Model\Settings($this->scopeConfig);
    }

    public function testIsEnabled()
    {
        $this->assertTrue($this->subject->isEnabled());
    }
}
