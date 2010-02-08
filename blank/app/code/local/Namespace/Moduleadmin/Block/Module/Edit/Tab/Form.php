<?php

class <Namespace>_<Moduleadmin>_Block_<Module>_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('<module>_form', array('legend'=>Mage::helper('<module>')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('<module>')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('<module>')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('<module>')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('<module>')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('<module>')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('<module>')->__('Content'),
          'title'     => Mage::helper('<module>')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->get<Module>Data() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->get<Module>Data());
          Mage::getSingleton('adminhtml/session')->set<Module>Data(null);
      } elseif ( Mage::registry('<module>_data') ) {
          $form->setValues(Mage::registry('<module>_data')->getData());
      }
      return parent::_prepareForm();
  }
}