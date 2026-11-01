<?php

namespace App\Data;

use SensitiveParameter;

final readonly class LoginAttemptData
{
    public function __construct(public string $username, #[SensitiveParameter] public string $password)
    {
    }
}
