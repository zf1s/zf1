## Changelog:

### 1.13.3
- zend-locale
  - Add Croatia to the European Union ([#21](https://github.com/zf1s/zf1/pull/21))
- zend-validate
  - fixed Zend_Validate_Barcode_IntelligentMail class name for psr-0 autoloading compatibility with composer 2.0 ([#24](https://github.com/zf1s/zf1/pull/24))

### 1.13.2
- zend-search-lucene
  - fixed "Trying to access array offset on value of type int" when passed a non-string value to `Zend_Search_Lucene_Index_Term` ([#19](https://github.com/zf1s/zf1/pull/19))
- zend-service-rackspace
  - restore back `array_key_exists` in place of `isset` - reverted unnecessary changes from [#16](https://github.com/zf1s/zf1/pull/16/files#diff-7d8cdc4dbd5afcd88fca225eaf9a353f)

### 1.13.1
- general
  - php 7.4 compatibility ([#16](https://github.com/zf1s/zf1/pull/16))
- zend-crypt
  - fixed Zend_Crypt_Math::rand() method returning random bytes when random integer was expected (removed broken /dev/urandom implementation) ([#16](https://github.com/zf1s/zf1/pull/16))
- zend-file-transfer
  - adjust setAdapter method for compatibility with composer autoloader ([#12](https://github.com/zf1s/zf1/pull/12))
  - now suggests adding zend-validate as http adapter requires it ([#12](https://github.com/zf1s/zf1/pull/12))
- zend-view
  - fix @method annotations in Zend_View_Helper_Navigation ([#13](https://github.com/zf1s/zf1/pull/13))

### 1.13.0
- general
  - re-introduce original tests suite and adjust it to run on php 5.3-7.3 (using modded [zf1s/phpunit](https://github.com/zf1s/phpunit))
  - tests adjustments
    - composer autoloader and zend loader compatibility fixes
    - adjustments for changed folders structure of framework packages
    - Zend_DebugTest fixes backported from [diablomedia/zf1@ba8cf7](https://github.com/diablomedia/zf1/commit/ba8cf7aaf6542b7aadee79146709302d65e85bdd#diff-187c3c3644f1fbbe71be9a261283f95a) (thanks!)
    - fix restoring initial locale properties and HTTP_ACCEPT_LANGUAGE in Zend_LocaleTest
    - silence 'tempnam(): file created in the system's temporary directory' errors in zend-config writer tests
    - fix property exists assertion in zend-amf test
    - skip 'resources' tests - those i18n resources were failing / unfinished on original repo
    - skip tests using mcrypt (deprecated since php 7.1)
    - Zend_VersionTest fix
    - commit test files for Zend_Filter_Compress for deterministic results
    - fix doctype inconsistencies when testing rendered html in Zend_Form_Decorator_ViewHelperTest
    - fixed tests sensitive to line endings in test files (expected CRLF)
    - BC Zend_Amf_RequestTest fix from [diablomedia/zf1@ba8cf7](https://github.com/diablomedia/zf1/commit/ba8cf7aaf6542b7aadee79146709302d65e85bdd#diff-68d7be0100c43bc2df55377c9d27cd05) (thanks!)
    - tell git do not touch line-endings in zend-http-response test files - tests rely on mixed line-endings in raw responses
    - portability adjustments - mainly for windows & winux (wsl)
    - added missing stdlib and xml tests to Zend/AllTests
  - composer: fill ext-* dependencies ([#6](https://github.com/zf1s/zf1/pull/6))
- zend-cloud
  - fix lettercase of loaded class Zend_Service_Amazon_Ec2_Availabilityzones
- zend-codegenerator
  - fix output of Zend_CodeGenerator_Php_File
  - restore commented require_once
- zend-config
  - fix regression in reading yaml config - introduced in 1.12.21 ([zf1s/zend-config@544edd](https://github.com/zf1s/zend-config/commit/544edd9815ac050745a59798ff9d713df3947c6b))
  - xml: restore error handler in case of exception
- zend-date
  - borrowed a few fixes fixes for zend-date tests from [diablomedia/zf1-date](https://github.com/diablomedia/zf1-date) (thanks!)
  - Fixing DateTest on servers with more recent timezone files [diablomedia/zf1-date@ef47f4](https://github.com/diablomedia/zf1-date/commit/ef47f4f3bde9c175c32e4b73ac779bd88a33799e)
  - sunrise/sunset calculation differences in php >= 7.2 [diablomedia/zf1-date@f69aca](https://github.com/diablomedia/zf1-date/commit/f69acaf6bc563af898144abd0a8aa1bbbf0e0308)
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
- zend-locale
  - format+math+phpmath overhaul
    - untangled normalization - removed when value is expected in already normalized form
    - fix for issues with locales where e.g. thousand separator is a dot (e.g. german), i.a. [zendframework/zf1#706](https://github.com/zendframework/zf1/issues/706)
    - apart from zend-currency, it will also have a big impact on zend-measure package (fixing calculations)
  - iconv_substr php 7.0.11+ compatibility fixes - borrowed from [axot/zf1@4c6400](https://github.com/axot/zf1/commit/4c6400ad28f1f7a3448492f9d444aff1080c6) (thanks!)
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
  - fix regression in loading values introduced in 1.12.21 [zf1s/zend-xmlrpc@ea5a62](https://github.com/zf1s/zend-xmlrpc/commit/ea5a62283b03d9226c44c5f0ec1442158c1f780e)
  + restore php 5.3 compatibility
    

### 1.12.22
- zend-loader
    - make the introduced performance optimization for PluginLoader optional ([zf1s/zend-loader#4](https://github.com/zf1s/zend-loader/pull/4))
- zend-view
    - php 5.3 compatibility fixes ([zf1s/zend-view#2](https://github.com/zf1s/zend-view/pull/4))

### 1.12.21
- zend-application
    - disable require once call and the autoloader ([zf1s/zend-application#1](https://github.com/zf1s/zend-application/pull/1))
- zend-cache
    - php 7.2 compatibility fixes
- zend-config
    - removed bad usage of count and improved string parsing ([zf1s/zend-config#1](https://github.com/zf1s/zend-config/pull/1))
    - php 7.2 compatibility fixes
    - added: retrieving nested value by `->get('value.value2.value3')]`
- zend-controller
    - composer autoloader compatibility fixes
- zend-date
    - Zend_Date::setTime fix for DST change [zendframework/zf1#682](https://github.com/zendframework/zf1/issues/682)
- zend-db
    - Fixed Warning: count(): Parameter must be an array or an object that implements Countable when $keyValuesCount is not an array ([zf1s/zend-db#1](https://github.com/zf1s/zend-db/pull/1))
- zend-feed
    - removed uses of deprecated function create_function() ([zf1s/zend-feed#1](https://github.com/zf1s/zend-feed/pull/1))
- zend-filter
    - fixed handling of namespaced classes
- zend-form
    - php 7.3 compatibility fixes
- zend-http
    - Fix for "Notice: Undefined index: detail", example user-agent: "LightspeedSystemsCrawler Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US"
    - php 7.2 compatibility fixes
- zend-loader
    - Drop support for ZendX pseudo namespace ([zf1s/zend-loader#1](https://github.com/zf1s/zend-loader/pull/1))
    - optimize loading plugins - use only composer autoloader
- zend-json
    - merged [zendframework/zf1#680](https://github.com/zendframework/zf1/pull/680) to fix "Zend_Json::decode null or empty string throw Zend_Json_Exception on PHP7"
- zend-rest
    - updated assemble signature ([zf1s/zend-rest#1](https://github.com/zf1s/zend-rest/pull/1))
- zend-validate
    - Added null check to avoid errors with passing null to a count parameter ([zf1s/zend-validate#1](https://github.com/zf1s/zend-validate/pull/1))
    - fixed invalid assignment $this->_messages = null causing "Warning: count(): Parameter must be an array or an object that implements Countable"
    - fixed handling of namespaced classes
- zend-view
    - fix for missing combine() variable $extras, causing notice on php 7.3
- zend-xmlrpc
    - php 7.2 compatibility fixes

### 1.12.20
- Final release of the original project, split into individual `zf1s/zend-*` packages.
