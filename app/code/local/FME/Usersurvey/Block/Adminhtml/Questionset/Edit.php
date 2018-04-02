<?php

class FME_Usersurvey_Block_Adminhtml_Questionset_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'usersurvey';
        $this->_controller = 'adminhtml_questionset';
        
        $this->_updateButton('save', 'label', Mage::helper('usersurvey')->__('Save Question Set'));
        $this->_updateButton('delete', 'label', Mage::helper('usersurvey')->__('Delete Question Set'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('questionset_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'questionset_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'questionset_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('questionset_data') && Mage::registry('questionset_data')->getId() ) {
            return Mage::helper('usersurvey')->__("Edit Question Set '%s'", $this->htmlEscape(Mage::registry('questionset_data')->getTitle()));
        } else {
            return Mage::helper('usersurvey')->__('Add Question Set');
        }
    }
}