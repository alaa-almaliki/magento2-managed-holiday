<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Setup;

use Alaa\ManagedHoliday\Api\Data\HolidayInterfaceFactory;
use Alaa\ManagedHoliday\Model\Holiday;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Alaa\ManagedHoliday\Setup\HolidaySetup;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidaySetupTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Setup
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidaySetupTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidaySetup|MockObject
     */
    protected $subject;

    /**
     * @var HolidayInterfaceFactory|MockObject
     */
    protected $holidayFactory;

    /**
     * @var HolidayResource|MockObject
     */
    protected $holidayResource;

    /**
     * @var Holiday|MockObject
     */
    protected $holiday;

    public function setUp()
    {
        $this->holidayFactory = $this->getMock(HolidayInterfaceFactory::class, ['create']);
        $this->holiday = $this->getMock(Holiday::class);
        $this->holidayFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->holiday);

        $this->holidayResource = $this->getMock(HolidayResource::class, ['save', 'saveHolidayStores']);

        $constructorArgs = [
            $this->holidayFactory,
            $this->holidayResource
        ];

        $this->subject = $this->getMock(HolidaySetup::class, null, $constructorArgs);
    }

    public function testInsertHolidays()
    {
        $data = [
            [
                'holiday_id' => 1,
                'title' => 'New Year Holiday',
                'reason' => 'New Year Holiday',
                'store_id' => [1],
                'holiday_date' => '2018-01-01',
                'is_active' => 1
            ],
            [
                'holiday_id' => 2,
                'title' => 'System Maintenance',
                'reason' => 'System Maintenance',
                'store_id' => 1,
                'holiday_date' => '2018-04-07',
                'is_active' => 1
            ]
        ];

        $this->subject->insertHolidays($data);
    }

    /**
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     *
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testInsertHolidaysWithException()
    {
        $data = [
            [
                'holiday_id' => 1,
                'title' => 'New Year Holiday',
                'reason' => 'New Year Holiday',
                'holiday_date' => '2018-01-01',
                'is_active' => 1
            ],
            [
                'holiday_id' => 2,
                'title' => 'System Maintenance',
                'reason' => 'System Maintenance',
                'holiday_date' => '2018-04-07',
                'is_active' => 1
            ]
        ];

        $this->throwException(new LocalizedException(__('Holiday data must have store ids')));
        $this->subject->insertHolidays($data);
    }
}
