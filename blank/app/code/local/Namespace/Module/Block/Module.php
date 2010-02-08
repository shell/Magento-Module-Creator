<?php
class <Namespace>_<Module>_Block_<Module> extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function get<Module>()     
     { 
        if (!$this->hasData('<module>')) {
            $this->setData('<module>', Mage::registry('<module>'));
        }
        return $this->getData('<module>');
        
    }
}