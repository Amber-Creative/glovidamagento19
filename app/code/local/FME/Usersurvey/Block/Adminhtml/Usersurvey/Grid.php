<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('usersurveyGrid');
      $this->setDefaultSort('question_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('usersurvey/usersurvey')->getCollection();
      $_conn = Mage::getSingleton('core/resource');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('question_id', array(
          'header'    => Mage::helper('usersurvey')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'question_id',
      ));

      $this->addColumn('qtitle', array(
          'header'    => Mage::helper('usersurvey')->__('Title'),
          'align'     =>'left',
          'index'     => 'qtitle',
      ));

	
      $this->addColumn('Sets', array(
			'header'    => Mage::helper('usersurvey')->__('Sets'),
			'index'     => 'question_id',
			'align'     =>'left',
			'filter'    => false,
      'renderer'  => new FME_Usersurvey_Block_Adminhtml_Renderer_Sets(),

      ));
	 

      $this->addColumn('type', array(
          'header'    => Mage::helper('usersurvey')->__('Type'),
          'align'     => 'left',
          'width'     => '80px',
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
     'renderer'  => new FME_Usersurvey_Block_Adminhtml_Renderer_Actions(),
    
  ));  
		
	  
      return parent::_prepareColumns();
  }

   

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
