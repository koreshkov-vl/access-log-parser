<?php

namespace App;

/**
 * Interface AccessLogParserInterface
 */
interface AccessLogParserInterface
{
    /**
     * Parse access log line
     *
     * @param string $accessLogLine
     * @return array
     */
    public function parseLine(string $accessLogLine): array;
}
