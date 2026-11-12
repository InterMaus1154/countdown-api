<?php

namespace App\Traits;

trait LogsApiActivity
{
    /**
     * Requires a Psr\Log\LoggerInterface in $this->logger.
     * Never pass secrets or plain-text tokens in $message or $context.
     */
    private function logApiActivity(string $activity, string $message, array $context = [], string $level = 'info'): void
    {
        $request = request();

        $finalMessage = sprintf(
            '[API Activity] %s %s /%s: Activity type: %s, %s',
            $request->ip() ?? '',
            $request->method(),
            $request->path(),
            $activity,
            $message
        );

        $this->logger->log($level, $finalMessage, $context);
    }
}
