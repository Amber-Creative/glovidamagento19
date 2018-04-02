<?php
class IWD_StockNotification_Model_Observer{


    const XML_PATH_ENABLED = 'stocknotification/default/status';
    const XML_PATH_ENABLED_CRON = 'stocknotification/default/auto_verify_enable';

    public function scheduledVerifyStock(){
        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED)|| !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED_CRON) ) {
            return;
        }
        try{
            Mage::getModel('stocknotification/verify')->verify();
        } catch(Exception $e){
            Mage::log($e->getMessage(), null, 'iwd_sn.log');
        }
    }

    public function verifyStockStatus($observer)
    {
        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            return;
        }

        $_product  = $observer->getProduct();
        $stockItem = $_product->getStockItem();

        if(!$_product || $_product->_isObjectNew || $_product->getStatus()==2 || empty($stockItem) || !$stockItem->getIsInStock()){
            return;
        }

        $collection = Mage::getModel('stocknotification/notice')->getCollection()
            ->addFieldToFilter('product_id', array('eq'=>$_product->getId()))
            ->addFieldToFilter('is_notified', array('eq'=>'0'));

        foreach($collection as $_item){
            try{
                Mage::getModel('stocknotification/verify')->sendEmailNotification($_item);
                Mage::getModel('stocknotification/verify')->updateStatus($_item);
            }catch(Exception $e){
                Mage::logException($e);
            }
        }
    }


    public function verifyStockStatusBundleProduct($observer)
    {
        if(!Mage::getStoreConfigFlag(self::XML_PATH_ENABLED)) {
            return;
        }

        if(Mage::helper('stocknotification')->isSkipBundleProducts()){
            return;
        }

        $_product  = $observer->getProduct(); //select current product
        if($_product){
            $bundled_product_model = Mage::getModel('bundle/product_type');
            $bundled_products_ids = $bundled_product_model->getParentIdsByChild($_product->getId()); //select ids bundle items of this product
            if($bundled_products_ids){
                foreach($bundled_products_ids as $bundle_product_id){
                    $collection = Mage::getModel('stocknotification/notice')->getCollection()
                        ->addFieldToFilter('is_notified', array('eq'=>'0'))
                        ->addFieldToFilter('product_id', array('eq'=>$bundle_product_id))
                        ->addFieldToFilter('parent_id', array('null'=>true));
                    foreach($collection as $_item){
                        $model  = Mage::getModel('catalog/product')->setStoreId($_item->getStoreId());
                        $bundle_product = $model->load($bundle_product_id); //load one bundle product
                        if ($bundle_product->isSaleable()){
                            try{
                                Mage::getModel('stocknotification/verify')->sendEmailNotification($_item);
                                Mage::getModel('stocknotification/verify')->updateStatus($_item);
                            }catch(Exception $e){
                                Mage::logException($e);
                            }
                        }
                    }
                }
            }
        }
    }
}