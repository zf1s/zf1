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
 */

/**
 * Regression coverage for the drift between the frozen Zend_Db::FETCH_*
 * literals and the native PDO::FETCH_* constants, which PHP 8.5 renumbered.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @group      Zend_Db
 * @group      Zend_Db_Statement
 */
class Zend_Db_Statement_PdoFetchModeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param  mixed $mode
     * @return mixed
     */
    private function normalize($mode)
    {
        $method = new ReflectionMethod('Zend_Db_Statement_Pdo', '_normalizeFetchMode');
        $method->setAccessible(true);
        return $method->invoke(null, $mode);
    }

    public function testBaseFetchModesPassThroughUnchanged()
    {
        $this->assertSame(PDO::FETCH_ASSOC, $this->normalize(Zend_Db::FETCH_ASSOC));
        $this->assertSame(PDO::FETCH_NUM, $this->normalize(Zend_Db::FETCH_NUM));
        $this->assertSame(PDO::FETCH_BOTH, $this->normalize(Zend_Db::FETCH_BOTH));
        $this->assertSame(PDO::FETCH_OBJ, $this->normalize(Zend_Db::FETCH_OBJ));
        $this->assertSame(PDO::FETCH_COLUMN, $this->normalize(Zend_Db::FETCH_COLUMN));
    }

    public function testModifierFlagsMapToNativePdoValues()
    {
        $this->assertSame(PDO::FETCH_GROUP, $this->normalize(Zend_Db::FETCH_GROUP));
        $this->assertSame(PDO::FETCH_UNIQUE, $this->normalize(Zend_Db::FETCH_UNIQUE));
        $this->assertSame(PDO::FETCH_CLASSTYPE, $this->normalize(Zend_Db::FETCH_CLASSTYPE));
        $this->assertSame(PDO::FETCH_SERIALIZE, $this->normalize(Zend_Db::FETCH_SERIALIZE));
    }

    public function testCombinedFetchModesMapToNativePdoBitmask()
    {
        $this->assertSame(
            PDO::FETCH_GROUP | PDO::FETCH_COLUMN,
            $this->normalize(Zend_Db::FETCH_GROUP | Zend_Db::FETCH_COLUMN)
        );
        $this->assertSame(
            PDO::FETCH_UNIQUE | PDO::FETCH_COLUMN,
            $this->normalize(Zend_Db::FETCH_UNIQUE | Zend_Db::FETCH_COLUMN)
        );
    }

    /**
     * Regression guard: the legacy FETCH_UNIQUE literal (0x30000) embeds the
     * legacy FETCH_GROUP bit (0x10000). The remap must yield exactly
     * PDO::FETCH_UNIQUE and must not spuriously OR in PDO::FETCH_GROUP.
     */
    public function testOverlappingUniqueFlagRemapsExactly()
    {
        $this->assertSame(PDO::FETCH_UNIQUE, $this->normalize(Zend_Db::FETCH_UNIQUE));
    }

    public function testNonIntegerModeIsReturnedUnchanged()
    {
        $this->assertNull($this->normalize(null));
    }
}
