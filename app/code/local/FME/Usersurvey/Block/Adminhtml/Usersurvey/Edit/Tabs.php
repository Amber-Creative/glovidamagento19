<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('usersurvey_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('usersurvey')->__('Question Information'));
  }

  protected function _beforeToHtml()
  {
     $param = Mage::app()->getRequest()->get('activeTab');
   
       
      $this->addTab('form_section', array(
          'label'     => Mage::helper('usersurvey')->__('Question Information'),
          'title'     => Mage::helper('usersurvey')->__('Question Information'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_form')->toHtml(),
      ));
       if( Mage::registry('usersurvey_data') && Mage::registry('usersurvey_data')->getId() ) {
     $this->addTab('form_section1', array(
          'label'     => Mage::helper('usersurvey')->__('Answer Management'),
          'title'     => Mage::helper('usersurvey')->__('Answer Management'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_answermanagment')->toHtml(),
      ));
          $this->addTab('form_section2', array(
          'label'     => Mage::helper('usersurvey')->__('Question Results'),
          'title'     => Mage::helper('usersurvey')->__('Question Results'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_answerresult')->toHtml(),
      
      ));
       }
        if (array_key_exists($param, $this->_tabs)) {
              $this->addTab('form_section2', array(
          'label'     => Mage::helper('usersurvey')->__('Question Results'),
          'title'     => Mage::helper('usersurvey')->__('Question Results'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_answerresult')->toHtml(),
           'active'    => true
      ));
        }
      return parent::_beforeToHtml();
  }
}