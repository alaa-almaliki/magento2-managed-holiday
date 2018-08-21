<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Plugin\Adminhtml;

use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday;
use Alaa\ManagedHoliday\Plugin\Adminhtml\SaveHolidayStores;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class SaveHolidayStoresTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Plugin\Adminhtml
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SaveHolidayStoresTest extends TestCase
{
    use MockTrait;

    /**
     * @var MockObject|SaveHolidayStores
     */
    protected $subject;

    /**
     * @var Http|MockObject
     */
    protected $request;

    /**
     * @var Holiday|MockObject
     */
    protected $holidayResource;

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
        $this->request = $this->getMock(Http::class, ['getPostValue']);
        $this->holidayResource = $this->getMock(Holiday::class, ['saveHolidayStores']);
        $this->holidayRepository = $this->getMock(HolidayRepository::class);
        $this->holiday = $this->getMock(\Alaa\ManagedHoliday\Model\Holiday::class);
        $this->subject = new SaveHolidayStores($this->request, $this->holidayResource);
    }

    public function testAroundSave()
    {
        $this->request->expects($this->any())
            ->method('getPostValue')
            ->willReturn(['store_id' => [1]]);

        $this->subject->aroundSave(
            $this->holidayRepository, function () {
        }, $this->holiday
        );
    }
}
