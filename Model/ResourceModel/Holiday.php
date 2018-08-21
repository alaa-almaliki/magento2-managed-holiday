<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\ResourceModel;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Model\Sql\ConditionInterfaceFactory;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Holiday
 *
 * @package Alaa\ManagedHoliday\Model\ResourceModel
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class Holiday extends AbstractDb
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var ConditionInterfaceFactory
     */
    protected $conditionFactory;

    /**
     * Holiday constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ConditionInterfaceFactory $conditionFactory
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ConditionInterfaceFactory $conditionFactory,
        string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->storeManager = $storeManager;
        $this->conditionFactory = $conditionFactory;
    }

    /**
     * @param HolidayInterface $holiday
     * @param int|null $store
     * @return Holiday
     */
    public function deleteHolidayStore(HolidayInterface $holiday, int $store = null): self
    {
        $connection = $this->getConnection();
        $conditions = [
            $connection->quoteInto('main_table.holiday_id = ?', $holiday->getId()),
            $connection->quoteInto('store_id = ?', $store)
        ];

        $condition = $this->conditionFactory->create()->setConditions($conditions);
        $connection->delete('alaa_managed_holiday_store', $condition);

        return $this;
    }

    /**
     * @param HolidayInterface $holiday
     * @param array $stores
     */
    public function saveHolidayStores(HolidayInterface $holiday, array $stores)
    {
        if (!$holiday->getId()) {
            return;
        }

        $this->deleteStores($holiday);
        $storesToSave = \array_map(
            function ($store) use ($holiday) {
                return [
                    'holiday_id' => $holiday->getId(),
                    'store_id' => $store
                ];
            }, $stores
        );

        if (\is_array($storesToSave) && \count($storesToSave) > 0) {
            $this->getConnection()
                ->insertOnDuplicate(
                    'alaa_managed_holiday_store',
                    $storesToSave,
                    ['holiday_id', 'store_id']
                );
        }
    }

    /**
     * @param HolidayInterface $holiday
     * @return Holiday
     */
    public function deleteStores(HolidayInterface $holiday): self
    {
        $this->getConnection()
            ->delete(
                'alaa_managed_holiday_store',
                $this->getConnection()->quoteInto('alaa_managed_holiday_store.holiday_id = ?', $holiday->getId())
            );

        return $this;
    }

    /**
     * @param int $holidayId
     * @return array
     */
    public function getStoreIds(int $holidayId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(['holiday_store' => 'alaa_managed_holiday_store'], ['holiday_store.store_id'])
            ->where($connection->quoteInto('holiday_store.holiday_id = ?', $holidayId));

        return $connection->fetchCol($select);
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(HolidayInterface::TABLE_NAME, HolidayInterface::ATTRIBUTE_ID);
    }
}
