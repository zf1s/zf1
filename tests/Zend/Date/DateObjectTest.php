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
 * @package    Zend_Date
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Zend_Date
 */
// require_once 'Zend/Date.php';

/**
 * @category   Zend
 * @package    Zend_Date
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Date
 */
class Zend_Date_DateObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string|mixed
     */
    protected $originalTimezone;

    /**
     * $var Zend_Cache_Core
     */
    protected $_cache;

    public function setUp()
    {
        $this->originalTimezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Paris');
        // require_once 'Zend/Cache.php';
        $this->_cache = Zend_Cache::factory('Core', 'File',
                 ['lifetime' => 120, 'automatic_serialization' => true],
                 ['cache_dir' => dirname(__FILE__) . '/../_files/']);
        Zend_Date_DateObjectTestHelper::setOptions(['cache' => $this->_cache]);
    }

    public function tearDown()
    {
        date_default_timezone_set($this->originalTimezone);
        $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
    }

    /**
     * Test for date object creation null value
     */
    public function testCreationNull()
    {
        // look if locale is detectable
        try {
            $locale = new Zend_Locale();
        } catch (Zend_Locale_Exception $e) {
            $this->markTestSkipped('Autodetection of locale failed');
            return;
        }

        $date = new Zend_Date(0);
        $this->assertTrue($date instanceof Zend_Date);
    }

    /**
     * Test for date object creation negative timestamp
     */
    public function testCreationNegative()
    {
        // look if locale is detectable
        try {
            $locale = new Zend_Locale();
        } catch (Zend_Locale_Exception $e) {
            $this->markTestSkipped('Autodetection of locale failed');
            return;
        }

        $date = new Zend_Date(1000);
        $this->assertTrue($date instanceof Zend_Date);
    }

    /**
     * Test for date object creation text given
     */
    public function testCreationFailed()
    {
        // look if locale is detectable
        try {
            $locale = new Zend_Locale();
        } catch (Zend_Locale_Exception $e) {
            $this->markTestSkipped('Autodetection of locale failed');
            return;
        }

        try {
            $date = new Zend_Date("notimestamp");
            $this->fail("exception expected");
        } catch (Zend_Date_Exception $e) {
            // success
        }
    }

    /**
     * Test for setUnixTimestamp
     */
    public function testsetUnixTimestamp()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $diff = abs(time() - $date->getUnixTimestamp());
        $this->assertTrue(($diff < 2), "Zend_Date->setUnixTimestamp() returned a significantly "
            . "different timestamp than expected: $diff seconds");
        $date->setUnixTimestamp(0);
        $this->assertSame('0', (string)$date->setUnixTimestamp("12345678901234567890"));
        $this->assertSame("12345678901234567890", (string)$date->setUnixTimestamp("12345678901234567890"));

        $date->setUnixTimestamp();
        $diff = abs(time() - $date->getUnixTimestamp());
        $this->assertTrue($diff < 2, "setUnixTimestamp has a significantly different time than returned by time()): $diff seconds");
    }

    /**
     * Test for setUnixTimestampFailed
     */
    public function testsetUnixTimestampFailed()
    {
        try {
            $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
            $date->setUnixTimestamp("notimestamp");
            $this->fail("exception expected");
        } catch (Zend_Date_Exception $e) {
            // success
        }
    }

    /**
     * Test for getUnixTimestamp
     */
    public function testgetUnixTimestamp()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $result = $date->getUnixTimestamp();
        $diff = abs($result - time());
        $this->assertTrue($diff < 2, "Instance of Zend_Date_DateObject has a significantly different time than returned by setTime(): $diff seconds");
    }

    /**
     * Test for mktime
     */
    public function testMkTimeforDateValuesInPHPRange()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertSame(  mktime(0, 0, 0, 12, 30, 2037), $date->mktime(0, 0, 0, 12, 30, 2037, false));
        $this->assertSame(gmmktime(0, 0, 0, 12, 30, 2037), $date->mktime(0, 0, 0, 12, 30, 2037, true ));

        $this->assertSame(  mktime(0, 0, 0,  1,  1, 2000), $date->mktime(0, 0, 0,  1,  1, 2000, false));
        $this->assertSame(gmmktime(0, 0, 0,  1,  1, 2000), $date->mktime(0, 0, 0,  1,  1, 2000, true ));

        $this->assertSame(  mktime(0, 0, 0,  1,  1, 1970), $date->mktime(0, 0, 0,  1,  1, 1970, false));
        $this->assertSame(gmmktime(0, 0, 0,  1,  1, 1970), $date->mktime(0, 0, 0,  1,  1, 1970, true ));

        $this->assertSame(  mktime(0, 0, 0, 12, 30, 1902), $date->mktime(0, 0, 0, 12, 30, 1902, false));
        $this->assertSame(gmmktime(0, 0, 0, 12, 30, 1902), $date->mktime(0, 0, 0, 12, 30, 1902, true ));
    }

    /**
     * Test for mktime
     */
    public function testMkTimeforDateValuesGreaterPHPRange()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertSame(2232658800,  $date->mktime(0, 0, 0,10, 1, 2040, false));
        $this->assertSame(2232662400,  $date->mktime(0, 0, 0,10, 1, 2040, true ));
        $this->assertSame(7258114800,  $date->mktime(0, 0, 0, 1, 1, 2200, false));
        $this->assertSame(7258118400,  $date->mktime(0, 0, 0, 1, 1, 2200, true ));
        $this->assertSame(16749586800, $date->mktime(0, 0, 0,10,10, 2500, false));
        $this->assertSame(16749590400, $date->mktime(0, 0, 0,10,10, 2500, true ));
        $this->assertSame(32503676400, $date->mktime(0, 0, 0, 1, 1, 3000, false));
        $this->assertSame(32503680000, $date->mktime(0, 0, 0, 1, 1, 3000, true ));
        $this->assertSame(95617580400, $date->mktime(0, 0, 0, 1, 1, 5000, false));
        $this->assertSame(95617584000, $date->mktime(0, 0, 0, 1, 1, 5000, true ));

        // test for different set external timezone
        // the internal timezone should always be used for calculation
        $date->setTimezone('Europe/Paris');
        $this->assertSame(1577833200, $date->mktime(0, 0, 0, 1, 1, 2020, false));
        $this->assertSame(1577836800, $date->mktime(0, 0, 0, 1, 1, 2020, true ));
        date_default_timezone_set('Indian/Maldives');
        $this->assertSame(1577833200, $date->mktime(0, 0, 0, 1, 1, 2020, false));
        $this->assertSame(1577836800, $date->mktime(0, 0, 0, 1, 1, 2020, true ));
    }

    /**
     * Test for mktime
     */
    public function testMkTimeforDateValuesSmallerPHPRange()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertSame(-2208992400,   $date->mktime(0, 0, 0, 1, 1, 1900, false));
        $this->assertSame(-2208988800,   $date->mktime(0, 0, 0, 1, 1, 1900, true ));
        $this->assertSame(-8520339600,   $date->mktime(0, 0, 0, 1, 1, 1700, false));
        $this->assertSame(-8520336000,   $date->mktime(0, 0, 0, 1, 1, 1700, true ));
        $this->assertSame(-14830995600,  $date->mktime(0, 0, 0, 1, 1, 1500, false));
        $this->assertSame(-14830992000,  $date->mktime(0, 0, 0, 1, 1, 1500, true ));
        $this->assertSame(-12219321600,  $date->mktime(0, 0, 0,10,10, 1582, false));
        $this->assertSame(-12219321600,  $date->mktime(0, 0, 0,10,10, 1582, true ));
        $this->assertSame(-30609795600,  $date->mktime(0, 0, 0, 1, 1, 1000, false));
        $this->assertSame(-30609792000,  $date->mktime(0, 0, 0, 1, 1, 1000, true ));
        $this->assertSame(-62167395600,  $date->mktime(0, 0, 0, 1, 1,    0, false));
        $this->assertSame(-62167392000,  $date->mktime(0, 0, 0, 1, 1,    0, true ));
        $this->assertSame(-125282595600, $date->mktime(0, 0, 0, 1, 1,-2000, false));
        $this->assertSame(-125282592000, $date->mktime(0, 0, 0, 1, 1,-2000, true));

        $this->assertSame(-2208992400, $date->mktime(0, 0, 0, 13, 1, 1899, false));
        $this->assertSame(-2208988800, $date->mktime(0, 0, 0, 13, 1, 1899, true));
        $this->assertSame(-2208992400, $date->mktime(0, 0, 0,-11, 1, 1901, false));
        $this->assertSame(-2208988800, $date->mktime(0, 0, 0,-11, 1, 1901, true));
    }

    public function testIsLeapYear()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertTrue ($date->checkLeapYear(2000));
        $this->assertFalse($date->checkLeapYear(2002));
        $this->assertTrue ($date->checkLeapYear(2004));
        $this->assertFalse($date->checkLeapYear(1899));
        $this->assertTrue ($date->checkLeapYear(1500));
        $this->assertFalse($date->checkLeapYear(1455));
    }

    public function testWeekNumber()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertSame((int) date('W',mktime(0, 0, 0,  1,  1, 2000)), $date->weekNumber(2000,  1,  1));
        $this->assertSame((int) date('W',mktime(0, 0, 0, 10,  1, 2020)), $date->weekNumber(2020, 10,  1));
        $this->assertSame((int) date('W',mktime(0, 0, 0,  5, 15, 2005)), $date->weekNumber(2005,  5, 15));
        $this->assertSame((int) date('W',mktime(0, 0, 0, 11, 22, 1994)), $date->weekNumber(1994, 11, 22));
        $this->assertSame((int) date('W',mktime(0, 0, 0, 12, 31, 2000)), $date->weekNumber(2000, 12, 31));
        $this->assertSame(52, $date->weekNumber(2050, 12, 31));
        $this->assertSame(23, $date->weekNumber(2050,  6,  6));
        $this->assertSame(52, $date->weekNumber(2056,  1,  1));
        $this->assertSame(52, $date->weekNumber(2049, 12, 31));
        $this->assertSame(53, $date->weekNumber(2048, 12, 31));
        $this->assertSame( 1, $date->weekNumber(2047, 12, 31));
    }

    public function testDayOfWeek()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 1, 2000)), $date->dayOfWeekHelper(2000, 1, 1));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 2, 2000)), $date->dayOfWeekHelper(2000, 1, 2));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 3, 2000)), $date->dayOfWeekHelper(2000, 1, 3));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 4, 2000)), $date->dayOfWeekHelper(2000, 1, 4));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 5, 2000)), $date->dayOfWeekHelper(2000, 1, 5));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 6, 2000)), $date->dayOfWeekHelper(2000, 1, 6));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 7, 2000)), $date->dayOfWeekHelper(2000, 1, 7));
        $this->assertSame((int) date('w',mktime(0, 0, 0, 1, 8, 2000)), $date->dayOfWeekHelper(2000, 1, 8));
        $this->assertSame(6, $date->dayOfWeekHelper(2050, 1, 1));
        $this->assertSame(0, $date->dayOfWeekHelper(2050, 1, 2));
        $this->assertSame(1, $date->dayOfWeekHelper(2050, 1, 3));
        $this->assertSame(2, $date->dayOfWeekHelper(2050, 1, 4));
        $this->assertSame(3, $date->dayOfWeekHelper(2050, 1, 5));
        $this->assertSame(4, $date->dayOfWeekHelper(2050, 1, 6));
        $this->assertSame(5, $date->dayOfWeekHelper(2050, 1, 7));
        $this->assertSame(6, $date->dayOfWeekHelper(2050, 1, 8));
        $this->assertSame(4, $date->dayOfWeekHelper(1500, 1, 1));
    }

    public function testCalcSunInternal()
    {
        $date = new Zend_Date_DateObjectTestHelper(10000000);
        // PHP 7.2.0+ uses a newer algorithm for sunrise/sunset calculation apparently.
        // Seems to be changed in this commit of "timelib":
        // https://github.com/derickr/timelib/commit/8d0066f7110d4b8bd1a745bc6628c34577c34ba5
        // Brought into PHP in this commit:
        // https://github.com/php/php-src/commit/bdd56f31078bf1f34341943603cf6aaa72e0db5c#diff-b1c4e94d91863a5644d2e9402ec633f1L10
        // (which was later reverted in php < 7.2.0)
        // Example of the difference: https://3v4l.org/v46rk
        // Not really something we can test the same in all versions, so doing a version_compare here.
        //
        // Moreover, when php 8.1.0 deprecated date_sunset() and date_sunrise() functions,
        // Zend_Date::calcSun() got modified to use recommended date_sun_info() function instead (which is available in php since v5.1)
        // but that function yields slightly different results since zenith angles for sunrise/sunset/twilights are hardcoded internally
        // and they are different to what zf used before (see $horizonDeclination in Zend_Date::calcSun() - moved from Zend_Date::_checkLocation())
        // but ONLY NOW they are accurate! (calculations for civil / nautical / astronomical twilight were totally wrong before)
        //
        // Still values returned by date_sun_info() are slightly different in php 8.0+, (but only for sunrise/sunset, not for twilight)
        // so yet another set of conditions is added to this test and DateTest::testSunFunc().
        if (PHP_VERSION_ID >= 80000) {
            $this->assertSame( 9961443, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(10010614, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame( 9966709, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(10005348, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame( 9947536, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9996685, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame( 9952780, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9991440, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame( 9923557, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame( 9972669, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame( 9928765, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame( 9967461, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame( 9985422, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(10034630, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame( 9990725, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(10029327, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        } else if (PHP_VERSION_ID >= 70200) {
            $this->assertSame( 9961524, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(10010533, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame( 9966789, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(10005268, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame( 9947616, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9996604, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame( 9952860, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9991360, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame( 9923637, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame( 9972589, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame( 9928845, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame( 9967381, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame( 9985502, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(10034549, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame( 9990805, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(10029247, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        } else {
            $this->assertSame( 9961489, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(10010559, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame( 9966815, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(10005234, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame( 9947581, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9996630, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame( 9952886, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame( 9991326, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame( 9923602, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame( 9972615, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame( 9928870, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame( 9967347, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame( 9985468, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(10034575, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame( 9990830, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(10029213, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        }

        $date = new Zend_Date_DateObjectTestHelper(-148309884);
        if (PHP_VERSION_ID >= 80000) {
            $this->assertSame(-148322895, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(-148274514, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame(-148318410, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(-148278999, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame(-148336802, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148288444, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame(-148332339, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148292906, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame(-148360779, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame(-148312459, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame(-148356355, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame(-148316884, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame(-148298918, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148250499, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame(-148294395, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148255022, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        } else if (PHP_VERSION_ID >= 70200) {
            $this->assertSame(-148322816, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(-148274594, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame(-148318332, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(-148279078, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame(-148336723, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148288523, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame(-148332261, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148292985, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame(-148360700, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame(-148312539, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame(-148356276, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame(-148316963, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame(-148298839, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148250578, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame(-148294316, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148255101, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        } else {
            $this->assertSame(-148322853, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], true ));
            $this->assertSame(-148274568, $date->calcSun(['latitude' =>  38.4, 'longitude' => -29], false));
            $this->assertSame(-148318306, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], true ));
            $this->assertSame(-148279115, $date->calcSun(['latitude' => -38.4, 'longitude' => -29], false));
            $this->assertSame(-148336760, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148288497, $date->calcSun(['latitude' =>  38.4, 'longitude' =>  29], false));
            $this->assertSame(-148332235, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], true ));
            $this->assertSame(-148293022, $date->calcSun(['latitude' => -38.4, 'longitude' =>  29], false));
            $this->assertSame(-148360738, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], true ));
            $this->assertSame(-148312513, $date->calcSun(['latitude' =>  38.4, 'longitude' => 129], false));
            $this->assertSame(-148356250, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], true ));
            $this->assertSame(-148317000, $date->calcSun(['latitude' => -38.4, 'longitude' => 129], false));
            $this->assertSame(-148298876, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148250552, $date->calcSun(['latitude' =>  38.4, 'longitude' =>-129], false));
            $this->assertSame(-148294291, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], true ));
            $this->assertSame(-148255138, $date->calcSun(['latitude' => -38.4, 'longitude' =>-129], false));
        }
    }

    public function testGetDate()
    {
        $date = new Zend_Date_DateObjectTestHelper(0);
        $this->assertTrue(is_array($date->getDateParts()));
        $this->assertTrue(is_array($date->getDateParts(1000000)));

        $test = [             'seconds' =>   40,      'minutes' => 46,
            'hours'   => 14,       'mday'    =>   12,      'wday'    =>  1,
            'mon'     =>  1,       'year'    => 1970,      'yday'    => 11,
            'weekday' => 'Monday', 'month'   => 'January', 0         => 1000000];
        $result = $date->getDateParts(1000000);

        $this->assertSame((int) $test['seconds'], (int) $result['seconds']);
        $this->assertSame((int) $test['minutes'], (int) $result['minutes']);
        $this->assertSame((int) $test['hours'],   (int) $result['hours']  );
        $this->assertSame((int) $test['mday'],    (int) $result['mday']   );
        $this->assertSame((int) $test['wday'],    (int) $result['wday']   );
        $this->assertSame((int) $test['mon'],     (int) $result['mon']    );
        $this->assertSame((int) $test['year'],    (int) $result['year']   );
        $this->assertSame((int) $test['yday'],    (int) $result['yday']   );
        $this->assertSame(      $test['weekday'],       $result['weekday']);
        $this->assertSame(      $test['month'],         $result['month']  );
        $this->assertSame(      $test[0],               $result[0]        );

        $test = [                'seconds' =>   20,      'minutes' => 33,
            'hours'   => 11,          'mday'    =>    6,      'wday'    =>  3,
            'mon'     =>  3,          'year'    => 1748,      'yday'    => 65,
            'weekday' => 'Wednesday', 'month'   => 'February', 0        => -7000000000];
        $result = $date->getDateParts(-7000000000);

        $this->assertSame((int) $test['seconds'], (int) $result['seconds']);
        $this->assertSame((int) $test['minutes'], (int) $result['minutes']);
        $this->assertSame((int) $test['hours'],   (int) $result['hours']  );
        $this->assertSame((int) $test['mday'],    (int) $result['mday']   );
        $this->assertSame((int) $test['wday'],    (int) $result['wday']   );
        $this->assertSame((int) $test['mon'],     (int) $result['mon']    );
        $this->assertSame((int) $test['year'],    (int) $result['year']   );
        $this->assertSame((int) $test['yday'],    (int) $result['yday']   );
        $this->assertSame(      $test['weekday'],       $result['weekday']);
        $this->assertSame(      $test['month'],         $result['month']  );
        $this->assertSame(      $test[0],               $result[0]        );

        $test = [               'seconds' => 0,        'minutes' => 40,
            'hours'   => 2,          'mday'    => 26,       'wday'    => 2,
            'mon'     => 8,          'year'    => 2188,     'yday'    => 238,
            'weekday' => 'Tuesday', 'month'   => 'July', 0      => 6900000000];
        $result = $date->getDateParts(6900000000);

        $this->assertSame((int) $test['seconds'], (int) $result['seconds']);
        $this->assertSame((int) $test['minutes'], (int) $result['minutes']);
        $this->assertSame((int) $test['hours'],   (int) $result['hours']  );
        $this->assertSame((int) $test['mday'],    (int) $result['mday']   );
        $this->assertSame((int) $test['wday'],    (int) $result['wday']   );
        $this->assertSame((int) $test['mon'],     (int) $result['mon']    );
        $this->assertSame((int) $test['year'],    (int) $result['year']   );
        $this->assertSame((int) $test['yday'],    (int) $result['yday']   );
        $this->assertSame(      $test['weekday'],       $result['weekday']);
        $this->assertSame(      $test['month'],         $result['month']  );
        $this->assertSame(      $test[0],               $result[0]        );

        $test = [               'seconds' => 0,        'minutes' => 40,
            'hours'   => 2,          'mday'    => 26,       'wday'    => 3,
            'mon'     => 8,          'year'    => 2188,     'yday'    => 238,
            'weekday' => 'Wednesday', 'month'   => 'July', 0      => 6900000000];
        $result = $date->getDateParts(6900000000, true);

        $this->assertSame((int) $test['seconds'], (int) $result['seconds']);
        $this->assertSame((int) $test['minutes'], (int) $result['minutes']);
        $this->assertSame((int) $test['hours'],   (int) $result['hours']  );
        $this->assertSame((int) $test['mday'],    (int) $result['mday']   );
        $this->assertSame((int) $test['mon'],     (int) $result['mon']    );
        $this->assertSame((int) $test['year'],    (int) $result['year']   );
        $this->assertSame((int) $test['yday'],    (int) $result['yday']   );
    }

    public function testDate()
    {
        $date = new Zend_Date_DateObjectTestHelper(0);
        $this->assertTrue($date->date('U') > 0);
        $this->assertSame(           '0', $date->date('U',0          ));
        $this->assertSame(           '0', $date->date('U',0,false    ));
        $this->assertSame(           '0', $date->date('U',0,true     ));
        $this->assertSame(  '6900000000', $date->date('U',6900000000 ));
        $this->assertSame( '-7000000000', $date->date('U',-7000000000));
        $this->assertSame(          '06', $date->date('d',-7000000000));
        $this->assertSame(         'Wed', $date->date('D',-7000000000));
        $this->assertSame(           '6', $date->date('j',-7000000000));
        $this->assertSame(   'Wednesday', $date->date('l',-7000000000));
        $this->assertSame(           '3', $date->date('N',-7000000000));
        $this->assertSame(          'th', $date->date('S',-7000000000));
        $this->assertSame(           '3', $date->date('w',-7000000000));
        $this->assertSame(          '65', $date->date('z',-7000000000));
        $this->assertSame(          '10', $date->date('W',-7000000000));
        $this->assertSame(       'March', $date->date('F',-7000000000));
        $this->assertSame(          '03', $date->date('m',-7000000000));
        $this->assertSame(         'Mar', $date->date('M',-7000000000));
        $this->assertSame(           '3', $date->date('n',-7000000000));
        $this->assertSame(          '31', $date->date('t',-7000000000));
        $this->assertSame(         'CET', $date->date('T',-7000000000));
        $this->assertSame(           '1', $date->date('L',-7000000000));
        $this->assertSame(        '1748', $date->date('o',-7000000000));
        $this->assertSame(        '1748', $date->date('Y',-7000000000));
        $this->assertSame(          '48', $date->date('y',-7000000000));
        $this->assertSame(          'pm', $date->date('a',-7000000000));
        $this->assertSame(          'PM', $date->date('A',-7000000000));
        $this->assertSame(         '523', $date->date('B',-7000000000));
        $this->assertSame(          '12', $date->date('g',-7000000000));
        $this->assertSame(          '12', $date->date('G',-7000000000));
        $this->assertSame(          '12', $date->date('h',-7000000000));
        $this->assertSame(          '12', $date->date('H',-7000000000));
        $this->assertSame(          '33', $date->date('i',-7000000000));
        $this->assertSame(          '20', $date->date('s',-7000000000));
        $this->assertSame('Europe/Paris', $date->date('e',-7000000000));
        $this->assertSame(           '0', $date->date('I',-7000000000));
        $this->assertSame(       '+0100', $date->date('O',-7000000000));
        $this->assertSame(      '+01:00', $date->date('P',-7000000000));
        $this->assertSame(         'CET', $date->date('T',-7000000000));
        $this->assertSame(        '3600', $date->date('Z',-7000000000));
        $this->assertSame('1748-03-06T12:33:20+01:00', $date->date('c',-7000000000));
        $this->assertSame('Wed, 06 Mar 1748 12:33:20 +0100', $date->date('r',-7000000000));
        $this->assertSame( '-7000000000', $date->date('U'    ,-7000000000 ));
        $this->assertSame(           'H', $date->date('\\H'  ,-7000000000 ));
        $this->assertSame(           '.', $date->date('.'    ,-7000000000 ));
        $this->assertSame(    '12:33:20', $date->date('H:i:s',-7000000000 ));
        $this->assertSame( '06-Mar-1748', $date->date('d-M-Y',-7000000000 ));
        $this->assertSame(  '6900000000', $date->date('U',6900000000, true));
        $this->assertSame(         '152', $date->date('B',6900000000, true));
        $this->assertSame(          '12', $date->date('g',6899993000, true));
        $this->assertSame(           '1', $date->date('g',6899997000, true));
        $this->assertSame(           '1', $date->date('g',6900039200, true));
        $this->assertSame(          '12', $date->date('h',6899993000, true));
        $this->assertSame(          '01', $date->date('h',6899997000, true));
        $this->assertSame(          '01', $date->date('h',6900040200, true));
        $this->assertSame(         'UTC', $date->date('e',-7000000000,true));
        $this->assertSame(           '0', $date->date('I',-7000000000,true));
        $this->assertSame(         'GMT', $date->date('T',-7000000000,true));
        $this->assertSame(           '6', $date->date('N',6899740800, true));
        $this->assertSame(          'st', $date->date('S',6900518000, true));
        $this->assertSame(          'nd', $date->date('S',6900604800, true));
        $this->assertSame(          'rd', $date->date('S',6900691200, true));
        $this->assertSame(           '7', $date->date('N',6900432000, true));
        $date->setTimezone('Europe/Vienna');
        date_default_timezone_set('Indian/Maldives');
        $reference = $date->date('U');
        $this->assertTrue(abs($reference - time()) < 2);
        $this->assertSame('69000000', $date->date('U',69000000));

        // ISO Year (o) depends on the week number so 1.1. can be last year is week is 52/53
        $this->assertSame('1739', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1740)));
        $this->assertSame('1740', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1741)));
        $this->assertSame('1742', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1742)));
        $this->assertSame('1743', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1743)));
        $this->assertSame('1744', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1744)));
        $this->assertSame('1744', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1745)));
        $this->assertSame('1745', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1746)));
        $this->assertSame('1746', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1747)));
        $this->assertSame('1748', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1748)));
        $this->assertSame('1749', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 1749)));
        $this->assertSame('2049', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 2050)));
        $this->assertSame('2050', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 2051)));
        $this->assertSame('2052', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 2052)));
        $this->assertSame('2053', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 2053)));
        $this->assertSame('2054', $date->date('o',$date->mktime(0, 0, 0, 1, 1, 2054)));
    }

    function testMktimeDay0And32()
    {
        // the following functionality is used by isTomorrow() and isYesterday() in Zend_Date.
        $date = new Zend_Date_DateObjectTestHelper(0);
        $this->assertSame('20060101', $date->date('Ymd', $date->mktime(0, 0, 0, 12, 32, 2005)));
        $this->assertSame('20050301', $date->date('Ymd', $date->mktime(0, 0, 0,  2, 29, 2005)));
        $this->assertSame('20051231', $date->date('Ymd', $date->mktime(0, 0, 0,  1,  0, 2006)));
        $this->assertSame('20050131', $date->date('Ymd', $date->mktime(0, 0, 0,  2,  0, 2005)));
    }

    /**
     * Test for setTimezone()
     */
    public function testSetTimezone()
    {
        $date = new Zend_Date_DateObjectTestHelper(0);

        date_default_timezone_set('Europe/Vienna');
        $date->setTimezone('Indian/Maldives');
        $this->assertSame('Indian/Maldives', $date->getTimezone());
        try {
            $date->setTimezone('Unknown');
            // without new phpdate false timezones do not throw an exception !
            // known and expected behaviour
            if (function_exists('timezone_open')) {
                $this->fail("exception expected");
            }
        } catch (Zend_Date_Exception $e) {
            $this->assertRegexp('/not a known timezone/i', $e->getMessage());
            $this->assertSame('Unknown', $e->getOperand());
        }
        $this->assertSame('Indian/Maldives', $date->getTimezone());
        $date->setTimezone();
        $this->assertSame('Europe/Vienna', $date->getTimezone());
    }

    /**
     * Test for gmtOffset
     */
    public function testgetGmtOffset()
    {
        $date = new Zend_Date_DateObjectTestHelper(0);

        date_default_timezone_set('Europe/Vienna');
        $date->setTimezone();

        $this->assertSame(-3600, $date->getGmtOffset());
        $date->setTimezone('GMT');
        $this->assertSame(    0, $date->getGmtOffset());
    }

    /**
     * Test for _getTime
     */
    public function test_getTime()
    {
        $date = new Zend_Date_DateObjectTestHelper(Zend_Date::now());
        $time = $date->_getTime();
        $diff = abs(time() - $time);
        $this->assertTrue(($diff < 2), "Zend_Date_DateObject->_getTime() returned a significantly "
            . "different timestamp than expected: $diff seconds");
    }
    
    /**
     * Test for RFC 2822's Obsolete Date and Time (paragraph 4.3)
     * 
     * @see ZF-11296
     */
    public function test_obsRfc2822()
    {
        $date = new Zend_Date();
        /* Obsolete timezones */
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 +0000", Zend_Date::RFC_2822) instanceof Zend_Date);
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 UT", Zend_Date::RFC_2822) instanceof Zend_Date);
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 GMT", Zend_Date::RFC_2822) instanceof Zend_Date);
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 EST", Zend_Date::RFC_2822) instanceof Zend_Date);
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 I", Zend_Date::RFC_2822) instanceof Zend_Date);
        $this->assertTrue($date->set("Mon, 15 Aug 2005 15:52:01 Z", Zend_Date::RFC_2822) instanceof Zend_Date);
    }

    public function testToStringShouldEqualWithAndWithoutPhpFormat()
    {
        $date = new Zend_Date('22.05.2014');
        $date->setTime('12:00');
        $date->setTimezone('America/Los_Angeles');
    
        $this->assertEquals(
            $date->toString(Zend_Date::ATOM),
            $date->toString(DateTime::ATOM, 'php')
        );
    }

    public function testIncompleteTimeData()
    {
        try {
            $date = new Zend_Date();
            $date->set(0, Zend_Date::TIMES);
        } catch (Exception $e) {
            $this->fail('This usually stems from iconv_substr returnvalues in Zend_Locale_Format not being handled properly');
        } catch (Throwable $e) {
            $this->fail('This usually stems from iconv_substr returnvalues in Zend_Locale_Format not being handled properly');
        }

        $this->assertInstanceOf('Zend_Date', $date);
    }
}

class Zend_Date_DateObjectTestHelper extends Zend_Date
{
    public function __construct($date = null, $part = null, $locale = null)
    {
        $this->setTimezone('Europe/Paris');
        parent::__construct($date, $part, $locale);
    }

    public function mktime($hour, $minute, $second, $month, $day, $year, $dst= -1, $gmt = false)
    {
        return parent::mktime($hour, $minute, $second, $month, $day, $year, $dst, $gmt);
    }

    public function getUnixTimestamp()
    {
        return parent::getUnixTimestamp();
    }

    public function setUnixTimestamp($timestamp = null)
    {
        return parent::setUnixTimestamp($timestamp);
    }

    public function weekNumber($year, $month, $day)
    {
        return parent::weekNumber($year, $month, $day);
    }

    public function dayOfWeekHelper($y, $m, $d)
    {
        return Zend_Date_DateObject::dayOfWeek($y, $m, $d);
    }

    public function calcSun($location, $rise = false)
    {
        return parent::calcSun($location, $rise);
    }

    public function date($format, $timestamp = null, $gmt = false)
    {
        return parent::date($format, $timestamp, $gmt);
    }

    public function getDateParts($timestamp = null, $fast = null)
    {
        return parent::getDateParts($timestamp, $fast);
    }

    public function _getTime($sync = null)
    {
        return parent::_getTime($sync);
    }
}
