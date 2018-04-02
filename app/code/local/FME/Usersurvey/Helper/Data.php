<?php

class FME_Usersurvey_Helper_Data extends Mage_Core_Helper_Abstract
{
     const XML_EXTENSION_ENABLED                  =      'usersurvey/setting/settings';
     const XML_SURVEY_TYPE	                      =	     'usersurvey/setting/survey_type';
      
      public function getextensionenable()
      {
         return  Mage::getStoreConfig('usersurvey/setting/settings');
      }
      
       public function getsurvytype()
      {
         return  Mage::getStoreConfig( 'usersurvey/setting/survey_type');
      }
      public function getimagewidth()
      {
         return  Mage::getStoreConfig( 'usersurvey/front_setting/image_width');
      }
      public function getimageheight()
      {
         return  Mage::getStoreConfig( 'usersurvey/front_setting/image_height');
      }
      
  //---------------- functions ----------------------------------------    
   public function getfield($field,$id)
   {
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName('fme_usersurvey');
    $result = $read->fetchRow("select ".$field." from  ".$tableName." where question_id = ".$id);
     return $result[$field];
    
   }
   public function getstores()
  {
    $resource = Mage::getSingleton('core/resource');
    $read= $resource->getConnection('core_read');
    $pressTable = $resource->getTableName('fme_questionset');
    $qry = "select store_id FROM ".$pressTable." where set_id <> 0"; //query           
    $rest = $read->fetchAll($qry);
             foreach ($rest as $value) {
              $scope_ids[] = $value['store_id'];
             }
             return $scope_ids;
  }
    public function deleteshit($sid)
  {
      
        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        $tableName = $resource->getTableName('fme_questionset');
        $write->query("delete from ".$tableName." where set_id =".$sid."");
        $tableName = $resource->getTableName('fme_questionset');
        $write->query("delete from ".$tableName." where set_id =".$sid."");
         return 0;
     
  }
    public function getfieldname($field = null,$id = null,$title,$tbl)
   {
     if($id){
     
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName($tbl);
    $result = $read->fetchRow("select ".$field." from  ".$tableName." where ".$title." = ".$id);
     return $result[$field];
     }
   }
   public function getvotes($votes,$tno)
   {
     if($tno!=0 && $tno>0){
		 $result =  ($votes * 100)/$tno;
		 return round($result,2);
	 }else{
	 	 return 0;
	 }
   }
   
    public function gettotalvotes($id = null)
   {
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
     $tableName = $resource->getTableName('fme_answers');
    $result = $read->fetchAll("select  votes from  ".$tableName." where question_id IN (".$id.")");
    $arr = array();
    foreach($result as $rw)
    {
        $arr[] =  $rw['votes'];
    }
       return array_sum($arr);
    
   }
   
    public function byfieldname($id = null,$tbl,$field,$store_id)
   {
      if($id){
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName($tbl);
    $result = $read->fetchRow("select store_value from  ".$tableName." where ".$field." = ".$id." and store_id =".$store_id);
     return $result['store_value'];
      }
   }
   
   public function getsurveydata()
   {
      $resource = Mage::getSingleton('core/resource');
      $read = $resource->getConnection('core_read');
      $table = $resource->getTableName('fme_questionset');
     
      $stores = Mage::app()->getStore()->getId();
     
      //print_r($stores); exit();
      $collection = Mage::getModel('usersurvey/questionset')->getCollection();
      $collection->addFieldToFilter('locations',array('finset' => 1));
      $collection->addFieldToFilter('stores',  array(
                 array('finset' => '0'),
                 array('finset' => $stores))
               );
      $collection->setOrder('set_id','DESC');
      $collection->getSelect()->limit(1);
      //echo $collection->getSelect(); exit();
      $row = $collection->getData();
      $rows = $row[0];
      //print_r($rows); exit(); 
      return $rows;
      
   }
    public function getcmssurveydata()
   {

      $resource = Mage::getSingleton('core/resource');
      $read = $resource->getConnection('core_read');
      $table = $resource->getTableName('fme_questionset');
     
      $stores = Mage::app()->getStore()->getId();
     
      //print_r($stores); exit();
      $collection = Mage::getModel('usersurvey/questionset')->getCollection();
      $collection->addFieldToFilter('locations',array('finset' => 0));
      $collection->addFieldToFilter('stores',  array(
                 array('finset' => '0'),
                 array('finset' => $stores))
               );
      $collection->setOrder('set_id','DESC');
      $collection->getSelect()->limit(1);
      //echo $collection->getSelect(); exit();
      //$row = $collection->getData();
	  $rows = $collection->getFirstItem()->getData();
      //$rows = $row[0];
      //print_r($rows); exit(); 
      return $rows;
   }
    public function getcmssurveyiddata($id = null)
   {
      $resource = Mage::getSingleton('core/resource');
      $read = $resource->getConnection('core_read');
      $tableName = $resource->getTableName('fme_questionset');
    
    $rows = $read->fetchRow("select st.set_id from  ".$tableName." as st INNER JOIN ".$resource->getTableName('fme_questionset')." as sets on st.set_id = sets.set_id INNER JOIN ".$resource->getTableName('fme_questionset')." as store on st.set_id = store.set_id  where sets.values = 2 and st.set_id = ".$id." and store.store_id =".Mage::app()->getStore()->getId()." order by st.set_id DESC limit 1");
   if(!empty($rows['set_id']))
   {

       $result = $read->fetchAll("select * from  ".$tableName." as st  where st.set_id =".$rows['set_id']."  order by st.set_id DESC");
       return $result;
       }
      /*$result = $read->fetchAll("select * from  ".$tableName." as st INNER JOIN ".$resource->getTableName('fme_questionset')." as sets on st.set_id = sets.set_id INNER JOIN ".$resource->getTableName('fme_questionset')." as store on st.set_id = store.set_id  where sets.values = 2 and st.set_id = ".$id." and store.store_id =".Mage::app()->getStore()->getId()." order by st.set_id DESC");
      return $result;*/
   }
  //----------  ip base selection -----------------------------------------------------------//
  
    public function getcmsipsurveydata($ip = null)
   {
      
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName('fme_questionset');
    
    $result = $read->fetchAll("select ip from  ".$tableName." as st  where st.ip = '".$ip."' order by st.set_id DESC");
     return $result;
   }
    public function getipsurveydata($ip = null)
   {
      
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName('fme_questionset');
    
    $result = $read->fetchAll("select ip from  ".$tableName." as st where st.ip = '".$ip."' order by st.set_id DESC");
     return $result;
   }
     public function getvaluesset($id = null)
   {
      
    $resource = Mage::getSingleton('core/resource');
    $read = $resource->getConnection('core_read');
    $tableName = $resource->getTableName('fme_questionset');

    $result = $read->fetchAll("select `values` from  ".$tableName."   where set_id =".$id."");
     return $result;
   }

   public function getQuestionType($id)
   {
      $model = Mage::getModel('usersurvey/usersurvey')->load($id);
      return $model->getType();
   }
}