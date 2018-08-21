<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\Data\HolidayInterface;
use Alaa\ManagedHoliday\Model\ResourceModel\Holiday as HolidayResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Stdlib\DateTime\Timezone;

/**
 * Class Holiday
 *
 * @package Alaa\ManagedHoliday\Model
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class Holiday extends AbstractModel implements HolidayInterface, IdentityInterface
{
    const CACHE_TAG = 'managed_holiday';

    /**
     * @var string
     */
    protected $_idFieldName = HolidayInterface::ATTRIBUTE_ID;

    /**
     * @var Timezone
     */
    protected $timezone;

    /**
     * Holiday constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Timezone $timezone
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Timezone $timezone,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->timezone = $timezone;
    }

    /**
     * @inheritdoc
     */
    public function _construct()
    {
        $this->_init(HolidayResource::class);
    }

    /**
     * @param int $id
     * @return $this|HolidayInterface|AbstractModel
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    /**
     * @param string $title
     * @return HolidayInterface
     */
    public function setTitle(string $title): HolidayInterface
    {
        $this->setData(HolidayInterface::ATTRIBUTE_TITLE, $title);
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_getData(HolidayInterface::ATTRIBUTE_TITLE);
    }

    /**
     * @param string $reason
     * @return HolidayInterface
     */
    public function setReason(string $reason): HolidayInterface
    {
        $this->setData(HolidayInterface::ATTRIBUTE_REASON, $reason);
        return $this;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->_getData(HolidayInterface::ATTRIBUTE_REASON);
    }

    /**
     * @param int $storeId
     * @return HolidayInterface
     */
    public function setStoreId(int $storeId): HolidayInterface
    {
        $this->setData(HolidayInterface::ATTRIBUTE_STORE_ID, $storeId);
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->_getData(HolidayInterface::ATTRIBUTE_STORE_ID);
    }

    /**
     * @param string $date
     * @return HolidayInterface
     */
    public function setHolidayDate(string $date): HolidayInterface
    {
        $this->setData(
            self::ATTRIBUTE_HOLIDAY_DATE,
            $this->timezone->date($date)->getTimestamp()
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getHolidayDate(): string
    {
        return $this->timezone
            ->date($this->_getData(self::ATTRIBUTE_HOLIDAY_DATE))
            ->format('d-m-y');
    }

    /**
     * @param bool $isActive
     * @return HolidayInterface
     */
    public function setIsActive(bool $isActive): HolidayInterface
    {
        $this->setData(HolidayInterface::ATTRIBUTE_IS_ACTIVE, $isActive);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool)$this->_getData(HolidayInterface::ATTRIBUTE_IS_ACTIVE);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return parent::getId();
    }
}
