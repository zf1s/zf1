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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Db/Table/Select/TestCommon.php';





/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Db
 * @group      Zend_Db_Table
 * @group      Zend_Db_Table_Select
 */
class Zend_Db_Table_Select_Pdo_SqliteTest extends Zend_Db_Table_Select_TestCommon
{

    public function testSelectFromQualified()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support qualified table names');
    }

    public function testSelectJoinQualified()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support qualified table names');
    }

    public function testSelectFromForUpdate()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support FOR UPDATE');
    }

    public function testSelectJoinRight()
    {
        $this->markTestSkipped($this->getDriver() . ' does not support RIGHT OUTER JOIN');
    }

    public function getDriver()
    {
        return 'Pdo_Sqlite';
    }

    public function testSqlInjectionWithOrder()
    {
        $select = $this->_db->select();
        $select->from(['p' => 'products'])->order('MD5(1);select');
        $this->assertEquals('SELECT "p".* FROM "products" AS "p" ORDER BY "MD5(1);select" ASC', $select->assemble());

        $select = $this->_db->select();
        $select->from(['p' => 'products'])->order('name;select;MD5(1)');
        $this->assertEquals('SELECT "p".* FROM "products" AS "p" ORDER BY "name;select;MD5(1)" ASC', $select->assemble());
    }

    /**
     * @group ZF-378
     */
    public function testOrderOfSingleFieldWithDirection()
    {
        $select = $this->_db->select();
        $select->from( ['p' => 'product'])
            ->order('productId DESC');

        $expected = 'SELECT "p".* FROM "product" AS "p" ORDER BY "productId" DESC';
        $this->assertEquals($expected, $select->assemble(),
            'Order direction of field failed');
    }

    /**
     * @group ZF-378
     */
    public function testOrderOfMultiFieldWithDirection()
    {
        $select = $this->_db->select();
        $select->from( ['p' => 'product'])
            ->order( ['productId DESC', 'userId ASC']);

        $expected = 'SELECT "p".* FROM "product" AS "p" ORDER BY "productId" DESC, "userId" ASC';
        $this->assertEquals($expected, $select->assemble(),
            'Order direction of field failed');
    }

    /**
     * @group ZF-378
     */
    public function testOrderOfMultiFieldButOnlyOneWithDirection()
    {
        $select = $this->_db->select();
        $select->from( ['p' => 'product'])
            ->order( ['productId', 'userId DESC']);

        $expected = 'SELECT "p".* FROM "product" AS "p" ORDER BY "productId" ASC, "userId" DESC';
        $this->assertEquals($expected, $select->assemble(),
            'Order direction of field failed');
    }

    /**
     * @group ZF-378
     * @group ZF-381
     */
    public function testOrderOfConditionalFieldWithDirection()
    {
        $select = $this->_db->select();
        $select->from( ['p' => 'product'])
            ->order('IF("productId" > 5,1,0) ASC');

        $expected = 'SELECT "p".* FROM "product" AS "p" ORDER BY IF("productId" > 5,1,0) ASC';
        $this->assertEquals($expected, $select->assemble(),
            'Order direction of field failed');
    }

}
