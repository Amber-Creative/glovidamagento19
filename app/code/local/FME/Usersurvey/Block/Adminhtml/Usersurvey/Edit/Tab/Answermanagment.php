<?php

class FME_Usersurvey_Block_Adminhtml_Usersurvey_Edit_Tab_Answermanagment extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('answer_id');
      $this->setDefaultSort($this->getRequest()->getParam('id'));
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
      Mage::getSingleton('core/session')->setMyValue($this->getRequest()->getParam('id'));
  }

  protected function _prepareCollection()
  {
      $_conn = Mage::getSingleton('core/resource');
      $question_table = $_conn->getTableName('fme_usersurvey');
      $collection = Mage::getModel('usersurvey/answers')->getCollection();
      $collection->getSelect()->join(array('questions' => $question_table),'main_table.question_id = questions.question_id',array('questions.type','questions.question_id')); 
      $collection->addFieldToFilter('main_table.question_id',$this->getRequest()->getParam('id'));
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
  

    if( Mage::helper('usersurvey')->getfield('type',$this->getRequest()->getParam('id')) == 1)
    {
          $this->addColumn('answer_type', array(
              'header'    => Mage::helper('usersurvey')->__('Answer Title'),
              'align'     =>'left',
              'index'     => 'answer_type',
          ));
    }else{
      $this->addColumn('answer_type', array(
              'header'    => Mage::helper('usersurvey')->__('Answer Image'),
              'align'     =>'left',
    	         'filter'    => false,
              'index'     => 'answer_type',
    	        'renderer'  => 'usersurvey/adminhtml_renderer_image',
              'align' => 'center',
              'width'     => '200px',
          ));
    }
    	
    $this->addColumn('description', array(
    			'header'    => Mage::helper('usersurvey')->__('Answer Description'),
    			'index'     => 'description',
          ));
    	 
    $this->addColumn('sort_order', array(
    			'header'    => Mage::helper('usersurvey')->__('Sort Order'),
    			'width'     => '100px',
    			'filter'    => false,
    			'align'     =>'center',
    			'index'     => 'sort_order',
          ));
    $this->addColumn('votes', array(
    			'header'    => Mage::helper('usersurvey')->__('Results'),
    			'width'     => '100px',
    			'align'     =>'center',
    			'filter'    => false,
    			'index'     => 'votes',
          'renderer'  =>  new FME_Usersurvey_Block_Adminhtml_Renderer_Votes()
    			
          ));
        
    	  
    $this->addColumn('action',
      array(
        'header'    =>  Mage::helper('usersurvey')->__('Action'),
        'width'     => '100',
        'type'      => 'action',
        'align'     =>'center',
        'getter'    => 'getAnswerId',
        'actions'   => array(
          array(
            'caption'   => Mage::helper('usersurvey')->__('Delete'),
            'url'       => array('base'=> '*/*/deleteanswer'),
            'confirm'  => Mage::helper('usersurvey')->__('Are you sure?'),
            'class'     => 'addm',
            'field'  =>'id'
            )
          ),
        'filter'    => false,
        'sortable'  => false,
        'index'     => 'store',
        'is_system' => true,
         ));
    	
    	      
    	  
          return parent::_prepareColumns();
  }


 

    
  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/editanswer', array('id' => $row->getAnswerId()));
  }

}