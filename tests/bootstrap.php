<?php
/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting(E_ALL | E_STRICT);

$rootDir = dirname(dirname(__FILE__));

/**
 * Setup autoloading
 */
require $rootDir . '/vendor/autoload.php';

$zfTests = $rootDir . '/tests';
/*
 * Prepend the tests/ directory to the include_path.
 * This allows the tests to run without additional autoloader
 */
$path = array(
    $zfTests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Initial configuration
 */
Zend_Session::$_unitTestEnabled = true;

/*
 * Workarounds
 */
if (PHP_VERSION_ID >= 70300 && PCRE_VERSION_MAJOR === 10 && PCRE_VERSION_MINOR < 32) {
    // workaround for https://bugs.php.net/bug.php?id=76909 and ondrej's repo serving bugged libpcre2
    ini_set('pcre.jit', 0);
}

// workaround for Microsoft WSL
if (!defined('PHP_OS_WSL')) {
    $uname = php_uname();
    define('PHP_OS_WSL', strpos($uname, 'Linux') === 0 && strpos($uname, 'Microsoft'));
}

/*
 * Load the user-defined test configuration file, if it exists; otherwise, load
 * the default configuration.
 */
if (is_readable($zfTests . '/TestConfiguration.php')) {
    require_once $zfTests . '/TestConfiguration.php';
} else {
    require_once $zfTests . '/TestConfiguration.php.dist';
}

/*
 * Unset global variables that are no longer needed.
 */
unset($zfTests, $path);

/**
 * Start output buffering, if enabled
 */
if (defined('TESTS_ZEND_OB_ENABLED') && constant('TESTS_ZEND_OB_ENABLED')) {
    ob_start();
}
