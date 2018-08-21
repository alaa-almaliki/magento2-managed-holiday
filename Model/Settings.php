<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Settings
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Settings implements SettingsInterface
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Settings constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->isSetFlag($this->getConfigPath('enabled'));
    }

    /**
     * @param string $title
     * @return string
     */
    private function getConfigPath(string $title): string
    {
        return \sprintf(self::XML_PATH_MANAGED_HOLIDAY_SETTINGS, $title);
    }
}
