<?php
class IWD_StockNotification_Adminhtml_Iwd_Stocknotification_ListController extends Mage_Adminhtml_Controller_Action{
    public function verifyManuallyAction()
    {
        try{
            Mage::getModel('stocknotification/verify')->verify();
        } catch(Exception $e){
            Mage::log($e->getMessage(), null, 'iwd_sn.log');
        }
        $this->_redirect('adminhtml/system_config/edit/section/stocknotification');
        return;
    }
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('iwdall/stocknotification')
            ->_addBreadcrumb(Mage::helper('stocknotification')->__('Out of stock notification'), Mage::helper('stocknotification')->__('Out of stock notification'))
            ->_addBreadcrumb(Mage::helper('stocknotification')->__('View Subscribers'), Mage::helper('stocknotification')->__('View Subscribers'));
        $this->getLayout()->getBlock('head')->addJs('iwd/stocknotification/adminhtml/stocknotification.js');
        return $this;
    }

    public function deleteAction(){
        $redirect = $this->getRequest()->getParam('redirect');
        $redirect = (empty($redirect)) ? "*/adminhtml_list/index" : "*/{$redirect}/index";
        $checked_notifications = $this->getCheckedNotificationsIds();
        $count = 0;
        foreach ($checked_notifications as $id) {
            $notification = Mage::getModel('stocknotification/notice')->load($id);
            if($notification){
                $res = $notification->delete();
                if($res){
                    $count++;
                }
            }
        }
        $message = sprintf(Mage::helper('stocknotification')->__('Elements were deleted: %s'), $count);
        $this->addMessageSuccess($message);
        $this->_redirect($redirect);
    }

    public function deleteNotifiedAction(){
        $collection = Mage::getModel('stocknotification/notice')->getCollection();
        $collection->addFieldToFilter('is_notified',true);
        $count = 0;
        foreach($collection as $notification){
            $res = $notification->delete();
            if($res){
                $count++;
            }
        }
        if($count){
            $message = sprintf(Mage::helper('stocknotification')->__('Elements were deleted: %s'), $count);
            $this->addMessageSuccess($message);
        }else{
            $message = Mage::helper('stocknotification')->__('Nope notice elements');
            $this->addMessageNotice($message);
        }
        return Mage::helper("adminhtml")->getUrl('adminhtml/iwd_stocknotification_list/index');
    }

    public function deleteNotNotifiedAction(){
        $collection = Mage::getModel('stocknotification/notice')->getCollection();
        $collection->addFieldToFilter('is_notified',false);
        $count = 0;
        foreach($collection as $notification){
            $res = $notification->delete();
            if($res){
                $count++;
            }
        }
        if($count){
            $message = sprintf(Mage::helper('stocknotification')->__('Elements were deleted: %s'), $count);
            $this->addMessageSuccess($message);
        }else{
            $message = Mage::helper('stocknotification')->__('Nope waiting elements');
            $this->addMessageNotice($message);
        }

        return Mage::helper("adminhtml")->getUrl('adminhtml/iwd_stocknotification_list/index');
    }

    protected function addMessageSuccess($message){
        Mage::getSingleton('adminhtml/session')->addSuccess($message);
    }

    protected function addMessageNotice($message){
        Mage::getSingleton('adminhtml/session')->addNotice($message);
    }

    protected function getCheckedNotificationsIds(){
        $checked_orders = $this->getRequest()->getParam('entity_id');
        if (!is_array($checked_orders)){
            $checked_orders = array($checked_orders);
        }
        return $checked_orders;
    }

    public function indexAction()
    {
        $this->_title($this->__('IWD'))
            ->_title($this->__('Stock Notification'))
            ->_title($this->__('View Subscribers'));

        $this->_initAction();
        $this->renderLayout();
    }
    
    protected function _isAllowed()
    {
    	return Mage::getSingleton('admin/session')->isAllowed('system/iwdall/stocknotification');
    }
    
    
    /**
     * Export customer grid to CSV format
     */
    public function exportCsvAction()
    {
    	$fileName   = 'stockrequest.csv';
    	$content    = $this->getLayout()->createBlock('stocknotification/adminhtml_list_grid')
										->getCsv();

    	$this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * Export customer grid to XML format
     */
    public function exportXmlAction()
    {
    	$fileName   = 'stockrequest.xml';
    	$content    = $this->getLayout()->createBlock('stocknotification/adminhtml_list_grid')
    									->getExcelFile();
    
    	$this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * Prepare file download response
     */
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
    	$this->_prepareDownloadResponse($fileName, $content, $contentType);
    }
}