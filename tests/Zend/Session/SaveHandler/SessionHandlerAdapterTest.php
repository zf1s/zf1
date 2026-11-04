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
 * @package    Zend_Session
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @category   Zend
 * @package    Zend_Session
 * @subpackage UnitTests
 * @group      Zend_Session
 */
class Zend_Session_SaveHandler_SessionHandlerAdapterTest extends PHPUnit_Framework_TestCase
{
    public function testCallsAndReturnValuesArePassedThrough()
    {
        $handler = new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler();
        $handler->data['abc'] = 'foo|s:3:"bar";';
        $adapter = new Zend_Session_SaveHandler_SessionHandlerAdapter($handler);

        $this->assertSame($handler, $adapter->getSaveHandler());
        $this->assertSame(true, $adapter->open('/save/path', 'PHPSESSID'));
        $this->assertSame('foo|s:3:"bar";', $adapter->read('abc'));
        $this->assertSame('', $adapter->read('def'));
        $this->assertSame(true, $adapter->write('def', 'baz|i:1;'));
        $this->assertSame(true, $adapter->destroy('abc'));
        $this->assertSame(0, $adapter->gc(1440));
        $this->assertSame(true, $adapter->close());

        $this->assertSame(array(
            array('open', array('/save/path', 'PHPSESSID')),
            array('read', array('abc')),
            array('read', array('def')),
            array('write', array('def', 'baz|i:1;')),
            array('destroy', array('abc')),
            array('gc', array(1440)),
            array('close', array()),
        ), $handler->calls);
        $this->assertSame(array('def' => 'baz|i:1;'), $handler->data);
    }

    public function testCreateSidUsesDefaultSessionIdGenerator()
    {
        $adapter = new Zend_Session_SaveHandler_SessionHandlerAdapter(
            new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler()
        );

        $id = $adapter->create_sid();
        $this->assertRegExp('/^[a-zA-Z0-9,-]+$/', $id);
        $this->assertNotSame($id, $adapter->create_sid());
    }

    public function testValidateIdRejectsOnlyTheJustCreatedId()
    {
        $adapter = new Zend_Session_SaveHandler_SessionHandlerAdapter(
            new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler()
        );

        $this->assertTrue($adapter->validateId('unknownSessionId'));

        $id = $adapter->create_sid();
        $this->assertFalse($adapter->validateId($id));
        $this->assertTrue($adapter->validateId($id));
    }

    public function testValidateIdAcceptsCreatedIdOnceItWasRead()
    {
        $adapter = new Zend_Session_SaveHandler_SessionHandlerAdapter(
            new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler()
        );

        $id = $adapter->create_sid();
        $adapter->read($id);
        $this->assertTrue($adapter->validateId($id));
    }

    /**
     * Same assertions on all php versions: below 8.4 Zend_Session::setSaveHandler() registers
     * the callbacks, on 8.4+ the adapter.
     *
     * @runInSeparateProcess
     */
    public function testSessionIdHandlingOfRegisteredSaveHandler()
    {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_cookies', '0');
        Zend_Session::$_unitTestEnabled = false;
        $handler = new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler();
        Zend_Session::setSaveHandler($handler);

        // an unknown session id is accepted in strict mode, the save handler cannot validate ids
        session_id('zf1sUnknownSessionId0123456789ab');
        $this->assertTrue(session_start());
        $this->assertSame('zf1sUnknownSessionId0123456789ab', session_id());
        $_SESSION['foo'] = 'bar';

        // new session ids are not mistaken for collisions
        $this->assertTrue(session_regenerate_id());
        $regeneratedId = session_id();
        $this->assertNotSame('zf1sUnknownSessionId0123456789ab', $regeneratedId);
        $this->assertInternalType('string', session_create_id());
        session_write_close();
        $this->assertSame('foo|s:3:"bar";', $handler->data[$regeneratedId]);

        // the session is continued when started again
        $this->assertTrue(session_start());
        $this->assertSame($regeneratedId, session_id());
        $this->assertSame('bar', $_SESSION['foo']);
        session_write_close();
    }

    /**
     * @runInSeparateProcess
     */
    public function testNewSessionIdIsKeptWhenSessionIsStartedAgain()
    {
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_cookies', '0');
        Zend_Session::$_unitTestEnabled = false;
        $handler = new Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler();
        Zend_Session::setSaveHandler($handler);

        $this->assertTrue(session_start());
        $id = session_id();
        $_SESSION['foo'] = 'bar';
        session_write_close();

        $this->assertTrue(session_start());
        $this->assertSame($id, session_id());
        $this->assertSame('bar', $_SESSION['foo']);
        session_write_close();
    }
}

/**
 * Save handler keeping the session data in memory and recording the calls
 */
class Zend_Session_SaveHandler_SessionHandlerAdapterTest_ArrayHandler implements Zend_Session_SaveHandler_Interface
{
    public $data = array();

    public $calls = array();

    public function open($save_path, $name)
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        return true;
    }

    public function close()
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        return true;
    }

    public function read($id)
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        return isset($this->data[$id]) ? $this->data[$id] : '';
    }

    public function write($id, $data)
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        $this->data[$id] = $data;
        return true;
    }

    public function destroy($id)
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        unset($this->data[$id]);
        return true;
    }

    public function gc($maxlifetime)
    {
        $this->calls[] = array(__FUNCTION__, func_get_args());
        return 0;
    }
}
