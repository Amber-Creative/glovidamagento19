<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tab_Editanswer extends Mage_Adminhtml_Block_Widget_Form
{
  public function __construct()
  {
      
        parent::__construct();
        $this->setTemplate('usersurvey/usersurvey_edit.phtml');
         $this->setFormAction(Mage::getUrl('*/*/saveeditanswer'));  
       
     
  }
  
}