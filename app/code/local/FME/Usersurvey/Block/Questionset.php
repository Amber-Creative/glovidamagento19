<?php
class FME_Usersurvey_Block_Questionset extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
		
    }
    
     public function getUsersurvey()     
     { 
        if (!$this->hasData('usersurvey')) {
            $this->setData('usersurvey', Mage::registry('usersurvey'));
        }
        return $this->getData('usersurvey');
        
    }
}