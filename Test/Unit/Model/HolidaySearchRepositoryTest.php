<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model;

use Alaa\ManagedHoliday\Helper\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Model\HolidaySearchRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidaySearchRepositoryTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidaySearchRepositoryTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidaySearchRepository|MockObject
     */
    protected $subject;

    /**
     * @var Holiday|MockObject
     */
    protected $holidayHelper;

    /**
     * @var HolidayRepository|MockObject
     */
    protected $holidayRepository;

    /**
     * @var \Alaa\ManagedHoliday\Model\Holiday|MockObject
     */
    protected $holiday;

    public function setUp()
    {
        $this->holidayHelper = $this->getMock(Holiday::class, ['isHoliday', 'getHolidays', 'between']);
        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getById']);
        $this->holiday = $this->getMock(\Alaa\ManagedHoliday\Model\Holiday::class);

        $this->subject = $this->getMock(
            HolidaySearchRepository::class,
            null,
            [$this->holidayHelper, $this->holidayRepository]
        );
    }

    public function testGetById()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willReturn($this->holiday);
        $this->assertInstanceOf(\Alaa\ManagedHoliday\Model\Holiday::class, $this->subject->getById(1));
    }

    public function testGetByIdNullHoliday()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getById')
            ->willThrowException(new NoSuchEntityException(__('No Such Holiday')));
        $this->assertNull($this->subject->getById(1));
    }

    public function testIsHoliday()
    {
        $this->holidayHelper->expects($this->any())
            ->method('isHoliday')
            ->willReturn(true);
        $this->assertTrue($this->subject->isHoliday());
    }

    public function testNextHolidays()
    {
        $this->holidayHelper->expects($this->any())
            ->method('getHolidays')
            ->willReturn([$this->holiday]);
        $this->assertEquals([$this->holiday], $this->subject->nextHolidays());
    }

    public function testBetween()
    {
        $this->holidayHelper->expects($this->any())
            ->method('between')
            ->willReturn([$this->holiday]);
        $this->assertEquals([$this->holiday], $this->subject->between('01/01/2018', '12/29/2018'));
    }
}
