<?php

require_once '../vendor/autoload.php';

use App\AccessLogParser;
use App\AccessLogAggregator;

// test example
$parser = new AccessLogAggregator(new AccessLogParser());

// you can set your own list of search engines
// $parser->setSearchEngines(['rambler.ru', 'mail.ru', 'yandex.ru']);

$json = $parser->getJsonStatistics('../access_log');

echo $json;
