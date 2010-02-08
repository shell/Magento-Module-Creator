<?php

class <Namespace>_<Moduleadmin>_Block_<Module>_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = '<moduleadmin>';
        $this->_controller = '<moduleadmin>';
        
        $this->_updateButton('save', 'label', Mage::helper('<module>')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('<module>')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('<module>_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, '<module>_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, '<module>_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('<module>_data') && Mage::registry('<module>_data')->getId() ) {
            return Mage::helper('<module>')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('<module>_data')->getTitle()));
        } else {
            return Mage::helper('<module>')->__('Add Item');
        }
    }
}
