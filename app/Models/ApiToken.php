<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $table = 'api_tokens';

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getToken(): string
    {
        return $this->getAttribute('token');
    }

    public function setDeviceName(?string $name): void
    {
        $this->setAttribute('device_name', $name);
    }

    public function setIpAddress(?string $ip): void
    {
        $this->setAttribute('ip_address', $ip);
    }

    public function setToken(string $token): void
    {
        $this->setAttribute('token', $token);
    }

    public function setExpiresAt(Carbon $date): void
    {
        $this->setAttribute('expires_at', $date);
    }

    public function setLastUsedAt(Carbon $date): void
    {
        $this->setAttribute('last_used_at', $date);
    }

    public function getExpiresAt(): Carbon
    {
        return $this->getAttribute('expires_at');
    }

    /**
     * @param string|null $deviceName
     * @param string|null $ip
     * @return array{apiToken: ApiToken, plainToken: string}
     */
    public static function make(?string $deviceName = null, ?string $ip = null): array
    {
        $token = new self();
        $token->setDeviceName($deviceName);
        $token->setIpAddress($ip);

        $plainTextToken = Str::random(64);

        $hashToken = hash('sha256', $plainTextToken);
        $token->setToken($hashToken);
        $token->setExpiresAt(now()->addMonth()->toMutable());

        return [
            'apiToken' => $token,
            'plainToken' => $plainTextToken,
        ];
    }
}
