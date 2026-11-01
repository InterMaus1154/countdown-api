<?php

namespace App\Data;

use App\Models\User;

final readonly class LoginAttemptResponse
{
    public function __construct(public bool $success, public string $message, public ?User $user = null)
    {

    }
}
