<?php
// require_once 'Zend/Barcode/Object/Error.php';

#[AllowDynamicProperties]
class My_Namespace_Error extends Zend_Barcode_Object_Error
{

    public function getType()
    {
        return $this->_type;
    }
}
