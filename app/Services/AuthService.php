<?php

namespace App\Services;

use App\Data\LoginAttemptData;
use App\Data\LoginAttemptResponse;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher;
use Psr\Log\LoggerInterface;

class AuthService
{

    public function __construct(private StatefulGuard $guard, private LoggerInterface $logger, private UserService $users, private Hasher $hasher)
    {
    }

    public function isLoggedIn(): bool
    {
        return $this->guard->check();
    }

    public function getUserId(): ?int
    {
        return $this->getUser()?->getId();
    }

    public function getUser(): ?User
    {
        /** @var User|null $user */
        $user = $this->guard->user();
        return $user;
    }

    public function attempt(LoginAttemptData $data): LoginAttemptResponse
    {
        $this->recordLoginAttempt("Login started");

        $user = $this->users->findByUsername($data->username);
        if (!$user) {
            $this->recordLoginAttempt(sprintf('Username %s not found', $data->username));
            return $this->invalidCredentialsResponse();
        }

        if (!$this->hasher->check($data->password, $user->getPassword())) {
            $this->recordLoginAttempt(sprintf('Invalid password entered for %s', $data->username));
            return $this->invalidCredentialsResponse();
        }

        $this->recordLoginAttempt(sprintf('Successful login for %s', $data->username));
        $this->guard->login($user);
        return new LoginAttemptResponse(success: true, message: 'Success', user: $user);
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = $this->guard->user();
        $this->recordLogout($user->getUsername());
        $this->guard->logout();
    }

    private function recordLoginAttempt(string $message = ''): void
    {
        $t = now()->toDateTimeString();
        $ip = request()->ip();
        $finalMessage = sprintf('%s [Login Attempt] from %s: %s', $t, $ip, $message);
        $this->logger->info($finalMessage);
    }

    private function recordLogout(string $message = ''): void
    {
        $t = now()->toDateTimeString();
        $ip = request()->ip();
        $finalMessage = sprintf('%s [Logout] from %s: %s', $t, $ip, $message);
        $this->logger->info($finalMessage);
    }

    private function invalidCredentialsResponse(): LoginAttemptResponse
    {
        return new LoginAttemptResponse(success: false, message: 'Invalid Credentials', user: null);
    }
}
