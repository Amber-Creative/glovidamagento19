<?php

class FME_Usersurvey_Block_Adminhtml_Renderer_Setactions
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $readDetailsHtml = (!$row->getIsRead())
            ? '<a href="'. $this->getUrl('*/adminhtml_usersurvey/view', array('_current' => true, 'id' => $row->getId())) .'">' .
                Mage::helper('adminnotification')->__('View') .'</a> | '
            : '';

        $markAsReadHtml = (!$row->getIsRead())
            ? '<a href="'. $this->getUrl('*/adminhtml_usersurvey/edit', array('_current' => true, 'id' => $row->getId())) .'">' .
                Mage::helper('adminnotification')->__('Edit') .'</a> | '
            : '';

        return sprintf('%s%s<a href="%s" onClick="deleteConfirm(\'%s\', this.href); return false;">%s</a>',
            $readDetailsHtml,
            $markAsReadHtml,
            $this->getUrl('/adminhtml_usersurvey/delete', array(
                '_current'=>true,
                'id' => $row->getId(),
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl())
            ),
            Mage::helper('adminnotification')->__('Are you sure?'),
            Mage::helper('adminnotification')->__('Remove')
        );
    }
}
