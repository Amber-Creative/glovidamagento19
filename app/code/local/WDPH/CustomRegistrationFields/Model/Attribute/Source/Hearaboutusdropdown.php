<?php
class WDPH_CustomRegistrationFields_Model_Attribute_Source_Hearaboutusdropdown extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
    protected $_options = null;
    public function getAllOptions($withEmpty = false){
        if (is_null($this->_options)){
            $this->_options = array();

            $this->_options[] = array('label'=> 'Word of Mouth', value=>1);
            $this->_options[] = array('label'=> 'Google Search', value=>2);
            $this->_options[] = array('label'=> 'Facebook', value=>3);
			$this->_options[] = array('label'=> 'Instagram', value=>4);
			$this->_options[] = array('label'=> 'Others, please state:', value=>5);
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array('value'=>'', 'label'=>''));
        }
        return $options;
    }
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);

        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }
}