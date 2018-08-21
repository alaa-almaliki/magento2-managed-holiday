<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model\Sql;

/**
 * Interface ConditionInterface
 *
 * @package Alaa\ManagedHoliday\Model\Sql
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
interface ConditionInterface
{
    const CONDITION_OPERATOR_AND = 'AND';
    const CONDITION_OPERATOR_OR = 'OR';

    /**
     * @param array $conditions
     * @return ConditionInterface
     */
    public function setConditions(array $conditions): ConditionInterface;

    /**
     * @return array
     */
    public function getConditions(): array;

    /**
     * @param string $operator
     * @return ConditionInterface
     */
    public function setOperator(string $operator): ConditionInterface;

    /**
     * @return string
     */
    public function getOperator(): string;

    /**
     * @return string
     */
    public function stringify(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
