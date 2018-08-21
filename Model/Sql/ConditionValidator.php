<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\Sql;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class ConditionValidator
 *
 * @package Alaa\ManagedHoliday\Model\Sql
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ConditionValidator
{
    /**
     * @param ConditionInterface $condition
     * @return ConditionValidator
     * @throws LocalizedException
     */
    public function validate(ConditionInterface $condition): self
    {
        if (!\in_array($condition->getOperator(), $this->getAllowedOperators())) {
            throw new LocalizedException(__('Operator %1 is not allowed', $condition->getOperator()));
        }

        if (\count($condition->getConditions()) < 1) {
            throw new LocalizedException(__('Empty Conditions are not allowed'));
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function getAllowedOperators(): array
    {
        return [
            ConditionInterface::CONDITION_OPERATOR_AND,
            ConditionInterface::CONDITION_OPERATOR_OR,
        ];
    }
}
