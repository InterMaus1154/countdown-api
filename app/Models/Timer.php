<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Timer extends Model
{
    use HasUuids;

    protected $table = 'timers';

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public static function make(string $title, Carbon $endsAt, int $userId, ?string $description = null): Timer
    {
        $timer = new self();
        $timer->setUuid(Str::uuid7());
        $timer->setTitle($title);
        $timer->setDescription($description);
        $timer->setEndsAt($endsAt);
        $timer->setUserId($userId);
        return $timer;
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getUuid(): UuidInterface
    {
        return Uuid::fromString($this->getAttribute('uuid'));
    }

    public function getEndsAt(): Carbon
    {
        return $this->getAttribute('ends_at');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getDescription(): ?string
    {
        return $this->getAttribute('description');
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->setAttribute('uuid', $uuid->toString());
    }

    public function setTitle(string $title): void
    {
        $this->setAttribute('title', $title);
    }

    public function setDescription(?string $desc = null): void
    {
        $this->setAttribute('description', $desc);
    }

    public function setEndsAt(Carbon $carbon): void
    {
        $this->setAttribute('ends_at', $carbon);
    }

    public function setUserId(int $userId): void
    {
        $this->setAttribute('user_id', $userId);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
