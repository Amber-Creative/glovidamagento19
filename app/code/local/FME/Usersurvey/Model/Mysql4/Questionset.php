<?php

class FME_Usersurvey_Model_Mysql4_Questionset extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the questionset_id refers to the key field in your database table.
        $this->_init('usersurvey/questionset', 'set_id');
    }
}