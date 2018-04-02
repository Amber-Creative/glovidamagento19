<?php

class FME_Usersurvey_Block_Adminhtml_Questionset_Edit_Tab_Questions extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('usersurveyGrid');
      $this->setDefaultSort('question_id');
      $this->setDefaultDir('ASC');
      $this->setUseAjax(true);
      $this->setDefaultFilter(array('in_set'=>1));
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('usersurvey/usersurvey')->getCollection();
      $_conn = Mage::getSingleton('core/resource');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }



  /**
     * Add filter
     **/
    protected function _addColumnFilterToCollection($column)
    {
        
     
        if ($column->getId() == 'in_set') {
           
            $questionIds = $this->getSelectedQuestions();
            
            if (empty($questionIds)) {

                $questionIds = 0;
            }
            if ($column->getFilter()->getValue()) {

                $this->getCollection()->addFieldToFilter('question_id', array('in'=>$questionIds));
                
            } else {
                if($questionIds) {
                    $this->getCollection()->addFieldToFilter('question_id', array('nin'=>$questionIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

  protected function _prepareColumns()
  {
      $this->addColumn('in_set', array(
                'type'              => 'checkbox',
                'name'              => 'question_ids',
                'values'            => $this->getSelectedQuestions(),
                'align'             => 'center',
                'index'             => 'question_id',
                
            ));
      $this->addColumn('question_id', array(
          'header'    => Mage::helper('usersurvey')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'question_id',
      ));

      $this->addColumn('qtitle', array(
          'header'    => Mage::helper('usersurvey')->__('Title'),
          'align'     =>'left',
          'name'      => 'qtitle_ids',
          'index'     => 'qtitle',
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
	  
      return parent::_prepareColumns();
  }

  /**
     * Rerieve grid URL
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/questionsGrid', array('_current'=>true));
    }


    /**
     * Retrieve selected related products
     */
    public function getSelectedQuestions()
    {

       
            $collection = Mage::getModel('usersurvey/questionset')->getCollection();
            $collection->addFieldToFilter('set_id',$this->getRequest()->getParam('id'));
            $question_ids = array();
            foreach ($collection as  $value) {
              $question_ids[] = $value['question_ids'];
            }
			$q_ids = array();
            foreach ($question_ids as $key) {
              $q_ids[] =  explode(",", $key);
            }
            if(!empty($q_ids))
				return $q_ids[0];
			else
				return $q_ids;
    }



}
