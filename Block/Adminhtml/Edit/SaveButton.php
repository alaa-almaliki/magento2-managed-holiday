<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveButton
 *
 * @package Alaa\ManagedHoliday\Block\Adminhtml\Edit
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Holiday'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'Save'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
