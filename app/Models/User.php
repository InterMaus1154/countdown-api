<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getUsername(): string
    {
        return $this->getAttribute('username');
    }

    public function getPassword(): string
    {
        return $this->getAttribute('password');
    }
}
