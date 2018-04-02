<?php

class IWD_StockNotification_Block_Adminhtml_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{


    public function __construct()
    {
        parent::__construct ();
        $this->setId ( 'grid' );
        $this->setDefaultSort ( 'id' );
        $this->setDefaultDir ( 'desc' );
        $this->setSaveParametersInSession ( true );
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('stocknotification/notice')->getCollection();
        $collection->getSelect()->joinLeft(array('product'=>$collection->getTable('catalog/product')),'product.entity_id=main_table.product_id',array('sku'));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _afterLoadCollection()
    {
        parent::_afterLoadCollection();
        $columnId = $this->getParam($this->getVarNameSort(), $this->_defaultSort);

            $collection = $this->getCollection();
            foreach ($collection as $item)  {
//                var_dump(strtotime($item->getNotifiedAt()));
                if(!strtotime($item->getNotifiedAt())){
                    $item->setNotifiedAt(false);
                }
                $_productId = $item->getProductId();
                $_store_id = $item->getStoreId();
                $model = Mage::getModel('catalog/product')->setStoreId($_store_id);
                $_product = $model ->load($_productId);
                $item->setProductId($_product->getName());
//                $item->setSku($_product->getSku());

                $_parentId = $item->getParentId ();
                if (empty($_parentId)){
                    $type = $_product->getTypeId();
                    $type = uc_words($type);
                    $item->setParentId($this->helper('stocknotification')->__($type));
                }else{
                    $model = Mage::getModel('catalog/product')->setStoreId($_store_id);
                    $_product = $model ->load($_parentId);
                    $type = $_product->getTypeId();
                    $type = uc_words($type);
                    $item->setParentId($this->helper('stocknotification')->__($type));
                }

                $_customerId = $item->getCustomerId ();

                if (!empty($_customerId)){
                    $model = Mage::getModel('customer/customer')->setStoreId($_store_id);
                    $_customer = $model ->load($_customerId);

                    $item->setCustomerId($_customer->getName());
                }else{
                    $item->setCustomerId($this->helper('stocknotification')->__('Guest'));
                }
            }

        if($columnId == 'product_id' || $columnId == 'parent_id' || $columnId == 'customer_id'){
            $items = $collection->getItems();
            $dir = $this->getParam($this->getVarNameDir(), $this->_defaultDir);
            if($dir=='asc') {
                usort ($items, function($elem1, $elem2) use($columnId){
                    return strcmp($elem1->getData($columnId), $elem2->getData($columnId));
                });
            } else {
                usort ($items, function($elem1, $elem2) use($columnId){
                    return strcmp($elem2->getData($columnId), $elem1->getData($columnId));
                });
            }
            foreach($collection as $key=>$item){
                $collection->removeItemByKey($key);
            }
            foreach($items as $key=>$item){
                $collection->addItem($items[$key]);
            }
            $this->setCollection($collection);
        }
        return $this;
    }

    protected function _prepareColumns() 
    {

        $this->addColumn ( 'id',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'ID' ),
                'align' => 'center',
                'index' => 'entity_id',
                'width' => '60'
            )
        );

        $this->addColumn ( 'product_id',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Product Name' ),
                'align' => 'left',
                'index' => 'product_id',
                'filter'=>false
            )
        );

        $this->addColumn ( 'sku',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Product Sku' ),
                'align' => 'left',
                'index' => 'sku',
//                'filter'=>false
            )
        );

        $this->addColumn ( 'parent_id',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Type' ),
                'align' => 'left',
                'index' => 'parent_id',
                'filter'=>false
            )
        );

        $this->addColumn ( 'customer_id',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Customer Name' ),
                'align' => 'left',
                'index' => 'customer_id',
                'filter'=>false
            )
        );


        $this->addColumn ( 'email',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Email' ),
                'align' => 'left',
                'index' => 'email'
            )
        );


        $this->addColumn ( 'created',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Created' ),
                'align' => 'left',
                'index' => 'created_at',
                'type' => 'datetime'
            )
        );

        $this->addColumn ( 'notified',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Notified' ),
                'align' => 'left',
                'index' => 'notified_at',
                'type' => 'datetime'
            )
        );

        $this->addColumn ( 'is_notified',
            array (
                'header' => Mage::helper ( 'stocknotification' )->__ ( 'Status' ),
                'align' => 'left',
                'index' => 'is_notified',
                'type'      => 'options',
                'options'   => array(1 => $this->__('Notified'), 0 => $this->__('Waiting')),
            	'frame_callback' => array($this, 'decorateStatus')
            )
        );

        
        $this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('customer')->__('Excel XML'));
        
        return parent::_prepareColumns ();
    }
    
    
    public function decorateStatus($value, $row, $column, $isExport)
    {
        if($isExport){
            return $value;
        }
        if ($row->getIsNotified()) {
            $cell = '<span class="grid-severity-notice"><span>'.$value.'</span></span>';
        } else {
            $cell = '<span class="grid-severity-critical"><span>'.$value.'</span></span>';
        }
    	
    	return $cell;
    }
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_id');
        $this->getMassactionBlock()->addItem('delete_selected', array(
            'label' => Mage::helper('stocknotification')->__('Delete selected'),
            'url' => $this->getUrl('adminhtml/iwd_stocknotification_list/delete', array('redirect' => '*/list')),
            'confirm' => Mage::helper('stocknotification')->__('Are you sure?')
        ));

        return $this;
    }
}