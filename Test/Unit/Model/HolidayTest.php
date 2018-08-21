<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Timezone;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 * @author  Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayTest extends TestCase
{
    use MockTrait;

    /**
     * @var Holiday|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var Registry|MockObject
     */
    protected $registry;

    /**
     * @var Timezone|MockObject
     */
    protected $timezone;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class);
        $this->registry = $this->getMock(Registry::class);
        $this->timezone = $this->getMock(Timezone::class, ['date']);
        $this->timezone->expects($this->any())
            ->method('date')
            ->willReturn(new \DateTime());

        $this->subject = $this->getMock(Holiday::class, ['_init'], [$this->context, $this->registry, $this->timezone]);
    }

    public function testData()
    {
        $data = [
            'holiday_id' => 1,
            'title' => 'New Year Holiday',
            'reason' => 'New Year Holiday',
            'store_id' => 1,
            'holiday_date' => '2018-01-01',
            'is_active' => 1
        ];

        $this->subject->setData($data);
        $this->assertEquals(1, $this->subject->getId());
        $this->assertEquals('New Year Holiday', $this->subject->getTitle());
        $this->assertEquals('New Year Holiday', $this->subject->getReason());
        $this->assertEquals(1, $this->subject->getStoreId());
        $this->assertEquals(date('d-m-y'), $this->subject->getHolidayDate());
        $this->assertEquals(1, $this->subject->getIsActive());
        $this->assertEquals([Holiday::CACHE_TAG . '_' . 1], $this->subject->getIdentities());
    }
}
