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
class Mana_Filters_Model_Solr_Attribute extends Mana_Filters_Model_Filter_Attribute
{
    public function isCountedOnMainCollection() {
        return !$this->isApplied();
    }

    public function processCounts($counts) {
        /* @var $collection Enterprise_Search_Model_Resource_Collection */
        $collection = $counts;

        $attribute = $this->getAttributeModel();
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        $fieldName = $engine->getSearchEngineFieldName($attribute, 'nav');
        return $collection->getFacetedData($fieldName);

    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     * @return Enterprise_Search_Model_Resource_Collection
     */
    public function countOnCollection($collection)
    {
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        $facetField = $engine->getSearchEngineFieldName($this->getAttributeModel(), 'nav');
        $collection->setFacetCondition($facetField);

        return $collection;
    }

    /**
     * @param Enterprise_Search_Model_Resource_Collection $collection
     */
    public function applyToCollection($collection)
    {
        $engine = Mage::getResourceSingleton('enterprise_search/engine');
        $facetField = $engine->getSearchEngineFieldName($this->getAttributeModel(), 'nav');
        $collection->addFqFilter(array($facetField => array('or' => $this->getMSelectedValues())));
    }

}