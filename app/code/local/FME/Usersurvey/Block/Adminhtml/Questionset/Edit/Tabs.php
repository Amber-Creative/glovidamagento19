<?php

class FME_Usersurvey_Block_Adminhtml_Questionset_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('questionset_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('usersurvey')->__('Question Set Information'));
  }

  protected function _beforeToHtml()
  {
      $param = Mage::app()->getRequest()->get('activeTab');
      $this->addTab('form_section', array(
          'label'     => Mage::helper('usersurvey')->__('Question Set Information'),
          'title'     => Mage::helper('usersurvey')->__('Question Set Information'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tab_form')->toHtml(),
          'active'    => true
      ));
     
     $this->addTab('form_section1', array(
          'label'     => Mage::helper('usersurvey')->__('Questions'),
          'title'     => Mage::helper('usersurvey')->__('Questions'),
           'url'       => $this->getUrl('*/*/questions', array('_current' => true)),
          'class'     => 'ajax',
      ));

   
      $this->addTab('form_section2', array(
          'label'     => Mage::helper('usersurvey')->__('Set Results'),
          'title'     => Mage::helper('usersurvey')->__('Set Results'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tab_setanswers')->toHtml(),
          
      ));
           
      return parent::_beforeToHtml();
  }
}