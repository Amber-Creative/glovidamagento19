<?php

class FME_Usersurvey_Model_Methods extends Mage_Core_Model_Abstract
{
    public function toOptionArray()
    {
      
        $arr = array('1' => 'IP Base','2'=> 'No IP Base');
        return $arr;
        
    }
}