<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('usersurvey_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('usersurvey')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('usersurvey')->__('Item Information'),
          'title'     => Mage::helper('usersurvey')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}