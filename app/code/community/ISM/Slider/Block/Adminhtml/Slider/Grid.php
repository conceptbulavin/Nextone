<?php

/**
 * ISM Slider admin grid
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slider_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('slider/slider')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('slider_id',
            array(
                'header' => 'ID',
                'align' =>'right',
                'width' => '50px',
                'index' => 'slider_id',
            ));

        $this->addColumn('title',
            array(
                'header' => 'Title',
                'align' =>'left',
                'index' => 'title',
            ));

        $this->addColumn('identifier',
            array(
                'header' => 'Identifier',
                'align' =>'left',
                'index' => 'identifier',
            ));

        $this->addColumn('width',
            array(
                'header' => 'Width',
                'align' =>'left',
                'index' => 'width',
                'width' => '100px',
            ));

        $this->addColumn('height',
            array(
                'header' => 'Height',
                'align' =>'left',
                'index' => 'height',
                'width' => '100px',
            ));

        $this->addColumn('is_active', array(
            'header' => 'Is Active',
            'align' =>'left',
            'index' => 'is_active',
            'width' => '50px',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}