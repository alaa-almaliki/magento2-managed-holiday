<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Test\Unit;

/**
 * Trait MockTrait
 *
 * @package Alaa\ManagedHoliday\Test\Unit
 *
 * @author Alaa Al-Maliki<alaa.almaliki@gmail.com>
 */
trait MockTrait
{
    /**
     * @param string $class
     * @param array|null $methods
     * @param array|null $constructorArgs
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getMock(string $class, array $methods = null, array $constructorArgs = null)
    {
        $mock = $this->getMockBuilder($class);
        if (null !== $constructorArgs && \is_array($constructorArgs)) {
            $mock->setConstructorArgs($constructorArgs);
        } else {
            $mock->disableOriginalConstructor();
        }

        return $mock->setMethods($methods)->getMock();
    }
}
