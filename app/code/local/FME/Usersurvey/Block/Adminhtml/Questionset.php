<?php
class FME_Usersurvey_Block_Adminhtml_Questionset extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
   	$this->_controller = 'adminhtml_questionset';
    $this->_blockGroup = 'usersurvey';
    $this->_headerText = Mage::helper('usersurvey')->__('Question Set Management');
    $this->_addButtonLabel = Mage::helper('usersurvey')->__('Add New');
    parent::__construct();
  }
}