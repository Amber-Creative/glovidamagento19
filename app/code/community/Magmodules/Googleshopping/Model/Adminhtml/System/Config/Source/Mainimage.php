<?php
/**
 * Magmodules.eu - http://www.magmodules.eu.
 *
 * NOTICE OF LICENSE
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.magmodules.eu/MM-LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category      Magmodules
 * @package       Magmodules_Googleshopping
 * @author        Magmodules <info@magmodules.eu>
 * @copyright     Copyright (c) 2017 (http://www.magmodules.eu)
 * @license       https://www.magmodules.eu/terms.html  Single Service License
 */

class Magmodules_Googleshopping_Model_Adminhtml_System_Config_Source_Mainimage
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addFieldToFilter('frontend_input', 'media_image');

        $type = array();
        $type[] = array('value' => '', 'label' => Mage::helper('googleshopping')->__('Use default'));
        foreach ($attributes as $attribute) {
            $type[] = array(
                'value' => $attribute->getData('attribute_code'),
                'label' => str_replace("'", "", $attribute->getData('frontend_label'))
            );
        }

        $type[] = array('value' => 'first', 'label' => Mage::helper('googleshopping')->__('First Image'));
        $type[] = array('value' => 'last', 'label' => Mage::helper('googleshopping')->__('Last Image'));
        return $type;
    }

}