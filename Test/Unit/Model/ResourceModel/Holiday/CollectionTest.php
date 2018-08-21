<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit\Model\ResourceModel\Holiday;

use Alaa\ManagedHoliday\Model\ResourceModel\Holiday\Collection;
use Alaa\ManagedHoliday\Test\Unit\MockTrait;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class CollectionTest
 *
 * @package Alaa\ManagedHoliday\Test\Unit\Model\ResourceModel\Holiday
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class CollectionTest extends TestCase
{
    use MockTrait;

    /**
     * @var Collection|MockObject
     */
    protected $subject;

    /**
     * @var Select|MockObject
     */
    protected $select;


    public function setUp()
    {
        $this->subject = $this->getMock(Collection::class, ['getSelect']);
        $this->select = $this->getMock(Select::class, ['joinLeft']);
        $this->subject->expects($this->any())
            ->method('getSelect')
            ->willReturn($this->select);
    }

    public function testJoinStore()
    {
        $this->assertInstanceOf(Collection::class, $this->subject->joinStore());
    }
}
