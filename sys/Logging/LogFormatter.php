<?php

namespace Sys\Logging;

interface LogFormatter
{
    public function format(LogEntry $entry): string;
}
