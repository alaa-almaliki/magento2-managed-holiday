<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Ui\Component\Listing\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class HolidayActions
 *
 * @package Alaa\ManagedHoliday\Ui\Component\Listing\Columns
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class HolidayActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * HolidayActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'holiday/holiday/edit',
                        ['holiday_id' => $item['holiday_id']]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'holiday/holiday/delete',
                        ['holiday_id' => $item['holiday_id']]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['activate'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'holiday/holiday/active',
                        ['holiday_id' => $item['holiday_id'], 'active' => 1]
                    ),
                    'label' => __('Activate'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['deactivate'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'holiday/holiday/active',
                        ['holiday_id' => $item['holiday_id'], 'active' => 0]
                    ),
                    'label' => __('Deactivate'),
                    'hidden' => false,
                ];
            }
        }
        return $dataSource;
    }
}
