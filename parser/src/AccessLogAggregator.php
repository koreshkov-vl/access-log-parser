<?php

namespace App;

use Generator;

/**
 * Class AccessLogAggregator
 */
class AccessLogAggregator
{
    /**
     * Access log parser interface
     * @var
     */
    private $accessLogParser;

    /**
     * List of search engines for statistics
     * @var array
     */
    private $searchEngines = ['yandex.ru', 'google.ru', 'google.com', 'baidu.com', 'bing.com'];

    /**
     * AccessLogAggregator constructor.
     * @param AccessLogParserInterface $accessLogParser
     */
    public function __construct(AccessLogParserInterface $accessLogParser)
    {
        $this->accessLogParser = $accessLogParser;
    }

    /**
     * Set search engines for get statistics from access log
     *
     * @param array $searchEngines
     */
    public function setSearchEngines(array $searchEngines)
    {
        $this->searchEngines = $searchEngines;
    }

    /**
     * Get statistics in JSON format
     *
     * @param string $accessLogFilename
     * @return string
     */
    public function getJsonStatistics(string $accessLogFilename): string
    {
        $statistics     = $this->getStatistics($accessLogFilename);
        $jsonStatistics = json_encode($statistics);

        return $jsonStatistics;
    }

    /**
     * Get statistics using access log file
     *
     * @param string $accessLogFilename
     * @return array
     */
    private function getStatistics(string $accessLogFilename): array
    {
        $fileGenerator = $this->readAccessLogFile($accessLogFilename);

        $viewsCount  = 0;
        $traffic     = 0;
        $urls        = [];
        $statusCodes = [];

        foreach ($fileGenerator as $line) {
            $accessLogInfo = $this->accessLogParser->parseLine($line);

            if (isset($urls[$accessLogInfo['url']])) {
                $urls[$accessLogInfo['url']] ++;
            } else {
                $urls[$accessLogInfo['url']] = 1;
            }

            if (isset($statusCodes[$accessLogInfo['status']])) {
                $statusCodes[$accessLogInfo['status']] ++;
            } else {
                $statusCodes[$accessLogInfo['status']] = 1;
            }

            $traffic += $accessLogInfo['traffic'];
            $viewsCount++;
        }

        $urlsCount               = $this->getUniqueUrlsCount($urls);
        $searchEnginesWithVisits = $this->getSearchEnginesWithVisits($urls);

        $statistics = [
            'views'       => $viewsCount,
            'urls'        => $urlsCount,
            'traffic'     => $traffic,
            'crawlers'    => $searchEnginesWithVisits,
            'statusCodes' => $statusCodes
        ];

        return $statistics;
    }

    /**
     * Read each line of access log file
     *
     * @param string $path
     * @return Generator
     */
    private function readAccessLogFile(string $path): Generator
    {
        $handle = fopen($path, "r");
        while (!feof($handle)) {
            yield trim(fgets($handle));
        }
        fclose($handle);
    }

    /**
     * Get unique urls count
     *
     * @param array $urls
     * @return int
     */
    private function getUniqueUrlsCount(array $urls): int
    {
        $uniqueUrls = array_keys($urls);
        $urlsCount  = count($uniqueUrls);

        return $urlsCount;
    }

    /**
     * Get list of search engines with visits
     *
     * @param array $urlsWithVisits
     * @return array
     */
    private function getSearchEnginesWithVisits(array $urlsWithVisits): array
    {
        $searchEnginesWithVisits = array_fill_keys($this->searchEngines, 0);

        foreach ($urlsWithVisits as $url => $visits) {
            if (isset($searchEnginesWithVisits[$url])) {
                $searchEnginesWithVisits[$url] += $visits;
            }
        }

        return $searchEnginesWithVisits;
    }
}
