## Changelog:

### 1.13.0
- general
  - re-introduce original tests suite and adjust it to run on php 5.3-7.3 (using modded https://github.com/zf1s/phpunit)
  - tests adjustments
    - composer autoloader and zend loader compatibility fixes
    - adjustments for changed folders structure of framework packages
    - Zend_DebugTest fixes backported from https://github.com/diablomedia/zf1/commit/ba8cf7aaf6542b7aadee79146709302d65e85bdd#diff-187c3c3644f1fbbe71be9a261283f95a
    - fix restoring initial locale properties and HTTP_ACCEPT_LANGUAGE in Zend_LocaleTest
    - silence 'tempnam(): file created in the system's temporary directory' errors in zend-config writer tests
    - fix property exists assertion in zend-amf test
    - skip 'resources' tests - those i18n resources were failing / unfinished on original repo
    - skip tests using mcrypt (deprecated since php 7.1)
    - Zend_VersionTest fix
    - commit test files for Zend_Filter_Compress for deterministic results
    - fix doctype inconsistencies when testing rendered html in Zend_Form_Decorator_ViewHelperTest
    - fixed tests sensitive to line endings in test files (expected CRLF)
    - BC Zend_Amf_RequestTest fix from https://github.com/diablomedia/zf1/commit/ba8cf7aaf6542b7aadee79146709302d65e85bdd#diff-68d7be0100c43bc2df55377c9d27cd05 (thanks!)
    - tell git do not touch line-endings in zend-http-response test files - tests rely on mixed line-endings in raw responses
    - portability adjustments - mainly for windows & winux (wsl)
- zend-cloud
  - fix lettercase of loaded class Zend_Service_Amazon_Ec2_Availabilityzones
- zend-codegenerator
  - fix output of Zend_CodeGenerator_Php_File
  - restore commented require_once
- zend-config
  - fix regression in reading yaml config - introduced in 1.12.21 (https://github.com/zf1s/zend-config/commit/544edd9815ac050745a59798ff9d713df3947c6b)
  - xml: restore error handler in case of exception
- zend-date
  - borrowed a few fixes fixes for zend-date tests from diablomedia/zf1-date (thanks!)
  - Fixing DateTest on servers with more recent timezone files https://github.com/diablomedia/zf1-date/commit/ef47f4f3bde9c175c32e4b73ac779bd88a33799e
  - sunrise/sunset calculation differences in php >= 7.2 https://github.com/diablomedia/zf1-date/commit/f69acaf6bc563af898144abd0a8aa1bbbf0e0308
- zend-feed
  - php 7.2 compatibility fixes
- zend-filter
  - fix auto-loading compress adapters
- zend-gdata
  - fix constructing GData-Version header
- zend-loader
  - zend-loader and autoloader overhaul
    - ensure full compatibility with composer autoloader
    - lazy instantiate Zend_Loader_Autoloader in Zend_Application (only if necessary)
    - add Zend_Loader_Autoloader::setDisabled() method for turning it off when necessary (to resolve conflicting cases with composer autoloader)
    - fixes regression in Zend_Loader_ClassMapAutoloader
    - fix for portability of Zend_Loader_ClassMapAutoloader::realPharPath (now works on windows as well)
    - Zend_Loader_PluginLoader::useComposerAutoloader() for further sorting conflicting cases
    - autoload cache frontends and backends in Zend_Cache::factory by default
    - do not instantiate autoloader in Zend_Tool_Framework_Client
    - fixed loading zend-translate adapters, validators, encrypt filter adapters
    - fixes remaining loader & class_exists calls
  - restore ZendX_ namespace in Zend_Loader_Autoloader - to be removed again properly later
- zend-locale
  - format+math+phpmath overhaul
    - untangled normalization - removed when value is expected in already normalized form
    - fix for issues with locales where e.g. thousand separator is a dot (e.g. german), i.a. https://github.com/zendframework/zf1/issues/706
    - apart from zend-currency, it will also have a big impact on zend-measure package (fixing calculations)
  - iconv_substr php 7.0.11+ compatibility fixes - borrowed from https://github.com/axot/zf1/commit/4c6400ad28f1f7a3448492f9d444aff1080c6 (thanks!)
- zend-mail 
  - php 7.2 compatibility fixes
- zend-measure
  - php 7.3 compatibility fixes
- zend-oauth
  - php 7.2 compatibility fixes
- zend-openid
  - do not throw error on failed symlink creation
  + fix incorrect usage of time() function
- zend-pdf
  - php 7.3 compatibility fixes
- zend-reflection
  - php7.3 compatibility fixes
- zend-session
  - do not ini_set options if unitTestEnabled
  + overhaul of session handling in tests - solve conflicting cases
- zend-test
  - php 7.3 compatibility fixes
  + test case fixes
  + portability fixes for loading xml files in tests
- zend-timesync
  - fix microtime() usage
- zend-tool
  - php 7.2 compatibility fixes
- zend-validate
  - idn_to_ascii: use INTL_IDNA_VARIANT_UTS46 contant, if available
  + file size calculation fixed for php 7.x
  + file upload validator compatibilty fix
- zend-view
  - php 7.2 compatibility fix for PartialLoop helper
- zend-xmlrpc
  - fix regression in loading values introduced in 1.12.21 https://github.com/zf1s/zend-xmlrpc/commit/ea5a62283b03d9226c44c5f0ec1442158c1f780e
  + restore php 5.3 compatibility
    

### 1.12.22
- zend-loader
    - make the introduced performance optimization for PluginLoader optional (zf1s/zend-loader#4)
- zend-view
    - php 5.3 compatibility fixes (zf1s/zend-view#2)

### 1.12.21
- zend-application
    - disable require once call and the autoloader (zf1s/zend-application#1)
- zend-cache
    - php 7.2 compatibility fixes
- zend-config
    - removed bad usage of count and improved string parsing (zf1s/zend-config#1)
    - php 7.2 compatibility fixes
    - added: retrieving nested value by `->get('value.value2.value3')]`
- zend-controller
    - composer autoloader compatibility fixes
- zend-date
    - Zend_Date::setTime fix for DST change zendframework/zf1#682
- zend-db
    - Fixed Warning: count(): Parameter must be an array or an object that implements Countable when $keyValuesCount is not an array (zf1s/zend-db#1)
- zend-feed
    - removed uses of deprecated function create_function() (zf1s/zend-feed#1)
- zend-filter
    - fixed handling of namespaced classes
- zend-form
    - php 7.3 compatibility fixes
- zend-http
    - Fix for "Notice: Undefined index: detail", example user-agent: "LightspeedSystemsCrawler Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US"
    - php 7.2 compatibility fixes
- zend-loader
    - Drop support for ZendX pseudo namespace (zf1s/zend-loader#1)
    - optimize loading plugins - use only composer autoloader
- zend-json
    - merged zendframework/zf1#680 to fix "Zend_Json::decode null or empty string throw Zend_Json_Exception on PHP7"
- zend-rest
    - updated assemble signature (zf1s/zend-rest#1)
- zend-validate
    - Added null check to avoid errors with passing null to a count parameter (zf1s/zend-validate#1)
    - fixed invalid assignment $this->_messages = null causing "Warning: count(): Parameter must be an array or an object that implements Countable"
    - fixed handling of namespaced classes
- zend-view
    - fix for missing combine() variable $extras, causing notice on php 7.3
- zend-xmlrpc
    - php 7.2 compatibility fixes

### 1.12.20
- Final release of the original project, split into individual `zf1s/zend-*` packages.
