<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 *
 * @package Alaa\ManagedHoliday\Block\Adminhtml\Edit
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getHolidayId()) {
            $data = [
                'label' => __('Delete Holiday'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['holiday_id' => $this->getHolidayId()]);
    }
}
