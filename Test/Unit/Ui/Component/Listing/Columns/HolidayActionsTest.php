<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Ui\Component\Listing\Columns;

use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Alaa\ManagedHoliday\Ui\Component\Listing\Columns\HolidayActions;
use Magento\Backend\Model\Url;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class HolidayActionsTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Ui\Component\Listing\Columns
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayActionsTest extends TestCase
{
    use MockTrait;

    /**
     * @var HolidayActions|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var UiComponentFactory|MockObject
     */
    protected $uiComponentFactory;

    /**
     * @var Url|MockObject
     */
    protected $urlBuilder;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class);
        $this->uiComponentFactory = $this->getMock(UiComponentFactory::class);
        $this->urlBuilder = $this->getMock(Url::class, ['getUrl']);

        $this->subject = $this->getMock(
            HolidayActions::class,
            null,
            [$this->context, $this->uiComponentFactory, $this->urlBuilder]
        );
    }

    public function testPrepareDataSource()
    {
        $this->subject->setData('name', 'holiday');

        $dataSource['data']['items'] = [['holiday_id' => 1]];
        $this->urlBuilder->expects($this->any())
            ->method('getUrl')
            ->willReturn('base_url/holiday/holiday/action/holiday_id/1');

        $data = $this->subject->prepareDataSource($dataSource);
        $this->assertTrue(\is_array($data));
        $this->assertNotEmpty($data);

    }
}
