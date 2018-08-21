<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

/**
 * Class ArrayUtils
 *
 * @package Alaa\ManagedHoliday\Model
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class ArrayUtils
{
    /**
     * @param array $array
     * @return bool
     */
    public function isEmpty(array $array): bool
    {
        return !\is_array($array) || (\count($array) < 1);
    }
}
