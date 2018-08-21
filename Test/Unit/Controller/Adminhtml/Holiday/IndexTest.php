<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Controller\Adminhtml\Holiday\Index;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class IndexTest
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 *
 * @author Alaa Al-Maliki<alaa.almaliki@gmail.com>
 */
class IndexTest extends TestCase
{
    use MockTrait;

    /**
     * @var Index|MockObject
     */
    protected $subject;

    /**
     * @var Context|MockObject
     */
    protected $context;

    /**
     * @var PageFactory|MockObject
     */
    protected $pageFactory;

    /**
     * @var Page|MockObject
     */
    protected $page;

    /**
     * @var Config|MockObject;
     */
    protected $pageConfig;

    /**
     * @var Title|MockObject
     */
    protected $pageTitle;

    public function setUp()
    {
        $this->context = $this->getMock(Context::class);
        $this->pageFactory = $this->getMock(PageFactory::class, ['create']);
        $this->subject = $this->getMock(Index::class, null, [$this->context, $this->pageFactory]);
        $this->pageConfig = $this->getMock(Config::class, ['getTitle']);
        $this->pageTitle = $this->getMock(Title::class, ['set']);

        $this->page = $this->getMock(Page::class, ['getConfig', 'addBreadcrumb']);
        $this->pageFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->page);

        $this->page->expects($this->any())
            ->method('getConfig')
            ->willReturn($this->pageConfig);

        $this->pageConfig->expects($this->any())
            ->method('getTitle')
            ->willReturn($this->pageTitle);
    }

    public function testExecute()
    {
        $this->assertInstanceOf(Page::class, $this->subject->execute());
    }
}
