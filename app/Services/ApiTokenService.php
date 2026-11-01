<?php

namespace App\Services;

use App\Data\TokenMetadata;
use App\Models\ApiToken;
use Carbon\Carbon;

class ApiTokenService
{
    public function validateSecret(string $input): bool
    {
        $configSecret = config('client.client_app_secret');

        if(is_null($configSecret)) {
            return false;
        }

        return hash_equals($configSecret, $input);
    }

    /**
     * @param TokenMetadata $data
     * @return array{apiToken: ApiToken, plainTextToken: string}
     */
    public function issueToken(TokenMetadata $data): array
    {
        $result = ApiToken::make(deviceName: $data->deviceName, ip: $data->ip);

        $apiToken = $result['apiToken'];
        $apiToken = $this->commit($apiToken);

        $plainTextToken = $result['plainToken'];

        return [
           'apiToken' => $apiToken,
           'plainTextToken' => $plainTextToken
        ];
    }

    public function commit(ApiToken $token): ApiToken
    {
        return tap($token)->save();
    }

    public function validateToken(?string $token = null): ?ApiToken
    {
        if(is_null($token)) {
            return null;
        }

        return ApiToken::query()
            ->where('token', hash('sha256', $token))
            ->where('expires_at', '>', now())
            ->first();
    }

    public function updateLastUsedAt(ApiToken $apiToken): void
    {
        $apiToken->setLastUsedAt(Carbon::now());
        $this->commit($apiToken);
    }
}
