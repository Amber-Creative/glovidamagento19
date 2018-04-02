<?php
/**
 * Usersurvey extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   FME
 * @package    FME_Usersurvey
 * @author     Mahmood Rehman<mahmood.rehman@gmail.com>
 * @copyright  Copyright 2010 © free-magentoextensions.com All right reserved
 */

class FME_Usersurvey_Adminhtml_QuestionsetController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('usersurvey/question_set')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Applications Manager'), Mage::helper('adminhtml')->__('Applications Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		
		$this->_initAction()
			->renderLayout();
	}
	
protected function _initProductTest() {
		
		$producttestId  = (int) Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
		
		if (!$producttestId) {
		    return false;
		}
		
		$productest = Mage::getModel('usersurvey/questionset');
		if ($producttestId) {
			$productest->load($producttestId);
		}
			
		Mage::register('current_questions_set', $productest);
		
		return $productest;
			
	}
	public function editAction() {
		
		 $this->_initProducttest();
		
		 $productsarray = array();
		
		$id     = $this->getRequest()->getParam('id');
		  if($id != 0) {
			 $set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
			   if ($set_id) {
				 $productsarray = $set_id;
			   }
			  
			   if($productsarray){ 
				   array_push($_POST["set_qid"],$productsarray);
			   }
			  
			   Mage::registry('current_questions_set')->getRelatedQuestions($productsarray);
		   }
		$model  = Mage::getModel('usersurvey/questionset')->load($id);
		
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('usersurvey_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('usersurvey/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit'))
				->_addLeft($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		//$this->loadLayout();
		//$this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tab_form')) 
		//->_addLeft($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tabs'));
		//$this->renderLayout();
		$this->_forward('edit');
	}

	public function saveAction() {
			   
		if($this->getRequest()->getParam('id') > 0) {
			
			
		
		if ($data = $this->getRequest()->getPost()) {
			$set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
			//$model = Mage::getModel('usersurvey/questionset');		
			//$model->setData($data)
			//	->setId($this->getRequest()->getParam('id'));
			
			try {
				  $resource = Mage::getSingleton('core/resource');
				   $connection = Mage::getSingleton('core/resource')
						->getConnection('core_write');
				 $write = $resource->getConnection('core_write'); 
				 $arr = $this->getRequest()->getPost('in_products');
		//----------- Insert store values ---------------	
			   $conditions = array($write->quoteInto('set_id =?', $this->getRequest()->getParam('id')));
		  	   $write->delete($resource->getTableName('usersurvey/questionset_store'), $conditions);	 
			   $arr = (array)$this->getRequest()->getParam('stores');      
        if(in_array('0', $arr))
    {
		        $allStores = Mage::app()->getStores();
		foreach ($allStores as $_eachStoreId => $val)
		{
		$_storeId[] = Mage::app()->getStore($_eachStoreId)->getId();

		}
		foreach ($_storeId as $store) {
		            $storeArray = array();
		            $storeArray['set_id'] = $this->getRequest()->getParam('id');
		            $storeArray['store_id'] = $store;
		            $write->insert($resource->getTableName('usersurvey/questionset_store'), $storeArray);
		        }
		    }else{
		        foreach ((array)$this->getRequest()->getParam('stores') as $store) {
		            $storeArray = array();
		            $storeArray['set_id'] = $this->getRequest()->getParam('id');
		            $storeArray['store_id'] = $store;
		            $write->insert($resource->getTableName('usersurvey/questionset_store'), $storeArray);
		        }    
		       
		      }
		//----------- Insert store values ---------------		      
		if(sizeof($this->getRequest()->getPost('in_products'))== '1' and $arr!= 0)
		{
		  $arrs = $this->getRequest()->getPost('in_products');
		   $condition = array($write->quoteInto('set_qid =?', $set_id));
		   $write->delete($resource->getTableName('usersurvey/questionset'), $condition);
		   $write->insert($resource->getTableName('usersurvey/questionset'), array('question_ids' => $arr[0],'set_qid' => $set_id,'title' => $data['titles']));
		   
		   
		}elseif(sizeof($this->getRequest()->getPost('in_products')) > 1)
		{
			
			    


			 $arr2 = $this->getRequest()->getPost('in_products');
		$condition = array($connection->quoteInto('set_qid =?', $set_id));
				 $connection->delete($resource->getTableName('usersurvey/questionset'), $condition);
			 $newarray = array();
			 foreach($arr2 as $key => $val)
			{
				
				$newarray['question_ids'] = $val;
				$newarray['set_qid']      = $set_id;
				$newarray['title']       = $data['titles'];
				
				$write->insert($resource->getTableName('usersurvey/questionset'), $newarray);
				
			}
			
		}
		
    $fields = array();
     $conditions = array($write->quoteInto('set_id =?', $this->getRequest()->getParam('id')));
	 $write->delete($resource->getTableName('usersurvey/questionset_locations'), $conditions);		
   
    $arr_des = $this->getRequest()->getPost('design');
 
    $tableName = $resource->getTableName('usersurvey/questionset_locations');
    $nfields = array();
    foreach($arr_des as $key => $val)
    {  
    $nfields['values'] = $val;  
     $nfields['set_id'] =$this->getRequest()->getParam('id'); 
    $write->insert($resource->getTableName('usersurvey/questionset_locations'), $nfields);
    $write->commit();
    }
   


				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('usersurvey')->__('Record was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
	}else{
		if ($data = $this->getRequest()->getPost()) {
			
			//$model = Mage::getModel('usersurvey/questionset');		
			//$model->setData($data)
			//	->setId($this->getRequest()->getParam('id'));
			
			try {
				 $connection = Mage::getSingleton('core/resource')
						->getConnection('core_write');
				 $connection->beginTransaction();
				  $resource = Mage::getSingleton('core/resource');
				$write = $resource->getConnection('core_write');
				
				 $arr = $this->getRequest()->getPost('in_products');
				 $newarray['title']       = $data['titles'];
				
				$connection->insert($resource->getTableName('usersurvey/questionset'), $newarray);
				$lastInsertId = $connection->lastInsertId();
				 $condition = array($write->quoteInto('set_id =?', $lastInsertId));
				 $write->delete($resource->getTableName('usersurvey/questionset'), $condition);
		if(sizeof($this->getRequest()->getPost('in_products'))== '1' and $arr!= 0)
		{
		
		 
		   $write->insert($resource->getTableName('usersurvey/questionset'), array('question_ids' => $arr[0],'set_qid' => $lastInsertId,'title' => $data['titles']));
		   $lastInsertId2 = $connection->lastInsertId();
		   
		}elseif(sizeof($this->getRequest()->getPost('in_products')) > 1)
		{
			  

			 $arr2 = $this->getRequest()->getPost('in_products');
			 $newarray = array();
			 foreach($arr2 as $key => $val)
			{
				$newarray['question_ids'] = $val;				
				$newarray['title']       = $data['titles'];
				$newarray['set_qid']       = $lastInsertId;
				$write->insert($resource->getTableName('usersurvey/questionset'), $newarray);
				$lastInsertId2 = $connection->lastInsertId();
			}
			
		}else{
			
			$fields = array();		
    $fields['title'] = $data['titles'];
    $fields['set_qid'] =$lastInsertId;   
    $write->insert($resource->getTableName('usersurvey/questionset'), $fields);
    $lastInsertId2 = $connection->lastInsertId();
		$write->commit(); 
		}
  
	$arr = (array)$this->getRequest()->getParam('stores');      
        if(in_array('0', $arr))
    {
		        $allStores = Mage::app()->getStores();
		foreach ($allStores as $_eachStoreId => $val)
		{
		$_storeId[] = Mage::app()->getStore($_eachStoreId)->getId();

		}
		foreach ($_storeId as $store) {
		            $storeArray = array();
		            $storeArray['set_id'] = $lastInsertId2;
		            $storeArray['store_id'] = $store;
		            $write->insert($resource->getTableName('usersurvey/questionset_store'), $storeArray);
		        }
		    }else{
		        foreach ((array)$this->getRequest()->getParam('stores') as $store) {
		            $storeArray = array();
		            $storeArray['set_id'] = $lastInsertId2;
		            $storeArray['store_id'] = $store;
		            $write->insert($resource->getTableName('usersurvey/questionset_store'), $storeArray);
		        }    
		       
		      }
   
    $arr_des = $this->getRequest()->getPost('design');
 
    $tableName = $resource->getTableName('usersurvey/questionset_locations');
    $nfields = array();
    foreach($arr_des as $key => $val)
    {  
     $nfields['values'] = $val;  
     $nfields['set_id'] = $lastInsertId2; 
    $write->insert($resource->getTableName('usersurvey/questionset_locations'), $nfields);
    $write->commit();
    }
   


				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('usersurvey')->__('Record was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
			
		}
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Unable to find Record'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		
		if( $this->getRequest()->getParam('id') > 0 ) {
                    $path = Mage::getBaseDir('media') . DS ."usersurvey". DS. "cv". DS;
			try {
				 $resource = Mage::getSingleton('core/resource');
				$write = $resource->getConnection('core_write');
				 Mage::helper('usersurvey')->deleteshit($this->getRequest()->getParam('id'));
				 $tableName = $resource->getTableName('usersurvey/questionset_locations');
				$set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
				 
				
				$condition = array($write->quoteInto('set_id=?',$this->getRequest()->getParam('id')));
				$write->delete($tableName, $condition);
				$condition = array($write->quoteInto('set_qid=?',$set_id));
				$write->delete($resource->getTableName('usersurvey/questionset'), $condition);

				$model = Mage::getModel('usersurvey/questionset')->load($this->getRequest()->getParam('id'));
                                $cv_filename = $model->getData('cvfile');
                                $model->setId($this->getRequest()->getParam('id'))
                                        ->delete();
                        
                                unlink($path.$cv_filename);

                                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Record was successfully deleted'));
                                $this->_redirect('*/*/');
                                
                                

			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $jobsIds = $this->getRequest()->getParam('usersurvey');
        if(!is_array($jobsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Record(s)'));
        } else {
            $path = Mage::getBaseDir('media') . DS ."usersurvey". DS. "cv". DS;
            try {
                foreach ($jobsIds as $jobsId) {
                     Mage::helper('usersurvey')->deleteshit($jobsId);
                    $usersurvey = Mage::getModel('usersurvey/questionset')->load($jobsId);
                    $cv_filename = $usersurvey->getData('cvfile');
                        $usersurvey->delete();
                        
                    unlink($path.$cv_filename);
                        
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($jobsIds)
                    )
                );

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
 
 public function massStatusAction()
    {
        $usersurveyIds = $this->getRequest()->getParam('usersurvey');
        if(!is_array($usersurveyIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($usersurveyIds as $usersurveyId) {
                    $usersurvey = Mage::getSingleton('usersurvey/usersurvey')
                        ->load($usersurveyId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($usersurveyIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
   //================================================================================
   
   
   public function storeformAction()
   {
	
	 $this->loadLayout();
	   $this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tab_storeform'));  
	  // $this->getLayout()->getBlock('content')->append($block);  
	   $this->renderLayout(); 
   }


	  public function viewAction()
    {
	/* $this->loadLayout();
	   $block = $this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tab_setanswers');  
	   $this->getLayout()->getBlock('content')->append($block);  
	   $this->renderLayout();*/
	 $this->_initProducttest();
		
		 $productsarray = array();
	$id     = $this->getRequest()->getParam('id');
		  if($id != 0) {
			 $set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
			   if ($set_id) {
				 $productsarray = $set_id;
			   }
			  
			   if($productsarray){ 
				   array_push($_POST["set_qid"],$productsarray);
			   }
			  
			   Mage::registry('current_questions_set')->getRelatedQuestions($productsarray);
		   }
		$model  = Mage::getModel('usersurvey/questionset')->load($id);
		
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('usersurvey_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('usersurvey/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit'))
				->_addLeft($this->getLayout()->createBlock('usersurvey/adminhtml_questionset_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	
    }
    public function customerAction(){
	$this->_initProducttest();
		
		 $productsarray = array();
		
		$id     = $this->getRequest()->getParam('id');
		  if($id != 0) {
			 $set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
			   if ($set_id) {
				 $productsarray = $set_id;
			   }
			  
			   if($productsarray){ 
				   array_push($_POST["set_qid"],$productsarray);
			   }
			  
			   Mage::registry('current_questions_set')->getRelatedQuestions($productsarray);
		   }
        $this->loadLayout();
        $this->getLayout()->getBlock('customer.grid');
        $this->renderLayout();
    }
}