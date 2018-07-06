<?php

class FME_Usersurvey_Block_Adminhtml_Questionset_Edit_Tab_Storeform2 extends Mage_Adminhtml_Block_Widget_Form
{
  
  public function __construct()
  {
      
        parent::__construct();
        $this->setTemplate('usersurvey/storeform2.phtml');
         $this->setFormAction(Mage::getUrl('*/*/saveanswer'));  
       
     
  }

}