<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml customer grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class FME_Usersurvey_Block_Adminhtml_Questionset_Edit_Tab_Formgrid extends Mage_Adminhtml_Block_Widget_Grid
{
 public function __construct()
  {
      parent::__construct();
      $this->setId('customerGrid');
      $this->setDefaultSort('usersurvey_id');
      $this->setDefaultDir('ASC');
      $this->setUseAjax(true); // Using ajax grid is important   
      $this->setDefaultFilter(array('in_products'=>'')); // By default we have added a filter for the rows, that in_products value to be 1
   
  }
protected function _addColumnFilterToCollection($column)
    {
   
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedQuestions();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('question_ids', array('in'=>$productIds));
            } else {
                if($productIds) {
                    $this->getCollection()->addFieldToFilter('question_ids', array('nin'=>$productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('usersurvey/usersurvey')->getCollection();
      if ($this->isReadonly()) {
		  
		    $productIds = $this->_getSelectedQuestions();
		    if (empty($productIds)) {
			$productIds = array(0);
		    }
		    $collection->addFieldToFilter('question_ids', array('in'=>$productIds));
		}
		
      //$collection->getSelect()->__toString();exit;		
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }


public function isReadonly(){
		
		return 0;
	}
	
  protected function _prepareColumns()
  {
   
   if (!$this->isReadonly()) {
   $this->addColumn('in_products', array(
		   'header_css_class'  => 'a-center',
		   'type'              => 'checkbox',
		    'field_name'       => 'in_products[]',
		   'values'            => $this->_getSelectedQuestions(),
		   'align'             => 'center',
		   'index'             => 'usersurvey_id'               
   ));
   }
  $this->addColumn('usersurvey_id', array(
          'header'    => Mage::helper('usersurvey')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'usersurvey_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('usersurvey')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));



      $this->addColumn('type', array(
          'header'    => Mage::helper('usersurvey')->__('Type'),
          'align'     => 'left',
         'index'     => 'type',
          'type'      => 'options',
          'options'   => array(
              1 => 'Dropdrown',
              2 => 'Gallery',
          ),
      ));
	 
    $this->addColumn('actions', array(
    'header'    => Mage::helper('adminnotification')->__('Actions'),
    'width'     => '250px',
    'filter'    => false,
    'sortable'  => false,
     'renderer'  => new FME_Usersurvey_Block_Adminhtml_Renderer_Setactions(),
    
)); 

      return parent::_prepareColumns();
  }



  public function getRowUrl($row)
  {
      return $this->getUrl('*/adminhtml_usersurvey/edit', array('id' => $row->getId()));
  }

protected function _getSelectedQuestions()   // Used in grid to return selected customers values.
    {      $customers = null;
        $customers = array_values($this->getSelectedQuestions());
	
        return $customers;
    }
 
    public function getSelectedQuestions()
    {
        // Customer Data
       $id = $this->getRequest()->getParam('id');
	$set_id = Mage::helper('usersurvey')->getfieldname('set_qid',$this->getRequest()->getParam('id'),'set_id','questionset');
      	$productsArr = array();
			if($set_id != 0) {
			
				foreach (Mage::registry('current_questions_set')->getRelatedQuestions($set_id) as $products) {
			
				   $productsArr[] = $products;
				}
			}
		
	
		return $productsArr;  
    }
}
