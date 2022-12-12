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
 * @package    Zend_Amf
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * This is the default Implementation of Message, which provides
 * a convenient base for behavior and association of common endpoints
 *
 * @package    Zend_Amf
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Amf_Value_Messaging_AbstractMessage
{
    // Copied the `correlationId` property definition from Zend_Amf_Value_Messaging_AsyncMessage,
    // to keep the order of properties when they are being serialized same as PHP <8.1
    // https://github.com/php/php-src/blob/578b67da49af51b2f796a48782e51ceb62860943/UPGRADING#L334-L341
    // In PHP 8.1, the order of properties is the same as in the class definition, but properties from parent class
    // always go after properties from the current class.
    // `correlationId` prop is expected to be at the beginning of serialized messages,
    // otherwise it breaks Zend_Amf_ResponseTest cases for php 8.1+.
    /**
     * The message id to be responded to.
     * @var String
     */
    public $correlationId;

    /**
     * @var string Client identifier
     */
    public $clientId;

    /**
     * @var string Destination
     */
    public $destination;

    /**
     * @var string Message identifier
     */
    public $messageId;

    /**
     * @var int Message timestamp
     */
    public $timestamp;

    /**
     * @var int Message TTL
     */
    public $timeToLive;

    /**
     * @var object Message headers
     */
    public $headers;

    /**
     * @var string Message body
     */
    public $body;

    /**
     * generate a unique id
     *
     * Format is: ########-####-####-####-############
     * Where # is an uppercase letter or number
     * example: 6D9DC7EC-A273-83A9-ABE3-00005FD752D6
     *
     * @return string
     */
    public function generateId()
    {
        return sprintf(
            '%08X-%04X-%04X-%02X%02X-%012X',
            mt_rand(),
            mt_rand(0, 65535),
            bindec(substr_replace(
                sprintf('%016b', mt_rand(0, 65535)), '0100', 11, 4)
            ),
            bindec(substr_replace(sprintf('%08b', mt_rand(0, 255)), '01', 5, 2)),
            mt_rand(0, 255),
            mt_rand()
        );
    }
}
