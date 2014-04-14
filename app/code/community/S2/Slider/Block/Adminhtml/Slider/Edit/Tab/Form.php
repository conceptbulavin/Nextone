<?php

/**
 * S2 Slider slider edit form slider tab
 *
 * @category    S2
 * @package     S2_Slider
 * @author      S2 FED Team
 */
class S2_Slider_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('slider_data');
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('slider_form',
            array('legend'=> Mage::helper('slider')->__('Item information')));

        if ($model->getSliderId()) {
            $fieldset->addField('slider_id', 'hidden', array(
                'name' => 'slider_id',
            ));
        }

        //prepare fields
        $fieldset->addField('title', 'text',
            array(
                'label' => Mage::helper('slider')->__('Title'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'title',
            ));

        $fieldset->addField('identifier', 'text',
            array(
                'label' => Mage::helper('slider')->__('Identifier'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'identifier',
            ));

        $fieldset->addField('width', 'text',
            array(
                'label' => Mage::helper('slider')->__('Width'),
                'name' => 'width',
                'note' => Mage::helper('slider')->__('Slider width in pixels (px)')
            ));

        $fieldset->addField('height', 'text',
            array(
                'label' => Mage::helper('slider')->__('Height'),
                'name' => 'height',
                'note' => Mage::helper('slider')->__('Slider height in pixels (px)')
            ));

        $fieldset->addField('duration', 'text',
            array(
                'label' => Mage::helper('slider')->__('Duration'),
                'name' => 'duration',
                'note' => Mage::helper('slider')->__('Animation duration in milliseconds (1000 ms = 1 second)')
            ));

        $fieldset->addField('frequency', 'text',
            array(
                'label' => Mage::helper('slider')->__('Frequency'),
                'name' => 'frequency',
                'note' => Mage::helper('slider')->__('Delay between slides change in milliseconds (1000 ms = 1 second)')
            ));

        /*$fieldset->addField('effect', 'select',
            array(
                'label' => Mage::helper('slider')->__('Effect'),
                'name' => 'effect',
                'values'    => array(
                    array(
                        'value'     => 'show',
                        'label'     => Mage::helper('slider')->__('Show / Hide'),
                    ),
                    array(
                        'value'     => 'fade',
                        'label'     => Mage::helper('slider')->__('Fade In / Fade Out'),
                    ),
                    array(
                        'value'     => 'slideTop',
                        'label'     => Mage::helper('slider')->__('Slide From Top'),
                    ),
                    array(
                        'value'     => 'slideBottom',
                        'label'     => Mage::helper('slider')->__('Slide From Bottom'),
                    ),
                    array(
                        'value'     => 'slideLeft',
                        'label'     => Mage::helper('slider')->__('Slide From Left'),
                    ),
                    array(
                        'value'     => 'slideRight',
                        'label'     => Mage::helper('slider')->__('Slide From Right'),
                    ),
                )
            ));*/

        $fieldset->addField('autoslide', 'select',
            array(
                'label' => Mage::helper('slider')->__('Enable Autoslide'),
                'name' => 'autoslide',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slider')->__('Enabled'),
                    ),
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slider')->__('Disabled'),
                    ),
                )
            ));

        $fieldset->addField('controls', 'select',
            array(
                'label' => Mage::helper('slider')->__('Enable Controls'),
                'name' => 'controls',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slider')->__('Enabled'),
                    ),
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slider')->__('Disabled'),
                    ),
                )
            ));

        $fieldset->addField('pagination', 'select',
            array(
                'label' => Mage::helper('slider')->__('Enable Pagination'),
                'name' => 'pagination',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slider')->__('Enabled'),
                    ),
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slider')->__('Disabled'),
                    ),
                )
            ));

        $fieldset->addField('class', 'text',
            array(
                'label' => Mage::helper('slider')->__('Custom CSS Prefix'),
                'name' => 'class',
            ));

        $fieldset->addField('is_active', 'select',
            array(
                'label' => Mage::helper('slider')->__('Status'),
                'name' => 'is_active',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slider')->__('Enabled'),
                    ),
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slider')->__('Disabled'),
                    ),
                )
            ));


        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('stores', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('widget')->__('Assign to Store Views'),
                'title'     => Mage::helper('widget')->__('Assign to Store Views'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }


        if ( Mage::registry('slider_data') )
        {
            $form->setValues(Mage::registry('slider_data')->getData());
        }
        return parent::_prepareForm();
    }
}
