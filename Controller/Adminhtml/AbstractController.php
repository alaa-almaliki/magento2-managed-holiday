<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Class AbstractController
 *
 * @package Alaa\ManagedHoliday\Controller\Adminhtml
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
abstract class AbstractController extends Action
{
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Alaa_ManagedHoliday::holiday');
    }
}
