<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

try {
    $yaml = Yaml::parseFile($argv[1]);
} catch (ParseException $exception) {
    printf('Unable to parse the YAML string: %s', $exception->getMessage());
    exit($exception->getCode());
}

usort($yaml['entries'], function($a, $b) {
    return $a['title'] <=> $b['title'];
});

$output = Yaml::dump($yaml, 5, 2);

$output = preg_replace("/-\n +/m", '- ', $output);
$output = preg_replace("/ +- title:/", "\n  - title:", $output);
$output = preg_replace("/^entries:\n/m", "entries:", $output);
$output = "---\n" . $output;

file_put_contents($argv[1], $output);
