<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model\Sql;

use Alaa\ManagedHoliday\Model\Sql\Condition;
use Alaa\ManagedHoliday\Model\Sql\ConditionValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model\Sql
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ConditionTest extends TestCase
{
    public function testCondition()
    {
        $cons = [
            'column_one = 1',
            'column_two = 2',
        ];

        $condition = new Condition(new ConditionValidator());
        $condition->setConditions($cons);

        $this->assertEquals('column_one = 1 AND column_two = 2', $condition);
        $condition->setOperator(Condition::CONDITION_OPERATOR_OR);
        $this->assertEquals('column_one = 1 OR column_two = 2', $condition);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testConditionWithEmptyConditionsException()
    {
        $condition = new Condition(new ConditionValidator());
        $condition->setConditions([]);
        $this->assertEquals('column_one = 1 AND column_two = 2', $condition->stringify());
    }
}
