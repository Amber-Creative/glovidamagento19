<?php
class IWD_StockNOtification_Model_Verify extends Mage_Core_Model_Abstract
{
    const XML_PATH_EMAIL_SENDER = 'stocknotification/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'stocknotification/email/email_template';

    public function verify(){
        $collection = Mage::getModel('stocknotification/notice')->getCollection()
            ->addFieldToFilter('is_notified', array('eq'=>'0'));
        foreach($collection as $_item){
            $model  = Mage::getModel('catalog/product')->setStoreId($_item->getStoreId());
            $product = $model->load($_item->getProductId());
            $stockItem = $product->getStockItem();
            if (empty($stockItem) || !$stockItem->getIsInStock() || $product->getStatus() == 2 || !$product->isSaleable()){
                continue;
            }
            try{
                $this->sendEmailNotification($_item);
                $this->updateStatus($_item);
            }catch(Exception $e){
                Mage::log($e->getMessage(), null, 'iwd_sn.log');
            }
        }
    }

    public function updateStatus($_item){
        $model = Mage::getModel('stocknotification/notice')->load($_item->getId());
        $model->setData('is_notified','1');
        $model->setData('notified_at', now() );
        try{
            $model->save();
        }catch(Exception $e){
            Mage::logException($e);
        }
    }

    public function sendEmailNotification($_item){

        $productData = $this->prepareProductData($_item);

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $postObject = new Varien_Object();
        $postObject->setData($productData);


        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */
        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
            ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER,$_item->getStoreId()))
            ->sendTransactional(
                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE, $_item->getStoreId()),
                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER,$_item->getStoreId()),
                $_item->getEmail(),
                null,
                array('data' => $postObject)
            );

        if (!$mailTemplate->getSentSuccess()) {
            throw new Exception();
        }

        $translate->setTranslateInline(true);
    }

    private function prepareProductData($item){
        $data = array();
        $model  = Mage::getModel('catalog/product')->setStoreId($item->getStoreId());
        $product = $model->load($item->getProductId());

        if ($item->getParentId()) {
            $model = Mage::getModel('catalog/product')->setStoreId($item->getStoreId());
            $product = $model->load($item->getParentId());
        }

        $data['url'] = $product->getProductUrl();
        $data['name'] = $product->getName();
//        $data['image'] = Mage::helper('catalog/image')->init($product, 'small_image')->resize(240,148);
        return $data;
    }

}