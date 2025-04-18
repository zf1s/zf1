<?php

use Zf1s\Compat\Types;

class Zend_Acl_ExtendedAclZF2234 extends Zend_Acl
{
    public function roleDFSVisitAllPrivileges(Zend_Acl_Role_Interface $role, $resource = null,
                                              &$dfs = null)
    {
        Types::isNullable('resource', $resource, 'Zend_Acl_Resource_Interface');

        return $this->_roleDFSVisitAllPrivileges($role, $resource, $dfs);
    }

    public function roleDFSOnePrivilege(Zend_Acl_Role_Interface $role, $resource = null,
                                        $privilege = null)
    {
        Types::isNullable('resource', $resource, 'Zend_Acl_Resource_Interface');

        return $this->_roleDFSOnePrivilege($role, $resource, $privilege);
    }

    public function roleDFSVisitOnePrivilege(Zend_Acl_Role_Interface $role, $resource = null,
                                             $privilege = null, &$dfs = null)
    {
        Types::isNullable('resource', $resource, 'Zend_Acl_Resource_Interface');

        return $this->_roleDFSVisitOnePrivilege($role, $resource, $privilege, $dfs);
    }
}