<?php

class FME_Usersurvey_Model_Mysql4_Usersurvey extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the usersurvey_id refers to the key field in your database table.
        $this->_init('usersurvey/usersurvey', 'question_id');
    }
}