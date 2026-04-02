#!/usr/bin/env php
<?php
// Composes GitHub release notes from CHANGELOG.md and auto-generated contributor info.
//
// Usage:
//   php bin/compose-release-notes.php 1.15.6
//   php bin/compose-release-notes.php 1.15.6 1.15.5    # explicit previous tag

$root = dirname(__DIR__);
$tag = $argv[1] ?? null;
$previousTag = $argv[2] ?? null;

if ($tag === null || $tag === '--help' || $tag === '-h') {
    fwrite(STDERR, "Usage: php bin/compose-release-notes.php <tag> [previous-tag]\n");
    exit(1);
}

// extract changelog section
$extractScript = $root . '/bin/extract-changelog.php';
$changelog = shell_exec(sprintf('php %s %s 2>/dev/null', escapeshellarg($extractScript), escapeshellarg($tag)));
if ($changelog === null || $changelog === '') {
    fwrite(STDERR, "error: could not extract changelog for version $tag\n");
    exit(1);
}

// detect previous tag if not provided
if ($previousTag === null) {
    $tags = shell_exec('git tag --sort=-version:refname 2>/dev/null');
    if ($tags) {
        $tagList = array_values(array_filter(array_map('trim', explode("\n", $tags))));
        $pos = array_search($tag, $tagList, true);
        if ($pos !== false && isset($tagList[$pos + 1])) {
            // tag exists, take the one after it
            $previousTag = $tagList[$pos + 1];
        } elseif ($pos === false && count($tagList) > 0) {
            // tag doesn't exist yet, use the latest existing tag
            $previousTag = $tagList[0];
        }
    }
}

// get contributors from GitHub auto-generated notes
$contributorOutput = '';
if ($previousTag) {
    $cmd = sprintf(
        'gh api repos/zf1s/zf1/releases/generate-notes -f tag_name=%s -f previous_tag_name=%s --jq .body 2>/dev/null',
        escapeshellarg($tag),
        escapeshellarg($previousTag)
    );
    $autoNotes = shell_exec($cmd);
    if ($autoNotes) {
        // extract all @mentions from the auto-generated notes
        preg_match_all('/@([a-zA-Z0-9_-]+)/', $autoNotes, $matches);
        $allContributors = array_unique($matches[1]);
        usort($allContributors, 'strcasecmp');

        // extract "New Contributors" section if present
        $lines = explode("\n", $autoNotes);
        $capturing = false;
        $newContributorLines = [];
        foreach ($lines as $line) {
            if (strpos($line, 'New Contributors') !== false) {
                $capturing = true;
                continue;
            }
            if ($capturing) {
                if (strpos($line, '**Full Changelog**') !== false || ($line !== '' && $line[0] === '#')) {
                    break;
                }
                if (trim($line) !== '') {
                    $newContributorLines[] = $line;
                }
            }
        }

        // sort new contributors by PR number
        usort($newContributorLines, function ($a, $b) {
            preg_match('/\/pull\/(\d+)/', $a, $ma);
            preg_match('/\/pull\/(\d+)/', $b, $mb);
            return ($ma[1] ?? 0) - ($mb[1] ?? 0);
        });

        $parts = [];
        if ($allContributors) {
            $mentions = array_map(function ($c) { return '@' . $c; }, $allContributors);
            $parts[] = "\xF0\x9F\x8E\x89 Contributors: " . implode(' ', $mentions);
        }
        if ($newContributorLines) {
            $parts[] = "## New Contributors\n" . implode("\n", $newContributorLines);
        }
        $contributorOutput = implode("\n\n", $parts);
    }
}

// compose
$intro = 'This is a general release of all packages in the monorepo.' . "\n"
    . 'You may use individual packages by installing them via composer: `composer require zf1s/zend-*`'
    . ' or the whole framework in one go with `composer require zf1s/zf1`'
    . ' - see [README](https://github.com/zf1s/zf1/blob/master/README.md).';

$body = $intro . "\n\n" . trim($changelog);

if ($contributorOutput !== '') {
    $body .= "\n\n" . $contributorOutput;
}

echo $body . "\n";
