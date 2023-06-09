<?php
#[AllowDynamicProperties]
class Zend_Session_SessionHelper
{
    /**
     * Destroy session and reset all internal changes
     */
    public static function reset() {
        if (Zend_Session::isStarted()) {
            Zend_Session::destroy();
        }
        if (session_id()) {
            session_destroy();
        }

        Zend_Session_Namespace::unlockAll();
        Zend_Session_Namespace::resetSingleInstance();

        foreach(array(
                    'Zend_Session_Abstract::_writable' => false,
                    'Zend_Session_Abstract::_readable' => false,
                    'Zend_Session_Abstract::_expiringData' => array(),
                    'Zend_Session::_sessionStarted' => false,
                    'Zend_Session::_regenerateIdState' => 0,
                    'Zend_Session::_writeClosed' => false,
                    'Zend_Session::_sessionCookieDeleted' => false,
                    'Zend_Session::_destroyed' => false,
                    'Zend_Session::_strict' => false,
                    'Zend_Session::_defaultOptionsSet' => false,
                ) as $prop => $value) {
            list($class, $property) = explode('::', $prop);
            $reflection = new ReflectionProperty($class, $property);
            $reflection->setAccessible(true);
            $reflection->setValue(null, $value);
        }
    }
}
