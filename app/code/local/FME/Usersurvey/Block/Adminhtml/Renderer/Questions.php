<?php
class FME_Usersurvey_Block_Adminhtml_Renderer_Questions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        
         $rw = '';
        // print_r($row); exit();
        if($row->getSetQid()==""){
            return "";
        }
        else{
             $resource = Mage::getSingleton('core/resource');
             $read = $resource->getConnection('core_read');
            
             $tableName = $resource->getTableName('questionset');
            $result = $read->fetchAll("select question_ids from  ".$tableName." where set_qid = ".$row['set_qid']);
           
           
                foreach($result as $row)
                {
                    $tbl[]= $row['question_ids'];
                }
                //print_r($tbl); 
                if(count($tbl)>0)
                {
                    $ids = join(',',$tbl);  
                    $result = $read->fetchAll("select title from  ".$resource->getTableName('usersurvey')." where usersurvey_id in ( ".$ids.")");
                    foreach($result as $row)
                    {
                        $rw .= $row['title']." <br />";
                    } 
                }
                        
             
            }
            return $rw;
        }
 
   

} 
?>