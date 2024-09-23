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
 * @package    Zend_Validate
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Validate_Float
 */
// require_once 'Zend/Validate/Float.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Validate
 */
class Zend_Validate_FloatTest extends PHPUnit_Framework_TestCase
{
    /**
     * Constant for Non-breaking space UTF-8 encoded value.
     * https://en.wikipedia.org/wiki/Non-breaking_space
     */
    const NBSP = "\xC2\xA0";

    /**
     * Zend_Validate_Float object
     *
     * @var Zend_Validate_Float
     */
    protected $_validator;

    /**
     * @var string
     */
    protected $_locale;

    /**
     * Creates a new Zend_Validate_Float object for each test method
     *
     * @return void
     */
    public function setUp()
    {
        $this->_locale = setlocale(LC_ALL, 0); //backup locale

        // require_once 'Zend/Registry.php';
        if (Zend_Registry::isRegistered('Zend_Locale')) {
            Zend_Registry::getInstance()->offsetUnset('Zend_Locale');
        }

        $this->_validator = new Zend_Validate_Float();
    }

    public function tearDown()
    {
        //restore locale
        if (is_string($this->_locale) && strpos($this->_locale, ';')) {
            $locales = [];
            foreach (explode(';', $this->_locale) as $l) {
                $tmp = explode('=', $l);
                $locales[$tmp[0]] = $tmp[1];
            }
            setlocale(LC_ALL, $locales);
            return;
        }
        setlocale(LC_ALL, $this->_locale);
    }

    /**
     * Ensures that the validator follows expected behavior
     *
     * @return void
     */
    public function testBasic()
    {
        $valuesExpected = [
            [1.00, true],
            [0.01, true],
            [-0.1, true],
            [1, true],
            ['not a float', false],
            ];
        foreach ($valuesExpected as $element) {
            $this->assertEquals($element[1], $this->_validator->isValid($element[0]));
        }
    }

    /**
     * Ensures that getMessages() returns expected default value
     *
     * @return void
     */
    public function testGetMessages()
    {
        $this->assertEquals([], $this->_validator->getMessages());
    }

    /**
     * Ensures that set/getLocale() works
     */
    public function testSettingLocales()
    {
        $this->_validator->setLocale('de');
        $this->assertEquals('de', $this->_validator->getLocale());
        $this->assertEquals(true, $this->_validator->isValid('10,5'));
    }

    /**
     * @ZF-4352
     */
    public function testNonStringValidation()
    {
        $this->assertFalse($this->_validator->isValid([1 => 1]));
    }

    /**
     * @ZF-7489
     */
    public function testUsingApplicationLocale()
    {
        Zend_Registry::set('Zend_Locale', new Zend_Locale('de'));
        $valid = new Zend_Validate_Float();
        $this->assertTrue($valid->isValid('123,456'));
    }

    /**
     * @ZF-7987
     */
    public function testNoZendLocaleButPhpLocale()
    {
        $locale = setlocale(LC_ALL, 0);
        setlocale(LC_ALL, 'de');
        $valid = new Zend_Validate_Float();
        $isValid1 = $valid->isValid(123.456);
        $isValid2 = $valid->isValid('123,456');
        setlocale(LC_ALL, $locale);
        $this->assertTrue($isValid1);
        $this->assertTrue($isValid2);
    }

    /**
     * @ZF-7987
     */
    public function testLocaleDeFloatType()
    {
        $this->_validator->setLocale('de');
        $this->assertEquals('de', $this->_validator->getLocale());
        $this->assertEquals(true, $this->_validator->isValid(10.5));
    }

    /**
     * @ZF-7987
     */
    public function testPhpLocaleDeFloatType()
    {
        $locale = setlocale(LC_ALL, 0);
        setlocale(LC_ALL, 'de');
        $valid = new Zend_Validate_Float();
        $isValid = $valid->isValid(10.5);
        setlocale(LC_ALL, $locale);
        $this->assertTrue($isValid);
    }

    /**
     * @ZF-7987
     */
    public function testPhpLocaleFrFloatType()
    {
        $locale = setlocale(LC_ALL, 0);
        setlocale(LC_ALL, 'fr');
        $valid = new Zend_Validate_Float();
        $isValid = $valid->isValid(10.5);
        setlocale(LC_ALL, $locale);
        $this->assertTrue($isValid);
    }

    /**
     * @ZF-8919
     */
    public function testPhpLocaleDeStringType()
    {
        $lcAll = setlocale(LC_ALL, 0);
        setlocale(LC_ALL, 'de_AT');
        $lcNumeric = setlocale(LC_NUMERIC, 0);
        setlocale(LC_NUMERIC, 'de_AT');
        $valid = new Zend_Validate_Float('de_AT');
        $isValid0 = $valid->isValid('1,3');
        $isValid1 = $valid->isValid('1000,3');
        $isValid2 = $valid->isValid('1.000,3');
        $isValid3 = $valid->isValid('1.3');
        $isValid4 = $valid->isValid('1000.3');
        $isValid5 = $valid->isValid('1,000.3');
        setlocale(LC_ALL, $lcAll);
        setlocale(LC_NUMERIC, $lcNumeric);
        $this->assertTrue($isValid0);
        $this->assertTrue($isValid1);
        $this->assertTrue($isValid2);
        $this->assertFalse($isValid3);
        $this->assertFalse($isValid4);
        $this->assertFalse($isValid5);
    }

    /**
     * @ZF-8919
     */
    public function testPhpLocaleFrStringType()
    {
        $valid = new Zend_Validate_Float('fr_FR');
        $this->assertTrue($valid->isValid('1,3'));
        $this->assertTrue($valid->isValid('1000,3'));
        $this->assertTrue($valid->isValid('1' . self::NBSP . '000,3'));
        $this->assertFalse($valid->isValid('1.3'));
        $this->assertFalse($valid->isValid('1000.3'));
        $this->assertFalse($valid->isValid('1,000.3'));
    }

    /**
     * @ZF-8919
     */
    public function testPhpLocaleEnStringType()
    {
        $valid = new Zend_Validate_Float('en_US');
        $this->assertTrue($valid->isValid('1.3'));
        $this->assertTrue($valid->isValid('1000.3'));
        $this->assertTrue($valid->isValid('1,000.3'));
        $this->assertFalse($valid->isValid('1,3'));
        $this->assertFalse($valid->isValid('1000,3'));
        $this->assertFalse($valid->isValid('1.000,3'));
    }
}
