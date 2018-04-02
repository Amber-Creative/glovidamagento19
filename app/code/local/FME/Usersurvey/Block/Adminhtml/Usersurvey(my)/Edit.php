<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'usersurvey';
        $this->_controller = 'adminhtml_usersurvey';
        
        $this->_updateButton('save', 'label', Mage::helper('usersurvey')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('usersurvey')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('usersurvey_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'usersurvey_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'usersurvey_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('usersurvey_data') && Mage::registry('usersurvey_data')->getId() ) {
            return Mage::helper('usersurvey')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('usersurvey_data')->getTitle()));
        } else {
            return Mage::helper('usersurvey')->__('Add Item');
        }
    }
}