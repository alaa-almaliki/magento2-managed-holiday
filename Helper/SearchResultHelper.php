<?php declare(strict_types=1);

namespace Alaa\ManagedHoliday\Helper;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Data\SearchResultInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class SearchResultHelper
 *
 * @package Alaa\ManagedHoliday\Helper
 *
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class SearchResultHelper extends AbstractHelper
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractCollection $collection
     * @return SearchResultHelper
     */
    public function addFiltersToCollection(
        SearchCriteriaInterface $searchCriteria,
        AbstractCollection $collection
    ): self {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            if (!empty($fields)) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }

        return $this;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractCollection $collection
     * @return SearchResultHelper
     */
    public function addSortOrderToCollection(
        SearchCriteriaInterface $searchCriteria,
        AbstractCollection $collection
    ): self {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() === SortOrder::SORT_ASC ?
                SortOrder::SORT_ASC :
                SortOrder::SORT_DESC;
            $collection->setOrder($sortOrder->getField(), $direction);
        }

        return $this;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractCollection $collection
     * @return SearchResultHelper
     */
    public function addPagingToCollection(
        SearchCriteriaInterface $searchCriteria,
        AbstractCollection $collection
    ): self {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
        return $this;
    }

    /**
     * @param SearchResultInterface $searchResult
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractCollection $collection
     * @return SearchResultInterface
     */
    public function buildSearchResult(
        SearchResultInterface $searchResult,
        SearchCriteriaInterface $searchCriteria,
        AbstractCollection $collection
    ): SearchResultInterface {
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getItems());
        return $searchResult;
    }
}
