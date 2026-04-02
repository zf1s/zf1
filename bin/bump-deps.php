#!/usr/bin/env php
<?php
// Bumps zf1s inter-package dependency versions in all package composer.json files.
// Only touches "zf1s/..." entries with a caret version constraint (e.g. "^1.15.5").
// Does not touch "zf1/" replace entries, external deps, or "suggest" descriptions.
//
// Usage:
//   php bin/bump-deps.php 1.16.0          # bumps to ^1.16.0
//   php bin/bump-deps.php ^1.16.0         # same, explicit caret
//   php bin/bump-deps.php --detect        # just prints the current version

$root = dirname(__DIR__);
$arg = $argv[1] ?? null;

if ($arg === null || $arg === '--help' || $arg === '-h') {
    fwrite(STDERR, "Usage: php bin/bump-deps.php <version>\n");
    fwrite(STDERR, "       php bin/bump-deps.php --detect\n");
    fwrite(STDERR, "\nExamples:\n");
    fwrite(STDERR, "  php bin/bump-deps.php 1.16.0\n");
    fwrite(STDERR, "  php bin/bump-deps.php ^1.16.0\n");
    exit(1);
}

$pattern = '/"zf1s\/([^"]+)":\s*"(\^[0-9]+\.[0-9]+\.[0-9]+)"/';

// detect current version from existing files
$currentVersion = null;
foreach (glob($root . '/packages/*/composer.json') as $file) {
    if (preg_match($pattern, file_get_contents($file), $m)) {
        $currentVersion = $m[2];
        break;
    }
}

if ($arg === '--detect') {
    echo $currentVersion ? "current: $currentVersion\n" : "no zf1s/* version constraints found\n";
    exit(0);
}

if ($currentVersion === null) {
    fwrite(STDERR, "error: could not detect current version from package composer.json files\n");
    exit(1);
}

$newVersion = ltrim($arg, '^');
$newConstraint = "^$newVersion";

if ($currentVersion === $newConstraint) {
    fwrite(STDERR, "already at $newConstraint, nothing to do\n");
    exit(0);
}

echo "bumping: $currentVersion -> $newConstraint\n\n";

$updated = 0;
foreach (glob($root . '/packages/*/composer.json') as $file) {
    $content = file_get_contents($file);
    $replaced = preg_replace(
        $pattern,
        '"zf1s/$1": "' . $newConstraint . '"',
        $content,
        -1,
        $count
    );

    if ($count > 0) {
        file_put_contents($file, $replaced);
        $short = str_replace($root . '/', '', $file);
        echo "  $short ($count dep" . ($count > 1 ? 's' : '') . ")\n";
        $updated++;
    }
}

echo "\nupdated $updated packages\n";
