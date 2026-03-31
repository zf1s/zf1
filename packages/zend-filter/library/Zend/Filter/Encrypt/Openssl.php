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
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Filter_Encrypt_Interface
 */
// require_once 'Zend/Filter/Encrypt/Interface.php';

/**
 * Encryption adapter for openssl
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Filter_Encrypt_Openssl implements Zend_Filter_Encrypt_Interface
{
    /**
     * Definitions for encryption
     * array(
     *     'public'   => public keys
     *     'private'  => private keys
     *     'envelope' => resulting envelope keys
     * )
     */
    protected $_keys = array(
        'public'   => array(),
        'private'  => array(),
        'envelope' => array()
    );

    /**
     * Internal passphrase
     *
     * @var string
     */
    protected $_passphrase;

    /**
     * Internal compression
     *
     * @var array
     */
    protected $_compression;

    /**
     * Internal create package
     *
     * @var boolean
     */
    protected $_package = false;

    /**
     * Cipher method for seal/open operations.
     * When null, auto-detected via getCipher().
     *
     * @var string|null
     */
    protected $_cipher;

    /**
     * Initialization vector from last encryption
     *
     * @var string
     */
    protected $_iv = '';

    /**
     * Class constructor
     * Available options
     *   'public'      => public key
     *   'private'     => private key
     *   'envelope'    => envelope key
     *   'passphrase'  => passphrase
     *   'compression' => compress value with this compression adapter
     *   'package'     => pack envelope keys into encrypted string, simplifies decryption
     *   'cipher'      => cipher method for seal/open (auto-detected when not set)
     *
     * @param string|array $options Options for this adapter
     */
    public function __construct($options = array())
    {
        if (!extension_loaded('openssl')) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('This filter needs the openssl extension');
        }

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (!is_array($options)) {
            $options = array('public' => $options);
        }

        if (array_key_exists('passphrase', $options)) {
            $this->setPassphrase($options['passphrase']);
            unset($options['passphrase']);
        }

        if (array_key_exists('compression', $options)) {
            $this->setCompression($options['compression']);
            unset($options['compress']);
        }

        if (array_key_exists('package', $options)) {
            $this->setPackage($options['package']);
            unset($options['package']);
        }

        if (array_key_exists('cipher', $options)) {
            $this->setCipher($options['cipher']);
            unset($options['cipher']);
        }

        $this->_setKeys($options);
    }

    /**
     * Sets the encryption keys
     *
     * @param  string|array $keys Key with type association
     * @return Zend_Filter_Encrypt_Openssl
     */
    protected function _setKeys($keys)
    {
        if (!is_array($keys)) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Invalid options argument provided to filter');
        }

        foreach ($keys as $type => $key) {
            if (ctype_print((string) $key) && is_file(realpath($key)) && is_readable($key)) {
                $file = fopen($key, 'r');
                $cert = fread($file, 8192);
                fclose($file);
            } else {
                $cert = $key;
                $key  = count($this->_keys[$type]);
            }

            switch ($type) {
                case 'public':
                    $test = openssl_pkey_get_public($cert);
                    if ($test === false) {
                        // require_once 'Zend/Filter/Exception.php';
                        throw new Zend_Filter_Exception("Public key '{$cert}' not valid");
                    }
                    if (PHP_VERSION_ID < 80000) {
                        openssl_free_key($test);
                    }
                    $this->_keys['public'][$key] = $cert;
                    break;
                case 'private':
                    $test = openssl_pkey_get_private($cert, $this->_passphrase);
                    if ($test === false) {
                        // require_once 'Zend/Filter/Exception.php';
                        throw new Zend_Filter_Exception("Private key '{$cert}' not valid");
                    }
                    if (PHP_VERSION_ID < 80000) {
                        openssl_free_key($test);
                    }
                    $this->_keys['private'][$key] = $cert;
                    break;
                case 'envelope':
                    $this->_keys['envelope'][$key] = $cert;
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    /**
     * Returns all public keys
     *
     * @return array
     */
    public function getPublicKey()
    {
        $key = $this->_keys['public'];
        return $key;
    }

    /**
     * Sets public keys
     *
     * @param  string|array $key Public keys
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setPublicKey($key)
    {
        if (is_array($key)) {
            foreach($key as $type => $option) {
                if ($type !== 'public') {
                    $key['public'] = $option;
                    unset($key[$type]);
                }
            }
        } else {
            $key = array('public' => $key);
        }

        return $this->_setKeys($key);
    }

    /**
     * Returns all private keys
     *
     * @return array
     */
    public function getPrivateKey()
    {
        $key = $this->_keys['private'];
        return $key;
    }

    /**
     * Sets private keys
     *
     * @param  string $key Private key
     * @param  string $passphrase
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setPrivateKey($key, $passphrase = null)
    {
        if (is_array($key)) {
            foreach($key as $type => $option) {
                if ($type !== 'private') {
                    $key['private'] = $option;
                    unset($key[$type]);
                }
            }
        } else {
            $key = array('private' => $key);
        }

        if ($passphrase !== null) {
            $this->setPassphrase($passphrase);
        }

        return $this->_setKeys($key);
    }

    /**
     * Returns all envelope keys
     *
     * @return array
     */
    public function getEnvelopeKey()
    {
        $key = $this->_keys['envelope'];
        return $key;
    }

    /**
     * Sets envelope keys
     *
     * @param  string|array $options Envelope keys
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setEnvelopeKey($key)
    {
        if (is_array($key)) {
            foreach($key as $type => $option) {
                if ($type !== 'envelope') {
                    $key['envelope'] = $option;
                    unset($key[$type]);
                }
            }
        } else {
            $key = array('envelope' => $key);
        }

        return $this->_setKeys($key);
    }

    /**
     * Returns the passphrase
     *
     * @return string
     */
    public function getPassphrase()
    {
        return $this->_passphrase;
    }

    /**
     * Sets a new passphrase
     *
     * @param string $passphrase
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setPassphrase($passphrase)
    {
        $this->_passphrase = $passphrase;
        return $this;
    }

    /**
     * Returns the compression
     *
     * @return array
     */
    public function getCompression()
    {
        return $this->_compression;
    }

    /**
     * Sets a internal compression for values to encrypt
     *
     * @param string|array $compression
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setCompression($compression)
    {
        if (is_string($this->_compression)) {
            $compression = array('adapter' => $compression);
        }

        $this->_compression = $compression;
        return $this;
    }

    /**
     * Returns if header should be packaged
     *
     * @return boolean
     */
    public function getPackage()
    {
        return $this->_package;
    }

    /**
     * Sets if the envelope keys should be included in the encrypted value
     *
     * @param boolean $package
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setPackage($package)
    {
        $this->_package = (boolean) $package;
        return $this;
    }

    /**
     * Encrypts $value with the defined settings
     * Note that you also need the "encrypted" keys to be able to decrypt
     *
     * @param  string $value Content to encrypt
     * @return string The encrypted content
     * @throws Zend_Filter_Exception
     */
    public function encrypt($value)
    {
        $encrypted     = array();
        $encryptedkeys = array();

        if (count($this->_keys['public']) == 0) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Openssl can not encrypt without public keys');
        }

        $keys         = array();
        $fingerprints = array();
        $count        = -1;
        foreach($this->_keys['public'] as $key => $cert) {
            $keys[$key] = openssl_pkey_get_public($cert);
            if ($this->_package) {
                $details = openssl_pkey_get_details($keys[$key]);
                if ($details === false) {
                    $details = array('key' => 'ZendFramework');
                }

                ++$count;
                $fingerprints[$count] = md5($details['key']);
            }
        }

        // compress prior to encryption
        if (!empty($this->_compression)) {
            // require_once 'Zend/Filter/Compress.php';
            $compress = new Zend_Filter_Compress($this->_compression);
            $value    = $compress->filter($value);
        }

        // The $iv output parameter is only supported in PHP >= 7.0; on older versions
        // $iv stays empty - the default fallback ciphers for PHP < 7.0 (RC4, AES-128-ECB)
        // don't require an IV anyway. See getCipher().
        // The IV is prepended to the encrypted output so decrypt() can extract it.
        $cipher = $this->getCipher();
        $iv = '';
        if (PHP_VERSION_ID >= 70000) {
            $crypt = openssl_seal($value, $encrypted, $encryptedkeys, $keys, $cipher, $iv);
            $this->_iv = $iv;
        } else {
            $crypt = openssl_seal($value, $encrypted, $encryptedkeys, $keys, $cipher);
        }

        if (PHP_VERSION_ID < 80000) {
            foreach ($keys as $key) {
                openssl_free_key($key);
            }
        }

        if ($crypt === false) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Openssl was not able to encrypt your content with the given options');
        }

        $this->_keys['envelope'] = $encryptedkeys;

        // Pack data and envelope keys into single string
        if ($this->_package) {
            $header = pack('n', count($this->_keys['envelope']));
            foreach($this->_keys['envelope'] as $key => $envKey) {
                $header .= pack('H32n', $fingerprints[$key], strlen($envKey)) . $envKey;
            }

            $encrypted = $header . $iv . $encrypted;
        } else {
            $encrypted = $iv . $encrypted;
        }

        return $encrypted;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Decrypts $value with the defined settings
     *
     * @param  string $value Content to decrypt
     * @return string The decrypted content
     * @throws Zend_Filter_Exception
     */
    public function decrypt($value)
    {
        $decrypted = "";
        $envelope  = current($this->getEnvelopeKey());

        if (count($this->_keys['private']) !== 1) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Please give a private key for decryption with Openssl');
        }

        if (!$this->_package && empty($envelope)) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Please give a envelope key for decryption with Openssl');
        }

        foreach($this->_keys['private'] as $key => $cert) {
            $keys = openssl_pkey_get_private($cert, $this->getPassphrase());
        }

        if ($this->_package) {
            $details = openssl_pkey_get_details($keys);
            if ($details !== false) {
                $fingerprint = md5($details['key']);
            } else {
                $fingerprint = md5("ZendFramework");
            }

            $count = unpack('ncount', $value);
            $count = $count['count'];
            $length  = 2;
            for($i = $count; $i > 0; --$i) {
                $header = unpack('H32print/nsize', substr($value, $length, 18));
                $length  += 18;
                if ($header['print'] == $fingerprint) {
                    $envelope = substr($value, $length, $header['size']);
                }

                $length += $header['size'];
            }

            // remainder of string is the value to decrypt
            $value = substr($value, $length);
        }

        $cipher = $this->getCipher();
        if (PHP_VERSION_ID >= 70000) {
            // extract IV and decrypt - PHP >= 7.0 supports the IV parameter and
            // the IV was embedded during encrypt(); older versions don't use IV at all
            $ivLength = openssl_cipher_iv_length($cipher);
            if ($ivLength > 0 && strlen($value) > $ivLength) {
                $iv = substr($value, 0, $ivLength);
                $value = substr($value, $ivLength);
            } elseif ($ivLength > 0) {
                // value too short to contain IV - pad to required length so openssl_open
                // doesn't error on IV length; decryption will fail and return false
                $iv = str_pad(substr($value, 0, $ivLength), $ivLength, "\0");
                $value = '';
            } else {
                $iv = '';
            }
            $crypt = openssl_open($value, $decrypted, $envelope, $keys, $cipher, $iv);
        } else {
            $crypt = openssl_open($value, $decrypted, $envelope, $keys, $cipher);
        }
        if (PHP_VERSION_ID < 80000) {
            openssl_free_key($keys);
        }

        if ($crypt === false) {
            // require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('Openssl was not able to decrypt you content with the given options');
        }

        // decompress after decryption
        if (!empty($this->_compression)) {
            // require_once 'Zend/Filter/Decompress.php';
            $decompress = new Zend_Filter_Decompress($this->_compression);
            $decrypted  = $decompress->filter($decrypted);
        }

        return $decrypted;
    }

    /**
     * Returns the cipher used for seal/open operations.
     *
     * RC4 was the implicit default in openssl_seal() before PHP 8.0 and was later hardcoded
     * explicitly, but is disabled in OpenSSL 3.x (i.a. Ubuntu 22.04+).
     * RC4 is considered insecure (see php.net/openssl-seal).
     *
     * Auto-detects the best available cipher when not explicitly set:
     * - RC4 if available and OpenSSL < 3.0 (backward compat with existing encrypted data)
     * - AES-128-ECB on PHP < 7.0 (no IV param support in openssl_seal/openssl_open)
     * - AES-128-CBC on PHP 7.0+
     *
     * AES-128 is used to match RC4's 128-bit key size. AES-256 would also work,
     * but 128-bit is widely considered sufficient and keeps the fallback consistent.
     *
     * @return string
     */
    public function getCipher()
    {
        if ($this->_cipher !== null) {
            return $this->_cipher;
        }

        // RC4 is listed by openssl_get_cipher_methods() on PHP 5.6-8.0 even when
        // OpenSSL 3.x has it disabled (PHP 8.1+ correctly removed it from the list).
        // Only trust the listing on OpenSSL < 3.0.
        if (OPENSSL_VERSION_NUMBER < 0x30000000
            && in_array('RC4', openssl_get_cipher_methods())
        ) {
            $this->_cipher = 'RC4';
        } elseif (PHP_VERSION_ID < 70000) {
            // PHP < 7.0 does not support the IV parameter in openssl_seal/openssl_open,
            // so we must use a cipher that does not require an IV.
            // ECB (Electronic Codebook) encrypts each block independently without IV.
            $this->_cipher = 'AES-128-ECB';
        } else {
            // CBC (Cipher Block Chaining) - each block is XORed with the previous ciphertext
            // block, requires IV. GCM would be stronger (authenticated encryption) but
            // openssl_seal/openssl_open can't handle authentication tags.
            $this->_cipher = 'AES-128-CBC';
        }

        return $this->_cipher;
    }

    /**
     * Sets the cipher method for seal/open operations
     *
     * @param string $cipher
     * @return Zend_Filter_Encrypt_Openssl
     */
    public function setCipher($cipher)
    {
        $this->_cipher = $cipher;
        return $this;
    }

    /**
     * Returns the adapter name
     *
     * @return string
     */
    public function toString()
    {
        return 'Openssl';
    }
}
