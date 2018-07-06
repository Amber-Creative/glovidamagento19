<?php
class FME_Usersurvey_Block_Adminhtml_Usersurvey extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_usersurvey';
    $this->_blockGroup = 'usersurvey';
    $this->_headerText = Mage::helper('usersurvey')->__('Question Management');
    $this->_addButtonLabel = Mage::helper('usersurvey')->__('Add New Question');
    parent::__construct();
   
  }
}