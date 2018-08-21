<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Helper;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Helper\Holiday;
use Alaa\ManagedHoliday\Model\HolidayRepository;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Helper\Context;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Helper
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayTest extends TestCase
{
    use MockTrait;

    /**
     * @var Holiday
     */
    protected $subject;

    /**
     * @var MockObject|Context
     */
    protected $helperContext;

    /**
     * @var MockObject|HolidayRepository
     */
    protected $holidayRepository;

    /**
     * @var \Alaa\ManagedHoliday\Model\Holiday|MockObject
     */
    protected $holiday;

    /**
     * @var SearchCriteriaBuilder|MockObject
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteria
     */
    protected $searchCriteria;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone|MockObject
     */
    protected $timezone;

    /**
     * @var \Alaa\ManagedHoliday\Model\ArrayUtils
     */
    protected $arrayUtils;

    /**
     * @var \Alaa\ManagedHoliday\Model\HolidayProviderInterface|MockObject
     */
    protected $holidayProvider;

    /**
     * @var \Alaa\ManagedHoliday\Model\SettingsInterface|MockObject
     */
    protected $settings;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|MockObject
     */
    protected $storeManager;

    /**
     * @var \Magento\Store\Api\Data\StoreInterface|MockObject
     */
    protected $store;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->helperContext = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->holiday = $this->getMock(\Alaa\ManagedHoliday\Model\Holiday::class, ['save', 'load']);
        $this->holidayRepository = $this->getMock(HolidayRepository::class, ['getList']);
        $this->searchCriteriaBuilder = $this->getMock(SearchCriteriaBuilder::class, ['create', 'addFilter']);
        $this->searchCriteriaBuilder->expects($this->any())
            ->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteria = new \Magento\Framework\Api\SearchCriteria();
        $this->searchCriteriaBuilder->expects($this->any())
            ->method('create')
            ->willReturn($this->searchCriteria);

        $this->timezone = $this->getMock(\Magento\Framework\Stdlib\DateTime\Timezone::class, ['date']);
        $this->timezone->expects($this->any())
            ->method('date')
            ->willReturn(new \DateTime());

        $this->arrayUtils = new \Alaa\ManagedHoliday\Model\ArrayUtils();
        $this->holidayProvider = $this->getMock(\Alaa\ManagedHoliday\Model\HolidayProvider::class, ['getHolidays']);
        $this->settings = $this->getMock(\Alaa\ManagedHoliday\Model\Settings::class);
        $this->storeManager = $this->getMock(\Magento\Store\Model\StoreManager::class, ['getStore']);
        $this->store = $this->getMock(\Magento\Store\Model\Store::class, ['getId']);
        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $constructorArgs = [
            $this->helperContext,
            $this->holidayRepository,
            $this->searchCriteriaBuilder,
            $this->timezone,
            $this->arrayUtils,
            $this->holidayProvider,
            $this->settings,
            $this->storeManager
        ];

        $this->subject = $this->getMock(Holiday::class, null, $constructorArgs);
    }

    public function testGetHolidayNullStoreId()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->holiday->setId(1);
        $this->assertInstanceOf(HolidayInterface::class, $this->subject->getHoliday());
    }

    public function testGetHolidayIntStoreId()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->holiday->setId(1);
        $this->assertInstanceOf(HolidayInterface::class, $this->subject->getHoliday('01/01/2018', 1));
    }

    public function testGetHolidayWithStoreObject()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->holiday->setId(1);
        $this->assertInstanceOf(HolidayInterface::class, $this->subject->getHoliday('01/01/2018', $this->store));
    }

    public function testGetHolidayWithStoreIdArray()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->holiday->setId(1);
        $this->assertInstanceOf(HolidayInterface::class, $this->subject->getHoliday('01/01/2018', [1]));
    }

    public function testGetHolidayNullEmptyArray()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([]);
        $this->assertNull($this->subject->getHoliday());
    }

    public function testGetHolidayNullNoHolidayId()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->assertNull($this->subject->getHoliday());
    }

    public function testIsHolidayTrue()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->holiday->setId(1);
        $this->assertTrue($this->subject->isHoliday());
    }

    public function testIsHolidayFalseNoHolidayId()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);
        $this->assertFalse($this->subject->isHoliday());
    }

    public function testIsHolidayFalseNoResults()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([]);
        $this->assertFalse($this->subject->isHoliday());
    }

    public function testBetween()
    {
        $this->holidayRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->holiday]);

        $this->assertTrue(\is_array($this->subject->between('', '')));
    }

    public function testGetHolidays()
    {
        $this->holidayProvider->expects($this->any())
            ->method('getHolidays')
            ->willReturn([$this->holiday]);
        $this->assertTrue(\is_array($this->subject->getHolidays()));
    }
}
