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
 * @package    Zend_EventManager
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

// require_once 'Zend/EventManager/EventCollection.php';
// require_once 'Zend/EventManager/ListenerAggregate.php';

/**
 * @category   Zend
 * @package    Zend_EventManager
 * @subpackage UnitTests
 * @group      Zend_EventManager
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_EventManager_TestAsset_MockAggregate implements Zend_EventManager_ListenerAggregate
{

    protected $listeners = array();
    public $priority;

    public function attach(Zend_EventManager_EventCollection $events, $priority = null)
    {
        $this->priority = $priority;

        $listeners = array();
        $listeners[] = $events->attach('foo.bar', array( $this, 'fooBar' ));
        $listeners[] = $events->attach('foo.baz', array( $this, 'fooBaz' ));

        // same value as spl_object_hash(), which is deprecated since php 8.6
        $hash = PHP_VERSION_ID >= 80600
            ? sprintf('%016x%016x', spl_object_id($events), 0)
            : spl_object_hash($events);
        $this->listeners[ $hash ] = $listeners;

        return __METHOD__;
    }

    public function detach(Zend_EventManager_EventCollection $events)
    {
        // same value as spl_object_hash(), which is deprecated since php 8.6
        $hash = PHP_VERSION_ID >= 80600
            ? sprintf('%016x%016x', spl_object_id($events), 0)
            : spl_object_hash($events);
        foreach ($this->listeners[ $hash ] as $listener) {
            $events->detach($listener);
        }

        return __METHOD__;
    }

    public function fooBar()
    {
        return __METHOD__;
    }

    public function fooBaz()
    {
        return __METHOD__;
    }
}
