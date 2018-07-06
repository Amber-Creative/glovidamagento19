<?php

class FME_Usersurvey_Block_Adminhtml_Questionset_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('questionset_form', array('legend'=>Mage::helper('usersurvey')->__('Questionset information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('usersurvey')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('question_ids', 'hidden', array(
          'name'      => 'q_ids',
      ));
      
    $fieldset->addField('stores','multiselect',array(
    'name'      => 'stores[]',
    'label'     => Mage::helper('usersurvey')->__('Store View'),
    'title'     => Mage::helper('usersurvey')->__('Store View'),
    'required'  => true,
    'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
    ));


    $fieldset->addField('locations','multiselect',array(
          'name'      => 'locations',
          'label'     => Mage::helper('usersurvey')->__('Locations'),
          'title'     => Mage::helper('usersurvey')->__('Locations'),
          'required'  => true,
          'values' => array(
                                
                                '0' => array(
                                                'value'=> 0,
                                                'label' => 'Frontend'    
                                           ),
                                '1' => array(
                                                'value'=> 1,
                                                'label' => 'Registration'   
                                           ),                                         
                                  
                           ),

          ));
     
     
      if ( Mage::getSingleton('adminhtml/session')->getQuestionsetData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getQuestionsetData());
          Mage::getSingleton('adminhtml/session')->setQuestionsetData(null);
      } elseif ( Mage::registry('questionset_data') ) {
          $form->setValues(Mage::registry('questionset_data')->getData());
      }
      return parent::_prepareForm();
  }
}