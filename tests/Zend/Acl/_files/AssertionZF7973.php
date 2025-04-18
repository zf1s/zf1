<?php

use Zf1s\Compat\Types;

// require_once 'Zend/Acl/Assert/Interface.php';

class Zend_Acl_AclTest_AssertionZF7973 implements Zend_Acl_Assert_Interface {
    public function assert(Zend_Acl $acl,
                $role = null,
                $resource = null,
                $privilege = null)
    {
        Types::isNullable('role', $role, 'Zend_Acl_Role_Interface');
        Types::isNullable('resource', $resource, 'Zend_Acl_Resource_Interface');

        if($privilege != 'privilege') {
            return false;
        }

        return true;
    }
}
