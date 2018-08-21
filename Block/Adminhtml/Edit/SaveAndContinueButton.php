<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAndContinueButton
 *
 * @package Alaa\ManagedHoliday\Block\Adminhtml\Edit
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getHolidayId()) {
            return [];
        }

        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
