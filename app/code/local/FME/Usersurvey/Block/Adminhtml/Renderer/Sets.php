<?php
class FME_Usersurvey_Block_Adminhtml_Renderer_Sets extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
    
         $tbl = '';
        if($row->getQuestionId()==""){
            return "";
        }
        else{
             $resource = Mage::getSingleton('core/resource');
             $read = $resource->getConnection('core_read');
            
             $tableName = $resource->getTableName('fme_questionset');
            $result = $read->fetchAll("select title from  ".$tableName." where FIND_IN_SET('".$row->getQuestionId()."',question_ids)");
            foreach($result as $row)
            {
                $tbl .= $row['title'].", <br />";
            }
             
            }
            return $tbl;
        }
 
   

} 
?>