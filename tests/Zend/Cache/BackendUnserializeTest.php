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
 * @package    Zend_Cache
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Backends unserialize their own data structures without creating objects from stored data
 *
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage UnitTests
 * @group      Zend_Cache
 */
class Zend_Cache_BackendUnserializeTest extends PHPUnit_Framework_TestCase
{
    protected $_dirs = array();

    public function setUp()
    {
        Zend_Cache_BackendUnserializeTest_WakeUp::$wokenUp = false;
    }

    public function tearDown()
    {
        foreach ($this->_dirs as $dir) {
            array_map('unlink', glob($dir . '*'));
            rmdir($dir);
        }
    }

    protected function _mkdir()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'zend_cache_unserialize_' . uniqid() . DIRECTORY_SEPARATOR;
        mkdir($dir);
        return $this->_dirs[] = $dir;
    }

    public function testFileBackendMetadatasCreateNoObjects()
    {
        $dir = $this->_mkdir();
        $backend = new Zend_Cache_Backend_File(array('cache_dir' => $dir));
        $this->assertTrue($backend->save('data', 'foo'));
        $metadatasFiles = glob($dir . '*internal-metadatas---foo');
        file_put_contents($metadatasFiles[0], serialize(new Zend_Cache_BackendUnserializeTest_WakeUp()));

        $backend = new Zend_Cache_Backend_File(array('cache_dir' => $dir));
        $this->assertFalse($backend->load('foo'));
        $this->assertFalse(Zend_Cache_BackendUnserializeTest_WakeUp::$wokenUp);
    }

    public function testTwoLevelsBackendCreatesNoObjects()
    {
        $fastDir = $this->_mkdir();
        $backend = new Zend_Cache_Backend_TwoLevels(array(
            'slow_backend'         => 'File',
            'slow_backend_options' => array('cache_dir' => $this->_mkdir()),
            'fast_backend'         => 'File',
            'fast_backend_options' => array('cache_dir' => $fastDir, 'read_control' => false),
        ));
        $this->assertTrue($backend->save('data', 'foo'));
        $this->assertSame('data', $backend->load('foo'));
        file_put_contents($fastDir . 'zend_cache---foo', serialize(new Zend_Cache_BackendUnserializeTest_WakeUp()));

        $this->assertFalse($backend->load('foo'));
        $this->assertFalse(Zend_Cache_BackendUnserializeTest_WakeUp::$wokenUp);
    }
}

class Zend_Cache_BackendUnserializeTest_WakeUp
{
    public static $wokenUp = false;

    public function __wakeup()
    {
        self::$wokenUp = true;
    }
}
