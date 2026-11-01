<?php

namespace App\Http\Requests;

use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTimerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(AuthService::class)->isLoggedIn();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'end_date' => 'required|after_or_equal:'. now()->format('Y-m-d'),
            'end_time' => 'nullable|date_format:H:i'
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function(Validator $validator){
            if(! $this->input('end_date')) {
                return;
            }

            $endDate = $this->input('end_date');
            $time = $this->input('end_time', '00:00');
            $endsAt = Carbon::parse("{$endDate} {$time}");
            if($endsAt->isPast()) {
                $validator->errors()->add('end_date', 'End date must be after or equal to current date and time');
            }
        });
    }
}
