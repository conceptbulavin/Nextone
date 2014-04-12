<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/**
 * @author Mana Team
 *
 */
class Mana_Filters_Model_Solr_Category extends Mana_Filters_Model_Filter_Category
{
    public function isCountedOnMainCollection() {
        return true;
    }

    public function processCounts($counts) {
        /* @var $collection Enterprise_Search_Model_Resource_Collection */
        $collection = $counts;

        $facetedData = $collection->getFacetedData('category_ids');
        foreach ($this->getCountedCategories() as $category) {
            if (isset($facetedData[$category->getId()])) {
                $category->setProductCount($facetedData[$category->getId()]);
            }
        }
        return $this->getCountedCategories();
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return Enterprise_Search_Model_Resource_Collection
     */
    public function countOnCollection($collection)
    {
        $useFlat = (bool) Mage::getStoreConfig('catalog/frontend/flat_catalog_category');
        $countedCategories = $this->getCountedCategories();
        $categories = ($countedCategories instanceof Mage_Core_Model_Resource_Db_Collection_Abstract)
            ? $countedCategories->getAllIds()
            : (($useFlat)
                ? array_keys($this->getCountedCategories())
                : array_keys($this->getCountedCategories()->toArray()));

        $collection->setFacetCondition('category_ids', $categories);

        return $collection;
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $collection->addFqFilter(array('category_ids' => array('or' => $this->getMSelectedValues())));
    }

    public function isFilterAppliedWhenCounting($modelToBeApplied) {
        return true;
    }
}