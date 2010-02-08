<?php
class <Namespace>_<Moduleadmin>_Block_Adminhtml_<Module> extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = '<moduleadmin>';
    $this->_blockGroup = '<moduleadmin>';
    $this->_headerText = Mage::helper('<moduleadmin>')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('<moduleadmin>')->__('Add Item');
    parent::__construct();
  }
}
