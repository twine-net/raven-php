<?php

namespace Twine\Raven;

use Exception;
use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    /**
     * Adds a log record.
     *
     * @param  int     $level   The logging level
     * @param  string  $message The log message
     * @param  array   $context The log context
     * @return Boolean Whether the record has been processed
     */
    public function addRecord($level, $message, array $context = array())
    {
        if ($message instanceof Exception) {
            // Set context exception using exception
            $context = array_merge($context, ['exception' => $message]);
            $message = $message->getMessage();
        }

        return parent::addRecord($level, $message, $context);
    }
}
