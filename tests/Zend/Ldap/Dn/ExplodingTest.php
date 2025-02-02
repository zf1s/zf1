<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Ldap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Zend_Ldap_Dn
 */
// require_once 'Zend/Ldap/Dn.php';

/**
 * @category   Zend
 * @package    Zend_Ldap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Ldap
 * @group      Zend_Ldap_Dn
 */
class Zend_Ldap_Dn_ExplodingTest extends PHPUnit_Framework_TestCase
{
    public static function explodeDnOperationProvider()
    {
        $testData = [
            ['CN=Alice Baker,CN=Users,DC=example,DC=com', true],
            ['CN=Baker\\, Alice,CN=Users,DC=example,DC=com', true],
            ['OU=Sales,DC=local', true],
            ['OU=Sales;DC=local', true],
            ['OU=Sales ,DC=local', true],
            ['OU=Sales, dC=local', true],
            ['ou=Sales , DC=local', true],
            ['OU=Sales ; dc=local', true],
            ['DC=local', true],
            [' DC=local', true],
            ['DC= local  ', true],
            ['username', false],
            ['username@example.com', false],
            ['EXAMPLE\\username', false],
            ['CN=,Alice Baker,CN=Users,DC=example,DC=com', false],
            ['CN=Users,DC==example,DC=com', false],
            ['O=ACME', true],
            ['', false],
            ['   ', false],
            ['uid=rogasawara,ou=営業部,o=Airius', true],
            ['cn=Barbara Jensen, ou=Product Development, dc=airius, dc=com', true],
            ['CN=Steve Kille,O=Isode Limited,C=GB', true],
            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US', true],
            ['CN=L. Eagle,O=Sue\, Grabbit and Runn,C=GB', true],
            ['CN=Before\0DAfter,O=Test,C=GB', true],
            ['SN=Lu\C4\8Di\C4\87', true],
            ['OU=Sales+,O=Widget Inc.,C=US', false],
            ['+OU=Sales,O=Widget Inc.,C=US', false],
            ['OU=Sa+les,O=Widget Inc.,C=US', false],
        ];
        return $testData;
    }

    /**
     * @dataProvider explodeDnOperationProvider
     */
    public function testExplodeDnOperation($input, $expected)
    {
        $ret = Zend_Ldap_Dn::checkDn($input);
        $this->assertTrue($ret === $expected);
    }

    public function testExplodeDnCaseFold()
    {
        $dn='CN=Alice Baker,cn=Users,DC=example,dc=com';
        $k=[];
        $v=null;
        $this->assertTrue(Zend_Ldap_Dn::checkDn($dn, $k, $v, Zend_Ldap_Dn::ATTR_CASEFOLD_NONE));
        $this->assertEquals(['CN', 'cn', 'DC', 'dc'], $k);

        $this->assertTrue(Zend_Ldap_Dn::checkDn($dn, $k, $v, Zend_Ldap_Dn::ATTR_CASEFOLD_LOWER));
        $this->assertEquals(['cn', 'cn', 'dc', 'dc'], $k);

        $this->assertTrue(Zend_Ldap_Dn::checkDn($dn, $k, $v, Zend_Ldap_Dn::ATTR_CASEFOLD_UPPER));
        $this->assertEquals(['CN', 'CN', 'DC', 'DC'], $k);
    }

    public function testExplodeDn()
    {
        $dn='cn=name1,cn=name2,dc=example,dc=org';
        $k=[];
        $v=[];
        $dnArray=Zend_Ldap_Dn::explodeDn($dn, $k, $v);
        $expected=[
            ["cn" => "name1"],
            ["cn" => "name2"],
            ["dc" => "example"],
            ["dc" => "org"]
        ];
        $ke=['cn', 'cn', 'dc', 'dc'];
        $ve=['name1', 'name2', 'example', 'org'];
        $this->assertEquals($expected, $dnArray);
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);
    }

    public function testExplodeDnWithUtf8Characters()
    {
        $dn='uid=rogasawara,ou=営業部,o=Airius';
        $k=[];
        $v=[];
        $dnArray=Zend_Ldap_Dn::explodeDn($dn, $k, $v);
        $expected=[
            ["uid" => "rogasawara"],
            ["ou" => "営業部"],
            ["o" => "Airius"],
        ];
        $ke=['uid', 'ou', 'o'];
        $ve=['rogasawara', '営業部', 'Airius'];
        $this->assertEquals($expected, $dnArray);
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);
    }

    public function testExplodeDnWithSpaces()
    {
        $dn='cn=Barbara Jensen, ou=Product Development, dc=airius, dc=com';
        $k=[];
        $v=[];
        $dnArray=Zend_Ldap_Dn::explodeDn($dn, $k, $v);
        $expected=[
            ["cn" => "Barbara Jensen"],
            ["ou" => "Product Development"],
            ["dc" => "airius"],
            ["dc" => "com"],
        ];
        $ke=['cn', 'ou', 'dc', 'dc'];
        $ve=['Barbara Jensen', 'Product Development', 'airius', 'com'];
        $this->assertEquals($expected, $dnArray);
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);
    }

    public function testCoreExplodeDnWithMultiValuedRdn()
    {
        $dn='cn=name1+uid=user,cn=name2,dc=example,dc=org';
        $k=[];
        $v=[];
        $this->assertTrue(Zend_Ldap_Dn::checkDn($dn, $k, $v));
        $ke=[['cn', 'uid'], 'cn', 'dc', 'dc'];
        $ve=[['name1', 'user'], 'name2', 'example', 'org'];
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);

        $dn='cn=name11+cn=name12,cn=name2,dc=example,dc=org';
        $this->assertFalse(Zend_Ldap_Dn::checkDn($dn));

        $dn='CN=name11+Cn=name12,cn=name2,dc=example,dc=org';
        $this->assertFalse(Zend_Ldap_Dn::checkDn($dn));
    }

    public function testExplodeDnWithMultiValuedRdn()
    {
        $dn='cn=Surname\, Firstname+uid=userid,cn=name2,dc=example,dc=org';
        $k=[];
        $v=[];
        $dnArray=Zend_Ldap_Dn::explodeDn($dn, $k, $v);
        $ke=[['cn', 'uid'], 'cn', 'dc', 'dc'];
        $ve=[['Surname, Firstname', 'userid'], 'name2', 'example', 'org'];
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);
        $expected=[
            ["cn" => "Surname, Firstname", "uid" => "userid"],
            ["cn" => "name2"],
            ["dc" => "example"],
            ["dc" => "org"]
        ];
        $this->assertEquals($expected, $dnArray);
    }

    public function testExplodeDnWithMultiValuedRdn2()
    {
        $dn='cn=Surname\, Firstname+uid=userid+sn=Surname,cn=name2,dc=example,dc=org';
        $k=[];
        $v=[];
        $dnArray=Zend_Ldap_Dn::explodeDn($dn, $k, $v);
        $ke=[['cn', 'uid', 'sn'], 'cn', 'dc', 'dc'];
        $ve=[['Surname, Firstname', 'userid', 'Surname'], 'name2', 'example', 'org'];
        $this->assertEquals($ke, $k);
        $this->assertEquals($ve, $v);
        $expected=[
            ["cn" => "Surname, Firstname", "uid" => "userid", "sn" => "Surname"],
            ["cn" => "name2"],
            ["dc" => "example"],
            ["dc" => "org"]
        ];
        $this->assertEquals($expected, $dnArray);
    }

    /**
     * @expectedException Zend_Ldap_Exception
     */
    public function testCreateDnArrayIllegalDn()
    {
        $dn='name1,cn=name2,dc=example,dc=org';
        $dnArray=Zend_Ldap_Dn::explodeDn($dn);
    }

    public static function rfc2253DnProvider()
    {
        $testData = [
            ['CN=Steve Kille,O=Isode Limited,C=GB',
                [
                    ['CN' => 'Steve Kille'],
                    ['O'  => 'Isode Limited'],
                    ['C'  => 'GB']
                ]],
            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US',
                [
                    ['OU' => 'Sales', 'CN' => 'J. Smith'],
                    ['O'  => 'Widget Inc.'],
                    ['C'  => 'US']
                ]],
            ['CN=L. Eagle,O=Sue\, Grabbit and Runn,C=GB',
                [
                    ['CN' => 'L. Eagle'],
                    ['O'  => 'Sue, Grabbit and Runn'],
                    ['C'  => 'GB']
                ]],
            ['CN=Before\0DAfter,O=Test,C=GB',
                [
                    ['CN' => "Before\rAfter"],
                    ['O'  => 'Test'],
                    ['C'  => 'GB']
                ]],
            ['SN=Lu\C4\8Di\C4\87',
                [
                    ['SN' => 'Lučić']
                ]]
        ];
        return $testData;
    }

    /**
     * @dataProvider rfc2253DnProvider
     */
    public function testExplodeDnsProvidedByRFC2253($input, $expected)
    {
        $dnArray=Zend_Ldap_Dn::explodeDn($input);
        $this->assertEquals($expected, $dnArray);
    }
}
