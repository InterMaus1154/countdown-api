<?php

namespace App\Traits;

trait LogsTimerActivity
{
    private function logTimerActivity(string $activity, string $message, array $context = []): void
    {
        $finalMessage = sprintf('[Timer Activity] %s Caused by %s: Activity type: %s, %s', request()->ip() ?? '', $this->auth->getUserId() ?? 'API_CALL', $activity, $message);
        $this->logger->info($finalMessage, $context);
    }
}
