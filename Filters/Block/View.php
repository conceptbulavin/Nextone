<?php
/**
 * @category    Mana
 * @package     Mana_Filters
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Block type for showing filters in category view pages.
 * @author Mana Team
 * Injected into layout instead of standard catalog/layer_view in layout XML file.
 * @method string getShowInFilter() returns position of layered navigation if positioning module is installed and null otherwise
 */
class Mana_Filters_Block_View extends Mage_Catalog_Block_Layer_View {


    public $_currentCategory;
    public $_searchSession;
    public $_productCollection;
    public $_maxPrice;
    public $_minPrice;
    public $_currMinPrice;
    public $_currMaxPrice;

    protected function _construct()
    {
        $this->_currentCategory = Mage::registry('current_category');
        if($this->_currentCategory)
        {
            if(!$this->_currentCategory->getId())
            {
                $categoryId = $this->getRequest()->getParam('cat_id');
                $this->_currentCategory = Mage::getModel("catalog/category")->load($categoryId);
            }
        }
        else
        {
            $categoryId = $this->getRequest()->getParam('cat_id');
            $this->_currentCategory = Mage::getModel("catalog/category")->load($categoryId);
        }

        $this->_searchSession = Mage::getSingleton('catalogsearch/session');
        $this->setProductCollection();
        $this->setMinPrice();
        $this->setMaxPrice();
        $this->setCurrentPrices();
        parent::_construct();
        Mage::register('current_layer', $this->getLayer(), true);
    }

    /**
     * This method is called during page rendering to generate additional child blocks for this block.
     * @return Mana_Filters_Block_View
     * This method is overridden by copying (method body was pasted from parent class and modified as needed). All
     * changes are marked with comments.
     * @see app/code/core/Mage/Catalog/Block/Layer/Mage_Catalog_Block_Layer_View::_prepareLayout()
     */
    protected function _prepareLayout()
    {
        /* @var $layoutHelper Mana_Core_Helper_Layout */
        $layoutHelper = Mage::helper('mana_core/layout');
        $layoutHelper->delayPrepareLayout($this, 1000);

        return $this;
    }

    public function delayedPrepareLayout() {
        /* @var $helper Mana_Filters_Helper_Data */
        $helper = Mage::helper(strtolower('Mana_Filters'));

        /* @var $layout Mage_Core_Model_Layout */
        $layout = $this->getLayout();

        /* @var $layer Mage_Catalog_Model_Layer */
        $layer = $this->getLayer();

        /* @var $query Mana_Filters_Model_Query */
        $query = Mage::getSingleton('mana_filters/query');
        $query
            ->setLayer($layer)
            ->init();

        $showState = 'all';
        if ($showInFilter = $this->getShowInFilter()) {
            if ($template = Mage::getStoreConfig('mana_filters/positioning/' . $showInFilter)) {
                $this->setTemplate($template);
            }
            $showState = Mage::getStoreConfig('mana_filters/positioning/show_state_' . $showInFilter);
        }
        if ($showState) {
            /* @var $state Mana_Filters_Block_State */
            $stateBlock = $layout->createBlock('mana_filters/state', '', array(
                'layer' => $layer,
                'mode' => $showState,
            ));
            $this->setChild('layer_state', $stateBlock);
        }

        foreach ($helper->getFilterOptionsCollection() as $filterOptions) {
            /* @var $filterOptions Mana_Filters_Model_Filter2_Store */

            if ($helper->canShowFilterInBlock($this, $filterOptions)) {
                $displayOptions = $filterOptions->getDisplayOptions();

                $blockName = $helper->getFilterLayoutName($this, $filterOptions);
                /* @var $block Mana_Filters_Block_Filter */
                $block = $layout->createBlock((string)$displayOptions->block, $blockName, array(
                    'filter_options' => $filterOptions,
                    'display_options' => $displayOptions,
                    'show_in_filter' => $this->getShowInFilter(),
                    'query' => $query,
                    'layer' => $layer,
                    'attribute_model' => $filterOptions->getAttribute(),
                    'mode' => $helper->getMode(),
                ));
                $block->init();
                $this->setChild($filterOptions->getCode() . '_filter', $block);
            }
        }

        $query->apply();
        $layer->apply();

        return $this;
    }

    public function getFilters() {
        /* @var $helper Mana_Filters_Helper_Data */
        $helper = Mage::helper(strtolower('Mana_Filters'));

        $filters = array();
    	foreach ($helper->getFilterOptionsCollection() as $filterOptions) {
            /* @var $filterOptions Mana_Filters_Model_Filter2_Store */


            if ($helper->isFilterEnabled($filterOptions)) {
                if ($helper->canShowFilterInBlock($this, $filterOptions)) {
                    $filters[] = $this->getChild($filterOptions->getCode() . '_filter');
                }
    		}
        }
        return $filters;
    }
    public function getClearUrl() {
        /* @var $helper Mana_Filters_Helper_Data */
        $helper = Mage::helper(strtolower('Mana_Filters'));

        return $helper->getClearUrl();
    }

    /**
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer() {
        /* @var $helper Mana_Filters_Helper_Data */
        $helper = Mage::helper(strtolower('Mana_Filters'));
        return $helper->getLayer();
    }

    public function canShowBlock() {
        /* @var $helper Mana_Filters_Helper_Data */
        $helper = Mage::helper(strtolower('Mana_Filters'));

        switch ($helper->getMode()) {
            case 'category':
                return $this->_canShowBlockInCategory();
            case 'search':
                return $this->_canShowBlockInSearch();
            default:
                throw new Exception('Not implemented');
        }
    }
    public function _canShowBlockInCategory() {
        if ($this->canShowOptions()) {
            return true;
        } elseif ($state = $this->getChild('layer_state')) {
            $appliedFilters = $this->getChild('layer_state')->getActiveFilters();
            return !empty($appliedFilters);
        }
        else {
            return false;
        }
    }
    public function _canShowBlockInSearch() {
        $_isLNAllowedByEngine = Mage::helper('catalogsearch')->getEngine()->isLeyeredNavigationAllowed();
        if (!$_isLNAllowedByEngine) {
            return false;
        }
        $availableResCount = (int) Mage::app()->getStore()
            ->getConfig(Mage_CatalogSearch_Model_Layer::XML_PATH_DISPLAY_LAYER_COUNT);

        if ($availableResCount && $availableResCount<$this->getLayer()->getProductCollection()->getSize()) {
            return false;
        }
        return $this->_canShowBlockInCategory();
    }
    public function getPriceSlider()
    {
        return $this->setTemplate('mana/filters/priceslider.phtml')->_toHtml();
    }
    public function getCurrencySymbol()
    {
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
    }
    public function setMinPrice()
    {
        if( (isset($_GET['q']) && !isset($_GET['min'])) || !isset($_GET['q'])){


            $this->_minPrice = $this->_productCollection
                ->getFirstItem()
                ->getPrice();
        if($this->_minPrice >= 1)
            $this->_minPrice -= 1;

        $this->_searchSession->setMinPrice($this->_minPrice);
        }else{
            $this->_minPrice = $this->_searchSession->getMinPrice();
        }

    }

    public function setMaxPrice()
    {
        if( (isset($_GET['q']) && !isset($_GET['max'])) || !isset($_GET['q'])){
            $this->_maxPrice = $this->_productCollection
                ->getLastItem()
                ->getPrice();
            $this->_maxPrice += 1;
            $this->_searchSession->setMaxPrice($this->_maxPrice);
        }else{
            $this->_maxPrice = $this->_searchSession->getMaxPrice();
        }
    }

    public function setProductCollection()
    {
        if($this->_currentCategory->getId()){
            $this->_productCollection = $this->_currentCategory
                ->getProductCollection()
                ->addAttributeToSelect('*')
                ->setOrder('price', 'ASC');
        }else{
            $this->_productCollection = Mage::getSingleton('catalogsearch/layer')->getProductCollection()
                ->addAttributeToSelect('*')
                ->setOrder('price', 'ASC');
        }

    }

    public function setCurrentPrices()
    {

        $this->_currMinPrice = $this->getRequest()->getParam('min');
        if(!$this->_currMinPrice)
            $this->_currMinPrice = $this->_minPrice;
        $this->_currMaxPrice = $this->getRequest()->getParam('max');
        if(!$this->_currMaxPrice)
            $this->_currMaxPrice = $this->_maxPrice;
    }

}