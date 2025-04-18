<?php

use Zf1s\Compat\Types;

class Zend_Acl_MockAssertion implements Zend_Acl_Assert_Interface
{
    protected $_returnValue;

    public function __construct($returnValue)
    {
        $this->_returnValue = (bool) $returnValue;
    }

    public function assert(Zend_Acl $acl, $role = null, $resource = null,
                           $privilege = null)
    {
        Types::isNullable('role', $role, 'Zend_Acl_Role_Interface');
        Types::isNullable('resource', $resource, 'Zend_Acl_Resource_Interface');

       return $this->_returnValue;
    }
}