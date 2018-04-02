<?php
class FME_Usersurvey_Block_Adminhtml_Renderer_Stores extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
    
         $tbl = '';
          $tbl2 = '';
        if($row->getSetId()==""){
            return "";
        }
        else{
             $resource = Mage::getSingleton('core/resource');
             $read = $resource->getConnection('core_read');          
             $tableName = $resource->getTableName('questionset');
       
    $tableName2 = $resource->getTableName('usersurvey/questionset_locations');

    $result = $read->fetchAll("select `values` from  ".$tableName2." where set_id = ".$row->getSetId());
      
       //   $result = $read->fetchAll("select * from  ".$resource->getTableName('question_set_store')." where set_qid =".$row->getSetQid());
           foreach($result as $row)
           {
              
               $store[] = $row['values'];
                switch ($row['values']) {
                   case '1':
                      $varinle[] = 'Registration';
                       break;
                   case '2':
                    $varinle[] = 'Frontend';
                      break;
                   case '0':
                    $varinle[] = 'Disable';
                      break;
                  
      }
           }
     

           $i = 0;
      
           foreach($varinle as $key => $value)
           {
              // print_r($store_name->getName());
                
              $tbl2  .= $value."  <br />";
               $i++;
           }
    
            return $tbl2;
        }
 
    }

} 
?>