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
 * @package    Zend_Pdf
 * @subpackage Fonts
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** Zend_Pdf_Cmap */
// require_once 'Zend/Pdf/Cmap.php';


/**
 * Implements the "byte encoding" character map (type 0).
 *
 * This is the (legacy) Apple standard encoding mechanism and provides coverage
 * for characters in the Mac Roman character set only. Consequently, this cmap
 * type should be used only as a last resort.
 *
 * The mapping from Mac Roman to Unicode can be found at
 * {@link http://www.unicode.org/Public/MAPPINGS/VENDORS/APPLE/ROMAN.TXT}.
 *
 * @package    Zend_Pdf
 * @subpackage Fonts
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Cmap_ByteEncoding extends Zend_Pdf_Cmap
{
  /**** Instance Variables ****/


    /**
     * Glyph index array. Stores the actual glyph numbers. The array keys are
     * the translated Unicode code points.
     * @var array
     */
    protected $_glyphIndexArray = array();



  /**** Public Interface ****/


  /* Concrete Class Implementation */

    /**
     * Returns an array of glyph numbers corresponding to the Unicode characters.
     *
     * If a particular character doesn't exist in this font, the special 'missing
     * character glyph' will be substituted.
     *
     * See also {@link glyphNumberForCharacter()}.
     *
     * @param array $characterCodes Array of Unicode character codes (code points).
     * @return array Array of glyph numbers.
     */
    public function glyphNumbersForCharacters($characterCodes)
    {
        $glyphNumbers = array();
        foreach ($characterCodes as $key => $characterCode) {

           if (! isset($this->_glyphIndexArray[$characterCode])) {
                $glyphNumbers[$key] = Zend_Pdf_Cmap::MISSING_CHARACTER_GLYPH;
                continue;
            }

            $glyphNumbers[$key] = $this->_glyphIndexArray[$characterCode];

        }
        return $glyphNumbers;
    }

    /**
     * Returns the glyph number corresponding to the Unicode character.
     *
     * If a particular character doesn't exist in this font, the special 'missing
     * character glyph' will be substituted.
     *
     * See also {@link glyphNumbersForCharacters()} which is optimized for bulk
     * operations.
     *
     * @param integer $characterCode Unicode character code (code point).
     * @return integer Glyph number.
     */
    public function glyphNumberForCharacter($characterCode)
    {
        if (! isset($this->_glyphIndexArray[$characterCode])) {
            return Zend_Pdf_Cmap::MISSING_CHARACTER_GLYPH;
        }
        return $this->_glyphIndexArray[$characterCode];
    }

    /**
     * Returns an array containing the Unicode characters that have entries in
     * this character map.
     *
     * @return array Unicode character codes.
     */
    public function getCoveredCharacters()
    {
        return array_keys($this->_glyphIndexArray);
    }

    /**
     * Returns an array containing the glyphs numbers that have entries in this character map.
     * Keys are Unicode character codes (integers)
     *
     * This functionality is partially covered by glyphNumbersForCharacters(getCoveredCharacters())
     * call, but this method do it in more effective way (prepare complete list instead of searching
     * glyph for each character code).
     *
     * @internal
     * @return array Array representing <Unicode character code> => <glyph number> pairs.
     */
    public function getCoveredCharactersGlyphs()
    {
        return $this->_glyphIndexArray;
    }


  /* Object Lifecycle */

    /**
     * Object constructor
     *
     * Parses the raw binary table data. Throws an exception if the table is
     * malformed.
     *
     * @param string $cmapData Raw binary cmap table data.
     * @throws Zend_Pdf_Exception
     */
    public function __construct($cmapData)
    {
        /* Sanity check: This table must be exactly 262 bytes long.
         */
        $actualLength = strlen((string) $cmapData);
        if ($actualLength != 262) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Insufficient table data',
                                         Zend_Pdf_Exception::CMAP_TABLE_DATA_TOO_SMALL);
        }

        /* Sanity check: Make sure this is right data for this table type.
         */
        $type = $this->_extractUInt2($cmapData, 0);
        if ($type != Zend_Pdf_Cmap::TYPE_BYTE_ENCODING) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Wrong cmap table type',
                                         Zend_Pdf_Exception::CMAP_WRONG_TABLE_TYPE);
        }

        $length = $this->_extractUInt2($cmapData, 2);
        if ($length != $actualLength) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception("Table length ($length) does not match actual length ($actualLength)",
                                         Zend_Pdf_Exception::CMAP_WRONG_TABLE_LENGTH);
        }

        /* Mapping tables should be language-independent. The font may not work
         * as expected if they are not. Unfortunately, many font files in the
         * wild incorrectly record a language ID in this field, so we can't
         * call this a failure.
         */
        $language = $this->_extractUInt2($cmapData, 4);
        if ($language != 0) {
            // Record a warning here somehow?
        }

        /* The mapping between the Mac Roman and Unicode characters is static.
         * For simplicity, just put all 256 glyph indices into one array keyed
         * off the corresponding Unicode character.
         */
        $i = 6;
        $this->_glyphIndexArray[0x00]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x01]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x04]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x05]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x06]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x07]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x08]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x09]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x10]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x11]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x12]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x13]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x14]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x15]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x16]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x17]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x18]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x19]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x1f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x20]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x21]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x22]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x23]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x24]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x25]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x26]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x27]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x28]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x29]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x30]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x31]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x32]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x33]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x34]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x35]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x36]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x37]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x38]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x39]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x3f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x40]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x41]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x42]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x43]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x44]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x45]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x46]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x47]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x48]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x49]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x4f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x50]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x51]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x52]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x53]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x54]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x55]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x56]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x57]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x58]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x59]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x5f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x60]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x61]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x62]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x63]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x64]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x65]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x66]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x67]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x68]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x69]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x6f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x70]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x71]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x72]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x73]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x74]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x75]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x76]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x77]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x78]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x79]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7a]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7b]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7c]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7d]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7e]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x7f]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc4]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc7]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc9]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd6]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xdc]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe0]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe2]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe4]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe3]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe7]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe9]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xea]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xeb]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xed]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xec]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xee]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xef]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf3]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf2]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf4]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf6]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xfa]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf9]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xfb]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xfc]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2020] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb0]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa2]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa3]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa7]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2022] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb6]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xdf]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xae]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa9]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2122] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb4]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2260] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc6]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x221e] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2264] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2265] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2202] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2211] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x220f] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03c0] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x222b] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xaa]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xba]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x03a9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xe6]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xbf]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xac]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x221a] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0192] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2248] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2206] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xab]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xbb]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2026] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xa0]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc0]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc3]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd5]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0152] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0153] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2013] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2014] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201c] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201d] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2018] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2019] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf7]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x25ca] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xff]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0178] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2044] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x20ac] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2039] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x203a] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xfb01] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xfb02] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2021] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb7]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201a] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x201e] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x2030] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc2]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xca]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc1]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xcb]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xc8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xcd]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xce]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xcf]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xcc]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd3]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd4]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xf8ff] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd2]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xda]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xdb]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xd9]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x0131] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02c6] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02dc] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xaf]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02d8] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02d9] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02da] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0xb8]   = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02dd] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02db] = ord((string) $cmapData[$i++]);
        $this->_glyphIndexArray[0x02c7] = ord((string) $cmapData[$i]);
    }

}
