<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('usersurvey_form', array('legend'=>Mage::helper('usersurvey')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('usersurvey')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('usersurvey')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('usersurvey')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('usersurvey')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('usersurvey')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('usersurvey')->__('Content'),
          'title'     => Mage::helper('usersurvey')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
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