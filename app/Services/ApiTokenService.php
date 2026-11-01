<?php

namespace App\Services;

use App\Data\TokenMetadata;
use App\Models\ApiToken;

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

    public function issueToken(TokenMetadata $data): ApiToken
    {
        $apiToken = ApiToken::make(deviceName: $data->deviceName, ip: $data->ip);

        return $this->commit($apiToken);
    }

    public function commit(ApiToken $token): ApiToken
    {
        return tap($token)->save();
    }
}
