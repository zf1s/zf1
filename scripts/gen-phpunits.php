<?php

$map = [
    'zend-acl' => ['Zend/Acl/AllTests'],
    'zend-amf' => ['Zend/Amf/AllTests'],
    'zend-application' => ['Zend/Application/AllTests'],
    'zend-auth' => ['Zend/AuthTest', 'Zend/Auth/AllTests'],
    'zend-barcode' => ['Zend/Barcode/AllTests'],
    'zend-cache' => ['Zend/Cache/AllTests'],
    'zend-captcha' => ['Zend/Captcha/AllTests'],
    'zend-cloud' => ['Zend/Cloud/AllTests'],
    'zend-codegenerator' => ['Zend/CodeGenerator/Php/AllTests'],
    'zend-config' => ['Zend/ConfigTest', 'Zend/Config/AllTests'],
    'zend-console-getopt' => ['Zend/Console/GetoptTest'],
    'zend-controller' => ['Zend/Controller/AllTests'],
    'zend-crypt' => ['Zend/Crypt/AllTests'],
    'zend-currency' => ['Zend/CurrencyTest'],
    'zend-date' => ['Zend/DateTest', 'Zend/Date/AllTests'],
    'zend-db' => ['Zend/Db/AllTests'],
    'zend-debug' => ['Zend/DebugTest'],
    'zend-dojo' => ['Zend/Dojo/AllTests'],
    'zend-dom' => ['Zend/Dom/AllTests'],
    'zend-eventmanager' => ['Zend/EventManager/AllTests'],
    'zend-exception' => ['Zend/ExceptionTest'],
    'zend-feed' => ['Zend/Feed/AllTests'],
    'zend-file' => ['Zend/File/Zend_File_ClassFileLocatorTest'],
    'zend-file-transfer' => ['Zend/File/Transfer/AllTests'],
    'zend-filter' => ['Zend/FilterTest', 'Zend/Filter/AllTests'],
    'zend-form' => ['Zend/Form/AllTests'],
    'zend-gdata' => ['Zend/Gdata/AllTests'],
    'zend-http' => ['Zend/Http/AllTests'],
    'zend-json' => ['Zend/JsonTest', 'Zend/Json/AllTests'],
    'zend-layout' => ['Zend/Layout/AllTests'],
    'zend-ldap' => ['Zend/Ldap/AllTests'],
    'zend-loader' => ['Zend/LoaderTest', 'Zend/Loader/AllTests'],
    'zend-locale' => ['Zend/LocaleTest', 'Zend/Locale/AllTests'],
    'zend-log' => ['Zend/Log/AllTests'],
    'zend-mail' => ['Zend/Mail/AllTests'],
    'zend-markup' => ['Zend/Markup/AllTests'],
    'zend-measure' => ['Zend/Measure/AllTests'],
    'zend-memory' => ['Zend/Memory/AllTests'],
    'zend-mime' => ['Zend/MimeTest', 'Zend/Mime/AllTests'],
    'zend-mobile' => ['Zend/Mobile/AllTests'],
    'zend-navigation' => ['Zend/NavigationTest', 'Zend/Navigation/AllTests'],
    'zend-oauth' => ['Zend/Oauth/AllTests'],
    'zend-openid' => ['Zend/OpenIdTest', 'Zend/OpenId/AllTests'],
    'zend-paginator' => ['Zend/Paginator/AllTests'],
    'zend-pdf' => ['Zend/PdfTest', 'Zend/Pdf/AllTests'],
    'zend-progressbar' => ['Zend/ProgressBar/AllTests'],
    'zend-queue' => ['Zend/Queue/AllTests'],
    'zend-reflection' => ['Zend/Reflection/AllTests'],
    'zend-registry' => ['Zend/RegistryTest'],
    'zend-rest' => ['Zend/Rest/AllTests'],
    'zend-search' => [],
    'zend-search-lucene' => ['Zend/Search/Lucene/AllTests'],
    'zend-serializer' => ['Zend/Serializer/AllTests'],
    'zend-server' => ['Zend/Server/AllTests'],
    'zend-service' => [],
    'zend-service-akismet' => ['Zend/Service/AkismetTest'],
    'zend-service-amazon' => ['Zend/Service/Amazon/AllTests'],
    'zend-service-audioscrobbler' => ['Zend/Service/Audioscrobbler/AllTests'],
    'zend-service-console' => [],
    'zend-service-delicious' => ['Zend/Service/Delicious/AllTests'],
    'zend-service-ebay' => ['Zend/Service/Ebay/AllTests'],
    'zend-service-flickr' => ['Zend/Service/Flickr/AllTests'],
    'zend-service-livedocx' => ['Zend/Service/LiveDocx/AllTests'],
    'zend-service-rackspace' => ['Zend/Service/Rackspace/AllTests'],
    'zend-service-recaptcha' => ['Zend/Service/ReCaptcha/AllTests'],
    'zend-service-shorturl' => ['Zend/Service/ShortUrl/AllTests'],
    'zend-service-slideshare' => ['Zend/Service/SlideShareTest'],
    'zend-service-strikeiron' => ['Zend/Service/StrikeIron/AllTests'],
    'zend-service-twitter' => ['Zend/Service/Twitter/AllTests'],
    'zend-service-windowsazure' => ['Zend/Service/WindowsAzure/AllTests'],
    'zend-service-yahoo' => ['Zend/Service/Yahoo/AllTests'],
    'zend-session' => ['Zend/Session/AllTests'],
    'zend-soap' => ['Zend/Soap/AllTests'],
    'zend-stdlib' => ['Zend/Stdlib/AllTests'],
    'zend-tag' => ['Zend/Tag/AllTests'],
    'zend-test' => ['Zend/Test/AllTests'],
    'zend-text' => ['Zend/Text/AllTests'],
    'zend-timesync' => ['Zend/TimeSyncTest'],
    'zend-tool' => ['Zend/Tool/AllTests'],
    'zend-translate' => ['Zend/TranslateTest', 'Zend/Translate/Adapter/AllTests'],
    'zend-uri' => ['Zend/UriTest', 'Zend/Uri/AllTests'],
    'zend-validate' => ['Zend/ValidateTest', 'Zend/Validate/AllTests'],
    'zend-view' => ['Zend/ViewTest', 'Zend/View/AllTests'],
    'zend-version' => ['Zend/VersionTest'],
    'zend-wildfire' => ['Zend/Wildfire/AllTests'],
    'zend-xml' => ['Zend/Xml/AllTests'],
    'zend-xmlrpc' => ['Zend/XmlRpc/AllTests'],
];

foreach (new DirectoryIterator(dirname(__DIR__) . '/packages') as $dir) {
    if ($dir->isDot()) {
        continue;
    }
    $package = $dir->getFilename();
    if (!isset($map[$package])) {
        echo '!!! package not found in map: ' . $package . PHP_EOL;
        continue;
    }
    if (empty($map[$package])) {
        echo '! package does not have any tests: ' . $package . PHP_EOL;
        continue;
    }

    file_put_contents($dir->getRealPath().'/phpunit.php', '<?php
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require dirname(__FILE__) . \'/vendor/autoload.php\';
// add classmap for phpunit / utils from main repo
$rootDir = dirname(dirname(dirname(__FILE__)));
$classMap = require $rootDir . \'/vendor/composer/autoload_classmap.php\';
if ($classMap) {
    $loader->addClassMap($classMap);
}

define(\'PHPUnit_MAIN_METHOD\', \'PHPUnit_TextUI_Command::main\');
PHPUnit_TextUI_Command::main();
');
    echo 'saved ' . $dir->getRealPath().'/phpunit.php'. PHP_EOL;

    file_put_contents($dir->getRealPath().'/phpunit.xml.dist', '<phpunit bootstrap="../../tests/bootstrap.php"
         colors="true">
    <testsuite name="Zend Framework - Testsuite">
' . implode(PHP_EOL, array_map(function ($path) {
    return sprintf('        <directory>../../tests/%s.php</directory>', $path);
}, $map[$package])) . '
    </testsuite>

    <filter>
        <whitelist>
            <directory>./library/Zend/</directory>
        </whitelist>
    </filter>

    <php>
        <ini name="date.timezone" value="UTC"/>
    </php>
</phpunit>
');
    echo 'saved ' . $dir->getRealPath().'/phpunit.xml.dist'. PHP_EOL;

    $composer = file_get_contents($dir->getRealPath().'/composer.json');
    if (!file_exists($dir->getRealPath().'/composer.json.bak')) {
        copy($dir->getRealPath().'/composer.json', $dir->getRealPath().'/composer.json.bak');
    }

    $suggestPackages = [];
    if (preg_match('/"suggest": \{.*?\}/s', $composer, $suggestMatches)) {
        $suggest = $suggestMatches[0];

        preg_match_all('#zf1s/[a-z0-9-]+#', $suggest, $otherPackages);
        $suggestPackages = $otherPackages[0];
    }

    $pattern = preg_match('/"scripts"/', $composer)
        ? '/,\n    "scripts": \{.*\}\n$/s'
        : '/\n\}\n$/';

    $composer = preg_replace($pattern, ',
    "scripts": {
        "test": [
            "@composer update",
            "@php phpunit.php"
        ]
    },
    "repositories": [
        {
            "type": "path",
            "url": "../../packages/*"
        }
    ],
    "minimum-stability": "dev",
    "prefer-stable": true' . (count($suggestPackages) ? ',
    "require-dev": {
        ' . implode(','.PHP_EOL.'        ', array_map(function($package) {
            return '"'.$package.'": "@dev"';
        }, $suggestPackages)) . '
    }' : '') . '
}
', $composer);
    file_put_contents($dir->getRealPath().'/composer.json', $composer);
    echo 'updated ' . $dir->getRealPath().'/composer.json'. PHP_EOL;
}
