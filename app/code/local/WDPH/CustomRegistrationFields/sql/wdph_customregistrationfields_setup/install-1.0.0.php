<?php
$this->addAttribute('customer', 'hearaboutusdropdown', array(
    'type'      => 'int',
    'label'     => 'How did you hear about us',
    'input'     => 'select',
    'position'  => 2000,
    'required'  => false,//or true	
    'is_system' => 0,
	'source' => 'WDPH_CustomRegistrationFields_Model_Attribute_Source_Hearaboutusdropdown',
));
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'hearaboutusdropdown');
$attribute->setData('used_in_forms', array(
    'adminhtml_customer',
    'checkout_register',
    'customer_account_create',
    'customer_account_edit',
));
$attribute->setData('is_user_defined', 0);
$attribute->save();
$this->addAttribute('customer', 'hearaboutustext', array(
    'type'      => 'varchar',
    'label'     => 'How did you hear about us, others',
    'input'     => 'text',
    'position'  => 2010,
    'required'  => false,//or true
    'is_system' => 0,
));
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'hearaboutustext');
$attribute->setData('used_in_forms', array(
    'adminhtml_customer',
    'checkout_register',
    'customer_account_create',
    'customer_account_edit',
));
$attribute->setData('is_user_defined', 0);
$attribute->save();
?>