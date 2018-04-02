<?php

class FME_Usersurvey_Model_Mysql4_Answers_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('usersurvey/answers');
    }
}