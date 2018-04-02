<?php

class IWD_StockNotification_Block_Adminhtml_List extends  Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_list';
        $this->_blockGroup = 'stocknotification';
        $this->_headerText = Mage::helper ( 'stocknotification' )->__ ( 'View Subscribers' );
        parent::__construct ();
        $this->removeButton('add');
        $this->addButton('delete_notified',array(
            'label'=>Mage::helper ( 'stocknotification' )->__ ( 'Delete All Notified' ),
            'onclick'   =>"delete_notifications('".Mage::helper("adminhtml")->getUrl('adminhtml/iwd_stocknotification_list/deleteNotified')."')",
            'id' => 'delete_notified',
            'class'=> '',
        ));
        $this->addButton('delete_not_notified',array(
            'label'=>Mage::helper ( 'stocknotification' )->__ ( 'Delete All Waiting' ),
            'onclick'   =>"delete_notifications('".Mage::helper("adminhtml")->getUrl('adminhtml/iwd_stocknotification_list/deleteNotNotified')."')",
            'id' => 'delete_not_notified',
            'class'=> '',
        ));
    }

} 