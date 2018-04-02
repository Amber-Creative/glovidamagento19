<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('usersurvey_form', array('legend'=>Mage::helper('usersurvey')->__('Item information')));
     
      $fieldset->addField('qtitle', 'text', array(
          'label'     => Mage::helper('usersurvey')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'qtitle',
      ));



   if(Mage::registry('usersurvey_data')->getType())
   {
     
      $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('usersurvey')->__('Type'),
          'name'      => 'type',
	        'disabled' => true,
          'readonly' => true,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('usersurvey')->__('Dropdrown'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('usersurvey')->__('Gallery'),
              ),
          ),
      ));
   }else
   {
     $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('usersurvey')->__('Type'),
          'name'      => 'type',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('usersurvey')->__('Dropdrown'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('usersurvey')->__('Gallery'),
              ),
          ),
      ));
    
   }
   

      $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('usersurvey')->__('Sort Order'),
          'required'  => false,
          'name'      => 'sort_order',
	  ));
	
     
      if ( Mage::getSingleton('adminhtml/session')->getUsersurveyData() )
      {
	
          $form->setValues(Mage::getSingleton('adminhtml/session')->getUsersurveyData());
          Mage::getSingleton('adminhtml/session')->setUsersurveyData(null);
      } elseif ( Mage::registry('usersurvey_data') ) {
          $form->setValues(Mage::registry('usersurvey_data')->getData());
      }
      return parent::_prepareForm();
  }
}