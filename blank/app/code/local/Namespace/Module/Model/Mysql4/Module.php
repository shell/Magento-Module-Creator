<?php

class <Namespace>_<Module>_Model_Mysql4_<Module> extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the <module>_id refers to the key field in your database table.
        $this->_init('<module>/<module>', '<module>_id');
    }
}