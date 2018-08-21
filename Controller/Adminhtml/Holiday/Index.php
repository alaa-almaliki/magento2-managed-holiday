<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml\Holiday;

use Alaa\ManagedHoliday\Controller\Adminhtml\AbstractController;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml\Holiday
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class Index extends AbstractController
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * Index constructor.
     *
     * @param Action\Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Action\Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set('Manage Holiday');
        $page->addBreadcrumb(__('Managed Holiday'), __('Managed Holiday'));
        return $page;
    }
}
