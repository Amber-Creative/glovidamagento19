<?php
class FME_Usersurvey_Block_Adminhtml_Renderer_Votes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row) {
      
                  if ($row->getData('votes') != NULL ) {
			$items = $row->getData('votes');
	                                    	
				return $items." votes";
			
	        } 
    }


}