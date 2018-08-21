<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Model;

use Alaa\ManagedHoliday\Api\Data\HolidaySearchResultInterface;
use Magento\Framework\Api\Search\SearchResult;

/**
 * Class HolidaySearchResult
 *
 * @package Alaa\ManagedHoliday\Model
 * @author  Alaa Al-Makiki <alaa.almaliki@gmail.com>
 */
class HolidaySearchResult extends SearchResult implements HolidaySearchResultInterface
{

}
