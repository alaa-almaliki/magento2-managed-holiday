<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\Sql;

/**
 * Class Condition
 *
 * @package Alaa\ManagedHoliday\Model\Sql
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class Condition implements ConditionInterface
{
    /**
     * @var array
     */
    protected $conditions;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var ConditionValidator
     */
    protected $validator;

    /**
     * Condition constructor.
     *
     * @param ConditionValidator $validator
     */
    public function __construct(ConditionValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __toString(): string
    {
        return $this->stringify();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function stringify(): string
    {
        $this->validator->validate($this);
        return \implode(sprintf(' %s ', \trim(\strtoupper($this->getOperator()))), $this->getConditions());
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        if (!$this->operator) {
            $this->operator = self::CONDITION_OPERATOR_AND;
        }

        return $this->operator;
    }

    /**
     * @param string $operator
     * @return ConditionInterface
     */
    public function setOperator(string $operator): ConditionInterface
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return ConditionInterface
     */
    public function setConditions(array $conditions): ConditionInterface
    {
        $this->conditions = $conditions;
        return $this;
    }
}
