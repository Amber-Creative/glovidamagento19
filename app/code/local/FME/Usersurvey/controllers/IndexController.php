<?php
class FME_Usersurvey_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    			
		$this->loadLayout();     
		$this->renderLayout();
    }
     public function oddAction($var)
    {
	 return($var & 1);
    }
 
	
    public function evenAction($var)
    {
	    // returns whether the input integer is even
	    return(!($var & 1));
    }
     public function getajaxrequestAction()
    {
	
	    $resource = Mage::getSingleton('core/resource');
		$write = $resource->getConnection('core_write'); 
    	$read = $resource->getConnection('core_read');
      	$tableName = $resource->getTableName('fme_answers');	
		//$connection->beginTransaction();
		$arr = array();
		$qstid = '';
		
		if(isset($_POST['result']))
			$arr = $_POST['result'];
		if(isset($_POST['qstid']))
			$qstid = $_POST['qstid'];
		
		 $type = Mage::helper('usersurvey')->getsurvytype();
		
		$i=0;
		$result=array();
		if(!empty($arr)){
			while($i<count($arr)){
				$keys = explode("_",$arr[$i++]);
				$key  = $keys[1];
				$val = $arr[$i++];
				//echo "select votes from  ".$tableName." where answer_id = ".$val;
				$result = $write->fetchRow("select votes from  ".$tableName." where answer_id = ".$val);
				$votes = $result['votes'];
				$new_vote = $votes+1;
				$fields = array();
				if($type == '1'){
					$ip = $_SERVER["REMOTE_ADDR"];
					$tableName2 = $resource->getTableName('fme_questionset');
					$where = $write->quoteInto('set_id =?', $qstid);
					$write->update($tableName2, array('ip'=> $ip),$where);
					$write->commit();			
				}
				$fields['votes'] = $new_vote;
				$where = $write->quoteInto('answer_id =?', $val);
				$write->update($tableName, $fields, $where);
				$write->commit();
			}
			echo "<h3><center>Thank you for your feedback.</center></h3>";
			exit;
		}else{
			echo "1";exit;
		}
    }
}