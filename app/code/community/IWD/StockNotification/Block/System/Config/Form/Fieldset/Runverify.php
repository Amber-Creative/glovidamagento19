<?php
class IWD_StockNotification_Block_System_Config_Form_Fieldset_Runverify extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $archive_title = Mage::helper('stocknotification')->__("Verify All");

        $_secure = Mage::app()->getStore()->isCurrentlySecure();
        $archive_link = Mage::helper("adminhtml")->getUrl('adminhtml/iwd_stocknotification_list/verifyManually', array('_secure' => $_secure));

        return '<button style="margin-right:120px;" type="button" onclick="setLocation(\''.$archive_link.'\')">'.$archive_title.'</button>';
    }
}