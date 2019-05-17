## Changelog:

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
