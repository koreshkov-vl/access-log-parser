<?php

require_once '../vendor/autoload.php';

use App\AccessLogParser;
use App\AccessLogAggregator;

$parser = new AccessLogAggregator(new AccessLogParser());

$accessLogFile = $argv[1];

if (!file_exists($accessLogFile)) {
    echo 'file "' . $accessLogFile . '" doesn\'t exist!' . PHP_EOL;
    exit;
}

$json = $parser->getJsonStatistics($accessLogFile);

echo $json;