<?php

/**
 * ISM Slider slider edit
 *
 * @category    ISM
 * @package     ISM_Slider
 * @author      ISM FED Team
 */
class ISM_Slider_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    /**
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'slider';
        $this->_controller = 'adminhtml_slider';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('slider')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('slider')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        if( Mage::registry('slider_data')&&Mage::registry('slider_data')->getId())
        {
            if( Mage::registry('slider_data') && Mage::registry('slider_data')->getId() ) {
                return Mage::helper('slider')->__("Edit Slider '%s'", $this->escapeHtml(Mage::registry('slider_data')->getTitle()));
            } else {
                return Mage::helper('slider')->__('Add Item');
            }
        }
    }
}