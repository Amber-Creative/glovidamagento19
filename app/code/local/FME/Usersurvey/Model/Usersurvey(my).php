<?php

class FME_Usersurvey_Model_Usersurvey extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('usersurvey/usersurvey');
    }
}