<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/packages/zend-acl',
        __DIR__ . '/packages/zend-amf',
        __DIR__ . '/packages/zend-application',
        __DIR__ . '/packages/zend-auth',
        __DIR__ . '/packages/zend-barcode',
        __DIR__ . '/packages/zend-cache',
        __DIR__ . '/packages/zend-captcha',
        __DIR__ . '/packages/zend-cloud',
        __DIR__ . '/packages/zend-codegenerator',
        __DIR__ . '/packages/zend-config',
        __DIR__ . '/packages/zend-console-getopt',
        __DIR__ . '/packages/zend-controller',
        __DIR__ . '/packages/zend-crypt',
        __DIR__ . '/packages/zend-currency',
        __DIR__ . '/packages/zend-date',
        __DIR__ . '/packages/zend-db',
        __DIR__ . '/packages/zend-debug',
        __DIR__ . '/packages/zend-dojo',
        __DIR__ . '/packages/zend-dom',
        __DIR__ . '/packages/zend-eventmanager',
        __DIR__ . '/packages/zend-exception',
        __DIR__ . '/packages/zend-feed',
        __DIR__ . '/packages/zend-file',
        __DIR__ . '/packages/zend-file-transfer',
        __DIR__ . '/packages/zend-filter',
        __DIR__ . '/packages/zend-form',
        __DIR__ . '/packages/zend-gdata',
        __DIR__ . '/packages/zend-http',
        __DIR__ . '/packages/zend-json',
        __DIR__ . '/packages/zend-layout',
        __DIR__ . '/packages/zend-ldap',
        __DIR__ . '/packages/zend-loader',
        __DIR__ . '/packages/zend-locale',
        __DIR__ . '/packages/zend-log',
        __DIR__ . '/packages/zend-mail',
        __DIR__ . '/packages/zend-markup',
        __DIR__ . '/packages/zend-measure',
        __DIR__ . '/packages/zend-memory',
        __DIR__ . '/packages/zend-mime',
        __DIR__ . '/packages/zend-mobile',
        __DIR__ . '/packages/zend-navigation',
        __DIR__ . '/packages/zend-oauth',
        __DIR__ . '/packages/zend-openid',
        __DIR__ . '/packages/zend-paginator',
        __DIR__ . '/packages/zend-pdf',
        __DIR__ . '/packages/zend-progressbar',
        __DIR__ . '/packages/zend-queue',
        __DIR__ . '/packages/zend-reflection',
        __DIR__ . '/packages/zend-registry',
        __DIR__ . '/packages/zend-rest',
        __DIR__ . '/packages/zend-search',
        __DIR__ . '/packages/zend-search-lucene',
        __DIR__ . '/packages/zend-serializer',
        __DIR__ . '/packages/zend-server',
        __DIR__ . '/packages/zend-session',
        __DIR__ . '/packages/zend-soap',
        __DIR__ . '/packages/zend-stdlib',
        __DIR__ . '/packages/zend-tag',
        __DIR__ . '/packages/zend-text',
        __DIR__ . '/packages/zend-timesync',
        __DIR__ . '/packages/zend-tool',
        __DIR__ . '/packages/zend-translate',
        __DIR__ . '/packages/zend-uri',
        __DIR__ . '/packages/zend-validate',
        __DIR__ . '/packages/zend-version',
        __DIR__ . '/packages/zend-view',
        __DIR__ . '/packages/zend-wildfire',
        __DIR__ . '/packages/zend-xml',
        __DIR__ . '/packages/zend-xmlrpc',
        __DIR__ . '/packages/zend-service',
        __DIR__ . '/packages/zend-service-akismet',
        __DIR__ . '/packages/zend-service-amazon',
        __DIR__ . '/packages/zend-service-audioscrobbler',
        __DIR__ . '/packages/zend-service-console',
        __DIR__ . '/packages/zend-service-delicious',
        __DIR__ . '/packages/zend-service-ebay',
        __DIR__ . '/packages/zend-service-flickr',
        __DIR__ . '/packages/zend-service-livedocx',
        __DIR__ . '/packages/zend-service-rackspace',
        __DIR__ . '/packages/zend-service-recaptcha',
        __DIR__ . '/packages/zend-service-shorturl',
        __DIR__ . '/packages/zend-service-slideshare',
        __DIR__ . '/packages/zend-service-strikeiron',
        __DIR__ . '/packages/zend-service-twitter',
        __DIR__ . '/packages/zend-service-yahoo',

        //__DIR__ . '/packages/zend-test',
        //__DIR__ . '/packages/zend-service-windowsazure',
    ]);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_81);

    // Define what rule sets will be applied
    //$containerConfigurator->import(LevelSetList::UP_TO_PHP_53);



    // get services (needed for register a single rule)
     $services = $containerConfigurator->services();

    // register a single rule
     $services->set(ReturnTypeWillChangeRector::class);
};
