<?php

namespace App\Services;

use App\Models\Timer;
use Illuminate\Support\Str;

class TimerService
{
    public function commit(Timer $timer): Timer
    {
        if(is_null($timer->getAttribute('uuid'))) {
            $timer->setUuid(Str::uuid7());
        }

        return tap($timer)->save();
    }
}
