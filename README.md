# Monorepo for zf1s (Zend Framework 1) packages

[![Build Status](https://travis-ci.com/zf1s/zf1.svg?branch=master)](https://travis-ci.com/zf1s/zf1)

This is a monorepo of a fork of Zend Framework 1, made after it's reached its EOL.
All original framework's components have been split into individual packages, which can be installed separately with `composer`, e.g.
```
composer require zf1s/zend-*
```
where `*` may be one of:
[acl](https://github.com/zf1s/zend-acl),
[amf](https://github.com/zf1s/zend-amf),
[application](https://github.com/zf1s/zend-application),
[auth](https://github.com/zf1s/zend-auth),
[barcode](https://github.com/zf1s/zend-barcode),
[cache](https://github.com/zf1s/zend-cache),
[captcha](https://github.com/zf1s/zend-captcha),
[cloud](https://github.com/zf1s/zend-cloud),
[codegenerator](https://github.com/zf1s/zend-codegenerator),
[config](https://github.com/zf1s/zend-config),
[console-getopt](https://github.com/zf1s/zend-console-getopt),
[controller](https://github.com/zf1s/zend-controller),
[crypt](https://github.com/zf1s/zend-crypt),
[currency](https://github.com/zf1s/zend-currency),
[date](https://github.com/zf1s/zend-date),
[db](https://github.com/zf1s/zend-db),
[debug](https://github.com/zf1s/zend-debug),
[dojo](https://github.com/zf1s/zend-dojo),
[dom](https://github.com/zf1s/zend-dom),
[eventmanager](https://github.com/zf1s/zend-eventmanager),
[exception](https://github.com/zf1s/zend-exception),
[feed](https://github.com/zf1s/zend-feed),
[file](https://github.com/zf1s/zend-file),
[file-transfer](https://github.com/zf1s/zend-file-transfer),
[filter](https://github.com/zf1s/zend-filter),
[form](https://github.com/zf1s/zend-form),
[gdata](https://github.com/zf1s/zend-gdata),
[http](https://github.com/zf1s/zend-http),
[json](https://github.com/zf1s/zend-json),
[layout](https://github.com/zf1s/zend-layout),
[ldap](https://github.com/zf1s/zend-ldap),
[loader](https://github.com/zf1s/zend-loader),
[locale](https://github.com/zf1s/zend-locale),
[log](https://github.com/zf1s/zend-log),
[mail](https://github.com/zf1s/zend-mail),
[markup](https://github.com/zf1s/zend-markup),
[measure](https://github.com/zf1s/zend-measure),
[memory](https://github.com/zf1s/zend-memory),
[mime](https://github.com/zf1s/zend-mime),
[mobile](https://github.com/zf1s/zend-mobile),
[navigation](https://github.com/zf1s/zend-navigation),
[oauth](https://github.com/zf1s/zend-oauth),
[openid](https://github.com/zf1s/zend-openid),
[paginator](https://github.com/zf1s/zend-paginator),
[pdf](https://github.com/zf1s/zend-pdf),
[progressbar](https://github.com/zf1s/zend-progressbar),
[queue](https://github.com/zf1s/zend-queue),
[reflection](https://github.com/zf1s/zend-reflection),
[registry](https://github.com/zf1s/zend-registry),
[rest](https://github.com/zf1s/zend-rest),
[search](https://github.com/zf1s/zend-search),
[search-lucene](https://github.com/zf1s/zend-search-lucene),
[serializer](https://github.com/zf1s/zend-serializer),
[server](https://github.com/zf1s/zend-server),
[service](https://github.com/zf1s/zend-service),
[service-akismet](https://github.com/zf1s/zend-service-akismet),
[service-amazon](https://github.com/zf1s/zend-service-amazon),
[service-audioscrobbler](https://github.com/zf1s/zend-service-audioscrobbler),
[service-console](https://github.com/zf1s/zend-service-console),
[service-delicious](https://github.com/zf1s/zend-service-delicious),
[service-ebay](https://github.com/zf1s/zend-service-ebay),
[service-flickr](https://github.com/zf1s/zend-service-flickr),
[service-livedocx](https://github.com/zf1s/zend-service-livedocx),
[service-rackspace](https://github.com/zf1s/zend-service-rackspace),
[service-recaptcha](https://github.com/zf1s/zend-service-recaptcha),
[service-shorturl](https://github.com/zf1s/zend-service-shorturl),
[service-slideshare](https://github.com/zf1s/zend-service-slideshare),
[service-strikeiron](https://github.com/zf1s/zend-service-strikeiron),
[service-twitter](https://github.com/zf1s/zend-service-twitter),
[service-windowsazure](https://github.com/zf1s/zend-service-windowsazure),
[service-yahoo](https://github.com/zf1s/zend-service-yahoo),
[session](https://github.com/zf1s/zend-session),
[soap](https://github.com/zf1s/zend-soap),
[stdlib](https://github.com/zf1s/zend-stdlib),
[tag](https://github.com/zf1s/zend-tag),
[test](https://github.com/zf1s/zend-test),
[text](https://github.com/zf1s/zend-text),
[timesync](https://github.com/zf1s/zend-timesync),
[tool](https://github.com/zf1s/zend-tool),
[translate](https://github.com/zf1s/zend-translate),
[uri](https://github.com/zf1s/zend-uri),
[validate](https://github.com/zf1s/zend-validate),
[version](https://github.com/zf1s/zend-version),
[view](https://github.com/zf1s/zend-view),
[wildfire](https://github.com/zf1s/zend-wildfire),
[xml](https://github.com/zf1s/zend-xml),
[xmlrpc](https://github.com/zf1s/zend-xmlrpc).

These packages will be maintained as long as we're using them, mainly just to keep it all working on new versions of PHP as they're released.
Currently everything should be compatible with **PHP 5.3-7.4**. _5.2 support is dropped._

They may also contain some fixes, either for long-standing bugs, which haven't made their way into zf1 official repo before EOL, or newly found ones
and (backwards compatible) adjustments (optimisations for composer autoloader mostly). Maybe even one or two new features.

### Changelog: [here](CHANGELOG.md)
Original README: [click](README.orig.md)



