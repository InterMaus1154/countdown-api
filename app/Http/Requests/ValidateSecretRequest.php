<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Psr\Log\LoggerInterface;

class ValidateSecretRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'secret' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        // Log only the failing field names, never the submitted values (the secret).
        app(LoggerInterface::class)->warning(
            sprintf(
                '[API Activity] %s %s /%s: Activity type: VALIDATE_BAD_REQUEST, Failed fields: %s',
                $this->ip() ?? '',
                $this->method(),
                $this->path(),
                implode(', ', array_keys($validator->errors()->messages()))
            ),
            ['user_agent' => $this->userAgent()]
        );

        parent::failedValidation($validator);
    }
}
