<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'usersurvey';
        $this->_controller = 'adminhtml_usersurvey';
        
	 $this->removeButton('reset');
	 if( Mage::registry('usersurvey_data') && Mage::registry('usersurvey_data')->getId() ) {
	 $this->_addButton('addnew', array(
            'label'     => Mage::helper('adminhtml')->__('Add New Answer'),
            'onclick'   => 'setLocation(\'' .$this->getUrl('*/*/addanswer', array('id' => $this->getRequest()->getParam('id'))).'\')',
            'class'     => 'add',
        ), -100);
	 }
        $this->_updateButton('save', 'label', Mage::helper('usersurvey')->__('Save Question'));
       // $this->_updateButton('delete', 'label', Mage::helper('usersurvey')->__('Delete Question'));
	    $this->removeButton('delete');	
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
            return Mage::helper('usersurvey')->__("Edit Question '%s'", $this->htmlEscape(Mage::registry('usersurvey_data')->getTitle()));
        } else {
            return Mage::helper('usersurvey')->__('Add Question');
        }
    }
}