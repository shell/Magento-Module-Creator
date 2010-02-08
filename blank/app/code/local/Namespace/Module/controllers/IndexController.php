<?php
class <Namespace>_<Module>_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/<module>?id=15 
    	 *  or
    	 * http://site.com/<module>/id/15 	
    	 */
    	/* 
		$<module>_id = $this->getRequest()->getParam('id');

  		if($<module>_id != null && $<module>_id != '')	{
			$<module> = Mage::getModel('<module>/<module>')->load($<module>_id)->getData();
		} else {
			$<module> = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($<module> == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$<module>Table = $resource->getTableName('<module>');
			
			$select = $read->select()
			   ->from($<module>Table,array('<module>_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$<module> = $read->fetchRow($select);
		}
		Mage::register('<module>', $<module>);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}