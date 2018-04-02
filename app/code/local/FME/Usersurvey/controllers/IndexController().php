<?php
class FME_Usersurvey_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/usersurvey?id=15 
    	 *  or
    	 * http://site.com/usersurvey/id/15 	
    	 */
    	/* 
		$usersurvey_id = $this->getRequest()->getParam('id');

  		if($usersurvey_id != null && $usersurvey_id != '')	{
			$usersurvey = Mage::getModel('usersurvey/usersurvey')->load($usersurvey_id)->getData();
		} else {
			$usersurvey = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($usersurvey == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$usersurveyTable = $resource->getTableName('usersurvey');
			
			$select = $read->select()
			   ->from($usersurveyTable,array('usersurvey_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$usersurvey = $read->fetchRow($select);
		}
		Mage::register('usersurvey', $usersurvey);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}