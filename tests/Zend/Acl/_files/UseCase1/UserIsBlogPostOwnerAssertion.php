<?php

use Zf1s\Compat\Types;

class Zend_Acl_UseCase1_UserIsBlogPostOwnerAssertion implements Zend_Acl_Assert_Interface
{

    public $lastAssertRole = null;
    public $lastAssertResource = null;
    public $lastAssertPrivilege = null;
    public $assertReturnValue = true;

    public function assert(Zend_Acl $acl, $user = null, $blogPost = null, $privilege = null)
    {
        Types::isNullable('user', $user, 'Zend_Acl_Role_Interface');
        Types::isNullable('blogPost', $blogPost, 'Zend_Acl_Resource_Interface');

        $this->lastAssertRole = $user;
        $this->lastAssertResource = $blogPost;
        $this->lastAssertPrivilege = $privilege;
        return $this->assertReturnValue;
    }
}