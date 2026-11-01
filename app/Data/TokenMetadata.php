<?php

namespace App\Data;

final readonly class TokenMetadata
{
    public function __construct(public ?string $deviceName = null, public ?string $ip = null)
    {
    }
}
