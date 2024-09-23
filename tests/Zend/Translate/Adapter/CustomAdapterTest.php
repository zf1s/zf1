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
 * @package    Zend_Translate
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Translate_Adapter_CustomAdapterTest::main');
}

/**
 * @category   Zend
 * @package    Zend_Translate
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Translate
 */
class Zend_Translate_Adapter_CustomAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $includePath;

    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Translate_Adapter_CustomAdapterTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        // Store original include_path
        $this->includePath = get_include_path();
    }

    public function tearDown()
    {
        // Restore original include_path
        set_include_path($this->includePath);

        // Reset autoloader instance so it doesn't affect other tests
        Zend_Loader_Autoloader::resetInstance();
    }

    public function testCreate()
    {
        $this->addTestIncludePath();

        $translate = new Zend_Translate('My_CustomAdapter', ['test' => 'translated'], 'en');
        $this->assertTrue(true);
    }

    public function testCreateWithZendAutoloaderEnabled()
    {
        $this->addTestIncludePath();

        // register zend autoloader
        Zend_Loader_Autoloader::getInstance();

        $translate = new Zend_Translate('My_CustomAdapter', ['test' => 'translated'], 'en');
        $this->assertTrue(true);
    }

    public function addTestIncludePath()
    {
        set_include_path(__DIR__ . '/_files/' . PATH_SEPARATOR . $this->includePath);
    }

}

// Call Zend_Translate_Adapter_CustomAdapterTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "Zend_Translate_Adapter_CustomAdapterTest::main") {
    Zend_Translate_Adapter_CustomAdapterTest::main();
}
