<?php
class IWD_StockNotification_Model_System_Config_Backend_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/iwd_verify_out_of_stock/schedule/cron_expr';

    protected function _afterSave()
    {
        $enabled  = $this->getData('groups/default/fields/auto_verify_enable/value');
        $time     = $this->getData('groups/default/fields/auto_verify_start_time/value');
        $frequncy = $this->getData('groups/default/fields/auto_verify_frequency/value');

        $frequencyWeekly    = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;
        $frequencyMonthly   = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY;

        if ($enabled) {
            $cronExprArray = array(
                intval($time[1]),                                   # Minute
                intval($time[0]),                                   # Hour
                ($frequncy == $frequencyMonthly) ? '1' : '*',       # Day of the Month
                '*',                                                # Month of the Year
                ($frequncy == $frequencyWeekly) ? '1' : '*',        # Day of the Week
            );
            $cronExprString = join(' ', $cronExprArray);
        }
        else {
            $cronExprString = '';
        }

        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();
        }
        catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'iwd_sn.log');
            Mage::throwException(Mage::helper('adminhtml')->__('Unable to save the cron expression.'));
        }
    }
}