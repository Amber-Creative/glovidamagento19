<?php
class FME_Usersurvey_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if($row->getAnswerType()==""){
            return "";
        }
        else{
            //you can also use some resizer here...
            return "<img src='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."usersurvey/".$row->getAnswerType()."' width='75' height='75'/>";
        }
    }
    public function delete()
    {
        
            //you can also use some resizer here...
            return "<img src='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."usersurvey/delete.png'/>";
       
    }

} 
?>