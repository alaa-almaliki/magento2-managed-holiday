<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\ResourceModel\Holiday;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Model\Holiday as HolidayModel;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Alaa\ManagedHoliday\Model\ResourceModel\Holiday
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function _construct()
    {
        $this->_init(HolidayModel::class, HolidayResource::class);
        $this->_setIdFieldName(HolidayInterface::ATTRIBUTE_ID);
    }

    /**
     * @return Collection|AbstractCollection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this->joinStore();
    }

    /**
     * @return Collection
     */
    public function joinStore(): self
    {
        $this->getSelect()
            ->joinLeft(
                ['store' => 'alaa_managed_holiday_store'],
                'main_table.holiday_id=store.holiday_id',
                ['store.store_id']
            );

        return $this;
    }
}
