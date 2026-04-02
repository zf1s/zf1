#!/usr/bin/env php
<?php
// Extracts a version's section from CHANGELOG.md.
//
// Usage:
//   php bin/extract-changelog.php 1.15.6           # prints the section body
//   php bin/extract-changelog.php 1.15.6 --full    # includes the header line
//   php bin/extract-changelog.php --list            # lists all versions

$root = dirname(__DIR__);
$changelogFile = $root . '/CHANGELOG.md';

if (!file_exists($changelogFile)) {
    fwrite(STDERR, "error: CHANGELOG.md not found\n");
    exit(1);
}

$lines = file($changelogFile, FILE_IGNORE_NEW_LINES);
$arg = $argv[1] ?? null;

if ($arg === null || $arg === '--help' || $arg === '-h') {
    fwrite(STDERR, "Usage: php bin/extract-changelog.php <version> [--full]\n");
    fwrite(STDERR, "       php bin/extract-changelog.php --list\n");
    exit(1);
}

// list all versions
if ($arg === '--list') {
    foreach ($lines as $line) {
        if (preg_match('/^### (.+)/', $line, $m)) {
            echo $m[1] . "\n";
        }
    }
    exit(0);
}

$version = $argv[1];
$full = in_array('--full', array_slice($argv, 2), true);

// find the section for this version
$capturing = false;
$section = [];
$headerLine = null;

foreach ($lines as $line) {
    if (preg_match('/^### /', $line)) {
        if ($capturing) {
            break;
        }
        if (strpos($line, $version) !== false) {
            $capturing = true;
            $headerLine = $line;
            continue;
        }
    } elseif ($capturing) {
        $section[] = $line;
    }
}

if (!$capturing) {
    fwrite(STDERR, "error: version '$version' not found in CHANGELOG.md\n");
    fwrite(STDERR, "available versions: php bin/extract-changelog.php --list\n");
    exit(1);
}

// trim leading/trailing blank lines from the body
while ($section && trim($section[0]) === '') {
    array_shift($section);
}
while ($section && trim(end($section)) === '') {
    array_pop($section);
}

if ($full && $headerLine) {
    array_unshift($section, $headerLine, '');
}

echo implode("\n", $section) . "\n";
