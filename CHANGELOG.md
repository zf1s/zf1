## Changelog:

### 1.15.5 - 2024-04-18
- zend-controller
  - fixes "Creation of dynamic property ::$ajaxable is deprecated" on PHP 8.2+ when using `ContextSwitch` helper ([#196])

[#196]: https://github.com/zf1s/zf1/pull/196

### 1.15.4 - 2024-02-25
- zend-application
  - update dependencies according to usage ([#185])
- zend-auth
  - update dependencies according to usage ([#186])
- zend-controller
  - update dependencies according to usage ([#188])
- zend-form
  - fix instantiating filters with options via `addFilter()` in php 8.0+ ([#194])
- zend-json
  - update dependencies according to usage ([#189])
- zend-layout
  - update dependencies according to usage ([#190])
- zend-mail
  - update dependencies according to usage ([#191])
- zend-view
  - updated `HeadScript` to allow for proper html5 syntax creation ([#103])

[#103]: https://github.com/zf1s/zf1/pull/103
[#185]: https://github.com/zf1s/zf1/pull/185
[#186]: https://github.com/zf1s/zf1/pull/186
[#188]: https://github.com/zf1s/zf1/pull/188
[#189]: https://github.com/zf1s/zf1/pull/189
[#190]: https://github.com/zf1s/zf1/pull/190
[#191]: https://github.com/zf1s/zf1/pull/191
[#194]: https://github.com/zf1s/zf1/pull/194

### 1.15.3 - 2024-01-04
- general: php 8.2 & 8.3 compatibility
  - php 8.3 compatibility fixes ([#182])
  - php 8.2 compatibility fixes ([#183]), including:
    - add missing class properties, to address `Creation of dynamic property {x} is deprecated` deprecation messages + more fixes ([#170])
    - allow dynamic props in `Zend_Form`, `Zend_Form_Element` and `Zend_View_Abstract` ([#180])
    - remaining compatibility fixes ([#181])
- zend-controller
  - make zend-filter dependency required ([#171])
- zend-uri
  - update required and suggested dependencies according to usage ([#177])
- zend-validate
  - update required and suggested dependencies according to usage ([#179])
- zend-view
  - update required and suggested dependencies according to usage ([#178])
- general: ci & tests
  - github weekly workflow run ([#173])
  - zend-ldap: fix failing tests due to changes in `bitnami/openldap:2.5` ([#172])

[#170]: https://github.com/zf1s/zf1/pull/170
[#171]: https://github.com/zf1s/zf1/pull/171
[#172]: https://github.com/zf1s/zf1/pull/172
[#173]: https://github.com/zf1s/zf1/pull/173
[#177]: https://github.com/zf1s/zf1/pull/177
[#178]: https://github.com/zf1s/zf1/pull/178
[#179]: https://github.com/zf1s/zf1/pull/179
[#180]: https://github.com/zf1s/zf1/pull/180
[#181]: https://github.com/zf1s/zf1/pull/181
[#182]: https://github.com/zf1s/zf1/pull/182
[#183]: https://github.com/zf1s/zf1/pull/183

### 1.15.2 - 2023-02-19
- zend-ldap
  - php 8.1 compatibility fixes ([#159])
- zend-session
  - `Zend_Session_SaveHandler_DbTable` - fix "Failed to write session data using user defined save handler" warning on php 7+ ([#164])

[#159]: https://github.com/zf1s/zf1/pull/159
[#164]: https://github.com/zf1s/zf1/pull/164

### 1.15.1 - 2022-12-12
- general
  - php 8.1 compatibility fixes ([#149])
  - php 8.1 compat: `ReturnTypeWillChange` Attribute ([#147])
  - require php extensions in dev only - avoid composer warnings for missing extensions when installing `zf1s/zf1` package ([#136])
  - add missing `@throws` annotations ([#140])
  - fix psr-0 autoloading issues ([#135])
  - clean up `zf1s/zf1` package by ignoring unwanted files in export-ignore ([#134])
- zend-amf
  - fix for php 8.1+ keep the order of properties when they are being serialized same as PHP <8.1 ([#149])
  - phpdoc: fix `Zend_Amf_Server_Response` class name, should be `Zend_Amf_Response` ([#137])
- zend-console-getopt
  - Fix `str_split('')` logic to keep same as PHP before 8.2 on PHP 8.2 ([#143])
- zend-date
  - properly calculate sunrise, sunset and twilight times ([#151])
- zend-db
  - fix MySQLi adapter after changing default reporting mode by PHP 8.1 ([#156], [#158])
- zend-openid
  -  fix for `Zend_OpenId_Consumer_Storage_File` when symlinks are not used (i.e. on windows) ([#148])
- zend-progressbar
  - fix "stty: 'standard input': Inappropriate ioctl for device" spam ([#155])
- zend-server
  - fix issues with `Zend_Server_Reflection_Method` ([#149])
- zend-timesync
  - fix ntp time sync ([#153])
- general: CI
  - enable php 8.1/8.2 builds ([#129])
  - fix CI warnings: Node.js 12 actions are deprecated ([#139])
  - utilize continue-on-error for 'experimental' flag ([#142])
  - enable SQLite tests ([#157])
- general: tests
  - fix running tests on windows with composer v2 ([#150])
  - [zend-loader] fix failing Zend_Loader_PluginLoaderTest on windows ([#154])
  - [zend-paginator] use a temporary fixture test file for unit testing that git ignores ([#160])

[#129]: https://github.com/zf1s/zf1/pull/129
[#134]: https://github.com/zf1s/zf1/pull/134
[#135]: https://github.com/zf1s/zf1/pull/135
[#136]: https://github.com/zf1s/zf1/pull/136
[#137]: https://github.com/zf1s/zf1/pull/137
[#139]: https://github.com/zf1s/zf1/pull/139
[#140]: https://github.com/zf1s/zf1/pull/140
[#142]: https://github.com/zf1s/zf1/pull/142
[#143]: https://github.com/zf1s/zf1/pull/143
[#147]: https://github.com/zf1s/zf1/pull/147
[#148]: https://github.com/zf1s/zf1/pull/148
[#149]: https://github.com/zf1s/zf1/pull/149
[#150]: https://github.com/zf1s/zf1/pull/150
[#151]: https://github.com/zf1s/zf1/pull/151
[#153]: https://github.com/zf1s/zf1/pull/153
[#154]: https://github.com/zf1s/zf1/pull/154
[#155]: https://github.com/zf1s/zf1/pull/155
[#156]: https://github.com/zf1s/zf1/pull/156
[#157]: https://github.com/zf1s/zf1/pull/157
[#158]: https://github.com/zf1s/zf1/pull/158
[#160]: https://github.com/zf1s/zf1/pull/160

### 1.15.0 - 2022-10-05
- zend-loader
  - overhaul of zend-loader and autoloader done again ([#116])
  - continues work done initially in [#1] / [76477fb]
  - potential breaking changes:
    > Introduced `Zend_Loader_Exception_FileNotFoundException` and `Zend_Loader_Exception_ClassNotFoundException`
    > 
    > Instead of throwing `Zend_Exception` in `Zend_Loader::loadClass()` with a generic message `File \"$file\" does not exist or class \"$class\" was not found in the file`
    > - `Zend_Loader_Exception_FileNotFoundException` will be thrown with message `File "$file" could not be found within configured include_path`. or
    > - `Zend_Loader_Exception_ClassNotFoundException` with message `Class "$class" was not found in the file "$file".`, in their respective cases.
    
    > Not suppressing loading classes with `@` suppressor by default anymore.
    > 
    > Regular warnings/errors coming from a loaded file should be visible, otherwise it might be very confusing for devs
    >
    > At the same time though, `Zend_Loader` will not emit warnings when checking for files if they exist, by default. Added `isReadable` check inside `loadFile` before `include`/`include_once`.
    > 
    > There might be a performance hit, but it should be okay when most of the autoloading is handled by composer autoloader.
    > 
    > This change should finally allow seamless integration of `Zend_Loader` with composer autoloader, without any warnings.
  - fixed issues with loading custom Translate/File_Transfer/Filter adapters
  - for more details see [#116]
- zend-session
  - added "session.cookie_samesite" option ([#126])
- zend-validate
  - hostname: update TLDs (Version 2022100300) ([#104])
  - hostname: allow underscores in subdomain parts ([#131])
- general: docblock annotations
  - fixed parameter annotation for `Zend_XmlRpc_Fault::setMessage()` ([#117])
  - fixed parameter annotation for `Zend_Db_Table_Select::setIntegrityCheck()` ([#119])
  - fixed wrong return-type in `Zend_Form_Element::removeValidator()` ([#121])
  - fixed annotations for `Zend_Controller_Router_Route_Regex::__construct()` ([#123])
- general: publish the whole `zf1s/zf1` package on packagist ([#133])
  - the whole framework package can be now installed at once with `composer require zf1s/zf1` to easy up the transition period,
    but please keep in mind the recommended approach is to identify and install only the packages you need.
  
[#1]: https://github.com/zf1s/zf1/pull/1
[#104]: https://github.com/zf1s/zf1/pull/104
[#116]: https://github.com/zf1s/zf1/pull/116
[#117]: https://github.com/zf1s/zf1/pull/117
[#119]: https://github.com/zf1s/zf1/pull/119
[#121]: https://github.com/zf1s/zf1/pull/121
[#123]: https://github.com/zf1s/zf1/pull/123
[#126]: https://github.com/zf1s/zf1/pull/126
[#131]: https://github.com/zf1s/zf1/pull/131
[#133]: https://github.com/zf1s/zf1/pull/133
[76477fb]: https://github.com/zf1s/zf1/commit/76477fbe00a198ef4376ea38c46df3960c574af8

### 1.14.0 - 2021-10-01
- general: php 8.0 compatibility ([#51])
  - Remove default values from method signatures ([#78])
  - Fix iterators usage ([#82])
  - Fix Zend_Form_ElementTest ([#81])
  - Enforce types for PHP 8.0 ([#80])
  - Fix vsprintf TypeError for php 8.0 ([#79])
  - Fixed reflection deprecations for php 8.0 ([#76])
  - Make Zend_Validate_Date work as expected under PHP 8.0 ([#75])
  - Zend_Session Fix error handler for PHP8 usage ([#99])
  - Remove openssl deprecation for php 8.0 ([#73])
  - Remove libxml deprecations for php 8.0 ([#65])
  - Missing default values caused errorHandlerIgnore to fail under PHP 8.0 ([#63])
  - Replace version_compare on PHP_VERSION with PHP_VERSION_ID check ([#53])
  - Do not check get_magic_quotes_gpc value for php 5.4+ ([#56])
  - Remove usage of $php_errormsg ([#42])
  - Drop tests covering php older than 5.3.3 ([#55])
  - Drop code supporting php older than 5.3.3 ([#54])
  - Add guard on fclose in Zend_Mail_Storage_Mbox ([#70])
  - Fix Zend_Db issues with php8 ([#106])
  - php8 Zend_Log compatibility fixes ([#107])
  - [zend-paginator] prevent fatal error on php8 ([#109])
  - [zend-queue] prevent TypeError on md5 of non-string message ([#110])
  - [zend-loader] refactor broken resolvePharParentPath static method ([#111])
- zend-acl
  - Increase Performance in unsetting rules in ACL ([#60])
- zend-application
  - fix autoloading of Useragent class ([#113])
- zend-db
  - Fix PHPDoc @return statement for Zend_Db_Select::query() ([#95])
  - Fix PHPDoc typings on Zend_Db_Table_Rowset_Abstract::current ([#98])
- zend-exception
  - allow Throwable in $previous ([#112])
- zend-paginator
  - fixed phpdoc typo ([#86])
- zend-queue
  - Adding support for durable subscribers and persistent message sending ([#105])
- zend-view
  - fix Zend_View_Abstract::__get() phpdoc ([#87])
- Security
  - Backport of fix for CVE-2021-3007 in Zend_Http_Response_Stream ([#43])
- Infrastructure
  - Restore locales before calling test assertions ([#45])
  - Enable "fail-fast" env for setup-php ([#52])
  - Move MySQL testing from Travis to GitHub Actions ([#49])
  - Use ubuntu-16.04 by default for faster setup-php ([#72])
  - Add php 8.0 to GitHub Actions ignoring its errors ([#59])
  - Allow newer php-parallel-lint/php-parallel-lint for php 8.0 ([#58])
  - Move composer.json validate of sub-packages to GitHub Actions ([#40])
  - Use parallel-lint for GitHub actions ([#50])
  - Move memcache testing from Travis to GitHub Actions ([#47])
  - GitHub Actions: Install composer dependencies ([#41])
  - Use staabm/annotate-pull-request-from-checkstyle to report violatons in GitHub ([#66])
  - Use gnu parallel for validating composer.json ([#84])
  - CI: Switch to Ubuntu 20.04 ([#94])
  - ditch travis in favor of gha workflow ([#108])
  - enable postgres on gha ([#114])
  - use mysql v5.7 in gha ([#115])

[#40]: https://github.com/zf1s/zf1/pull/40
[#41]: https://github.com/zf1s/zf1/pull/41
[#42]: https://github.com/zf1s/zf1/pull/42
[#43]: https://github.com/zf1s/zf1/pull/43
[#45]: https://github.com/zf1s/zf1/pull/45
[#47]: https://github.com/zf1s/zf1/pull/47
[#49]: https://github.com/zf1s/zf1/pull/49
[#50]: https://github.com/zf1s/zf1/pull/50
[#51]: https://github.com/zf1s/zf1/issues/51
[#52]: https://github.com/zf1s/zf1/pull/52
[#53]: https://github.com/zf1s/zf1/pull/53
[#54]: https://github.com/zf1s/zf1/pull/54
[#55]: https://github.com/zf1s/zf1/pull/55
[#56]: https://github.com/zf1s/zf1/pull/56
[#58]: https://github.com/zf1s/zf1/pull/58
[#59]: https://github.com/zf1s/zf1/pull/59
[#60]: https://github.com/zf1s/zf1/pull/60
[#63]: https://github.com/zf1s/zf1/pull/63
[#65]: https://github.com/zf1s/zf1/pull/65
[#66]: https://github.com/zf1s/zf1/pull/66
[#70]: https://github.com/zf1s/zf1/pull/70
[#72]: https://github.com/zf1s/zf1/pull/72
[#73]: https://github.com/zf1s/zf1/pull/73
[#75]: https://github.com/zf1s/zf1/pull/75
[#76]: https://github.com/zf1s/zf1/pull/76
[#78]: https://github.com/zf1s/zf1/pull/78
[#79]: https://github.com/zf1s/zf1/pull/79
[#80]: https://github.com/zf1s/zf1/pull/80
[#81]: https://github.com/zf1s/zf1/pull/81
[#82]: https://github.com/zf1s/zf1/pull/82
[#84]: https://github.com/zf1s/zf1/pull/84
[#86]: https://github.com/zf1s/zf1/pull/86
[#87]: https://github.com/zf1s/zf1/pull/87
[#94]: https://github.com/zf1s/zf1/pull/94
[#95]: https://github.com/zf1s/zf1/pull/95
[#98]: https://github.com/zf1s/zf1/pull/98
[#99]: https://github.com/zf1s/zf1/pull/99
[#105]: https://github.com/zf1s/zf1/pull/105
[#106]: https://github.com/zf1s/zf1/pull/106
[#107]: https://github.com/zf1s/zf1/pull/107
[#108]: https://github.com/zf1s/zf1/pull/108
[#109]: https://github.com/zf1s/zf1/pull/109
[#110]: https://github.com/zf1s/zf1/pull/110
[#111]: https://github.com/zf1s/zf1/pull/111
[#112]: https://github.com/zf1s/zf1/pull/112
[#113]: https://github.com/zf1s/zf1/pull/113
[#114]: https://github.com/zf1s/zf1/pull/114
[#115]: https://github.com/zf1s/zf1/pull/115

### 1.13.4 - 2020-11-23
- zend-db
  - Fix Zend_Db_Adapter_Pdo_Pgsql being broken on Postgres 12 that removed d.adsrc ([#29])
- zend-xml
  - fix version comparison operator ([#26])
  - php 8 compatibility: wrap deprecated libxml_disable_entity_loader() ([#27])
  
[#26]: https://github.com/zf1s/zf1/pull/26
[#27]: https://github.com/zf1s/zf1/pull/27
[#29]: https://github.com/zf1s/zf1/pull/29
  
### 1.13.3 - 2020-08-26
- zend-locale
  - Add Croatia to the European Union ([#21](https://github.com/zf1s/zf1/pull/21))
- zend-validate
  - fixed Zend_Validate_Barcode_IntelligentMail class name for psr-0 autoloading compatibility with composer 2.0 ([#24](https://github.com/zf1s/zf1/pull/24))

### 1.13.2 - 2020-05-25
- zend-search-lucene
  - fixed "Trying to access array offset on value of type int" when passed a non-string value to `Zend_Search_Lucene_Index_Term` ([#19](https://github.com/zf1s/zf1/pull/19))
- zend-service-rackspace
  - restore back `array_key_exists` in place of `isset` - reverted unnecessary changes from [#16](https://github.com/zf1s/zf1/pull/16/files#diff-7d8cdc4dbd5afcd88fca225eaf9a353f)

### 1.13.1 - 2019-12-16
- general
  - php 7.4 compatibility ([#16](https://github.com/zf1s/zf1/pull/16))
- zend-crypt
  - fixed Zend_Crypt_Math::rand() method returning random bytes when random integer was expected (removed broken /dev/urandom implementation) ([#16](https://github.com/zf1s/zf1/pull/16))
- zend-file-transfer
  - adjust setAdapter method for compatibility with composer autoloader ([#12](https://github.com/zf1s/zf1/pull/12))
  - now suggests adding zend-validate as http adapter requires it ([#12](https://github.com/zf1s/zf1/pull/12))
- zend-view
  - fix @method annotations in Zend_View_Helper_Navigation ([#13](https://github.com/zf1s/zf1/pull/13))

### 1.13.0 - 2019-05-28
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
    

### 1.12.22 - 2019-05-07
- zend-loader
    - make the introduced performance optimization for PluginLoader optional ([zf1s/zend-loader#4](https://github.com/zf1s/zend-loader/pull/4))
- zend-view
    - php 5.3 compatibility fixes ([zf1s/zend-view#2](https://github.com/zf1s/zend-view/pull/4))

### 1.12.21 - various dates
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

### 1.12.20 - 2016-10-23
- Final release of the original project, split into individual `zf1s/zend-*` packages.
