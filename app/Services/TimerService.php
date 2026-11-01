<?php

namespace App\Services;

use App\Models\Timer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TimerService
{

    /**
     * @return Collection<int, Timer>
     */
    public function all(): Collection
    {
        return Timer::query()
            ->with('user')
            ->orderby('ends_at')
            ->get();
    }

    public function commit(Timer $timer): Timer
    {
        if (is_null($timer->getAttribute('uuid'))) {
            $timer->setUuid(Str::uuid7());
        }

        return tap($timer)->save();
    }

    public function findById(int $timerId): ?Timer
    {
        return Timer::query()
            ->where('id', $timerId)
            ->first();
    }

    public function findByUuid(string $uuid): ?Timer
    {
        return Timer::query()
            ->where('uuid', $uuid)
            ->first();
    }
}
