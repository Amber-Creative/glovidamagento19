<?php

class FME_Usersurvey_Model_Mysql4_Answers extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the usersurvey_id refers to the key field in your database table.
        $this->_init('usersurvey/answers', 'answer_id');
    }
}