<?php

use PHPUnit\Framework\TestCase;
use App\AccessLogAggregator;
use App\AccessLogParser;
use App\AccessLogParseException;

class AccessLogAggregatorTest extends TestCase
{
    /**
     * Test GetJsonStatistics positive case
     */
    public function testGetJsonStatistics()
    {
        $accessLogAggregator = new AccessLogAggregator(new AccessLogParser());

        $statistics = $accessLogAggregator->getJsonStatistics(__DIR__ . '/data/correct_access_log');

        $correctJson = file_get_contents(__DIR__ . "/result/statistics.json");

        $this->assertJson($statistics, $correctJson);
    }

    /**
     * Test GetJsonStatistics negative case
     */
    public function testThrowWhenGetJsonStatistics()
    {
        $this->expectException(AccessLogParseException::class);

        $accessLogAggregator = new AccessLogAggregator(new AccessLogParser());

        $statistics = $accessLogAggregator->getJsonStatistics(__DIR__ . '/data/incorrect_access_log');
    }
}
