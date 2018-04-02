<?php

class FME_Usersurvey_Adminhtml_UsersurveyController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('usersurvey/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    protected function _isAllowed() {
        return true;
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('usersurvey/usersurvey')->load($id);

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

            $this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit'))
                    ->_addLeft($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('filename');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS;
                    $uploader->save($path, $_FILES['filename']['name']);
                } catch (Exception $e) {
                    
                }

                //this way the name is saved in DB
                $data['filename'] = $_FILES['filename']['name'];
            }


            $model = Mage::getModel('usersurvey/usersurvey');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {


                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('usersurvey')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $resource = Mage::getSingleton('core/resource');
                $write = $resource->getConnection('core_write');

                $tableName = $resource->getTableName('fme_answers');
                $condition = array($write->quoteInto('question_id=?', $this->getRequest()->getParam('id')));
                $question_set_table = $resource->getTableName('fme_questionset');
                $write->query("UPDATE " . $question_set_table . " SET question_ids = TRIM(BOTH ',' FROM REPLACE(REPLACE(question_ids, '" . $this->getRequest()->getParam('id') . "', ''), ',,', ','))");
                $write->delete($tableName, $condition);
                $model = Mage::getModel('usersurvey/usersurvey');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $usersurveyIds = $this->getRequest()->getParam('usersurvey');
        if (!is_array($usersurveyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($usersurveyIds as $usersurveyId) {
                    $usersurvey = Mage::getModel('usersurvey/usersurvey')->load($usersurveyId);
                    $usersurvey->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($usersurveyIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $usersurveyIds = $this->getRequest()->getParam('usersurvey');
        if (!is_array($usersurveyIds)) {
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

    public function exportCsvAction() {
        $fileName = 'usersurvey.csv';
        $content = $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'usersurvey.xml';
        $content = $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

    public function addanswerAction() {
		$this->_initAction();
        $usersurveyId = $this->getRequest()->getParams('id');
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_addanswer'));
		/*$block = $this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_addanswer');  
        $this->getLayout()->getBlock('content')->append($block);*/
        $this->renderLayout();
    }

    public function viewAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('usersurvey/usersurvey')->load($id);

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

            $this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit'))
                    ->_addLeft($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function editanswerAction() {
        $id = $this->getRequest()->getParam('id');
        if (!$id) {

            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
            $this->_redirect('*/*/');
        } else {

            $model = Mage::getModel('usersurvey/answers')->load($id);

            if ($model->getId() || $id == 0) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if (!empty($data)) {
                    $model->setData($data);
                }


                Mage::register('usersurvey_data', $model);

                $this->loadLayout();

                $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

                $this->_addContent($this->getLayout()->createBlock('usersurvey/adminhtml_usersurvey_edit_tab_editanswer'));


                $this->renderLayout();
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('usersurvey')->__('Item does not exist'));
                $this->_redirect('*/*/');
            }
        }
    }

    public function deleteanswerAction() {

        $Ids = $this->getRequest()->getParam('id');
        $myValue = Mage::getSingleton('core/session')->getMyValue();
        Mage::getSingleton('core/session')->unsMyValue();

        if (!$Ids) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                $resource = Mage::getSingleton('core/resource');
                $write = $resource->getConnection('core_write');
                //$connection->beginTransaction();
                $tableName = $resource->getTableName('fme_answers');
                $condition = array($write->quoteInto('answer_id=?', $Ids));
                $write->delete($tableName, $condition);


                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Record was successfully deleted'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/edit', array('id' => $myValue));
    }

    public function saveanswerAction() {

        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);

                    // Set the file upload mode 
                    // false -> get the file directly in the specified folder
                    // true -> get the file in the product like folders 
                    //	(file.jpg will go in something like /media/f/i/file.jpg)
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS;
                    $path = $path . "usersurvey/";
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }

                //this way the name is saved in DB
                $fields['answer_type'] = $_FILES['image']['name'];
            }
        }
        $fields['question_id'] = $data['id'];

        if ($data['question_type'] == 1) {
            $fields['answer_type'] = $data['answer'];
        }

        $fields['description'] = $data['answer_d'];
        $fields['sort_order'] = $data['sort_order'];

        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        //$connection->beginTransaction();
        $tableName = $resource->getTableName('fme_answers');
        $write->insert($tableName, $fields);
        $write->commit();
        $this->_getSession()->addSuccess(
                $this->__('Record is successfully inserted')
        );
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
    }

    public function saveeditanswerAction() {

        if ($data = $this->getRequest()->getPost()) {

            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                try {
                    /* Starting upload */
                    $uploader = new Varien_File_Uploader('image');

                    // Any extention would work
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    // We set media as the upload dir
                    $path = Mage::getBaseDir('media') . DS;
                    $path = $path . "usersurvey/";
                    $uploader->save($path, $_FILES['image']['name']);
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }

                //this way the name is saved in DB
                $data['filename'] = $_FILES['image']['name'];
                $fields['answer_type'] = $data['filename'];
            } else {
				if(isset($_POST['answer']))
                	$fields['answer_type'] = $data['answer'];
            }
        }
        $fields['question_id'] = $data['id'];
        //$fields['question_type'] = $data['question_type'];
        /* if($fields['question_type'] == 1)
          {
          $fields['answer_type'] = $data['filename'];
          } */

        $fields['description'] = $data['answer_d'];
        $fields['sort_order'] = $data['sort_order'];

        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        //$connection->beginTransaction();
        $tableName = $resource->getTableName('fme_answers');


        $where = $write->quoteInto('answer_id =?', $data['answer_id']);
        $write->update($tableName, $fields, $where);
        $write->commit();

        $this->_getSession()->addSuccess(
                $this->__('Record is successfully updated')
        );
        $this->_redirect('*/*/edit', array('id' => $data['id']));
    }

}
