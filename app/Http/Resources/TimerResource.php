<?php

namespace App\Http\Resources;

use App\Models\Timer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Timer
 */
class TimerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->getUuid()->toString(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'ends_at' => $this->getEndsAt(),
            'expired' => $this->isInPast(),
        ];
    }
}
