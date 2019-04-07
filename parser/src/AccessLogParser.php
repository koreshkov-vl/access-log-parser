<?php

namespace App;

/**
 * Class AccessLogParser
 */
class AccessLogParser implements AccessLogParserInterface
{
    /**
     * Regex pattern for searching status code, traffic and url
     */
    const ACCESS_LOG_SEARCH_PATTERN = '/ ([0-9]{3}).*([0-9]+) ".*(\/\/www\.|\/\/)(.*)\//U';

    /**
     * Status code index in regex result
     */
    const STATUS_CODE_INDEX = 1;

    /**
     * Traffic value index in regex result
     */
    const TRAFFIC_VALUE_INDEX = 2;

    /**
     * Url index in regex result
     */
    const URL_INDEX = 4;

    /**
     * Parse line of access log for getting status code, traffic and domain
     *
     * @param string $accessLogLine
     * @return array
     */
    public function parseLine(string $accessLogLine): array
    {
        $pregMatchCodeResult = preg_match_all(self::ACCESS_LOG_SEARCH_PATTERN, $accessLogLine, $accessLogLineAfterParse);

        if ($pregMatchCodeResult === 0) {
            throw new AccessLogParseException('fail during parsing access log file. line: "' . $accessLogLine . '"');
        }

        $statusCode   = (int) $accessLogLineAfterParse[self::STATUS_CODE_INDEX][0];
        $trafficValue = (int) $accessLogLineAfterParse[self::TRAFFIC_VALUE_INDEX][0];
        $url          = $accessLogLineAfterParse[self::URL_INDEX][0];

        return [
            'status'  => $statusCode,
            'traffic' => $trafficValue,
            'url'     => $url
        ];
    }
}
