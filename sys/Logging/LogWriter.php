<?php

namespace Sys\Logging;

interface LogWriter
{
    public function write(string $formattedEntry): bool;
}
