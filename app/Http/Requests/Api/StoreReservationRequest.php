<?php
// app/Http/Requests/Api/StoreReservationRequest.php
namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location' => ['required', 'in:downtown,midtown'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'seating_preference' => ['nullable', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['required', 'string', 'max:20'],
            'special_requests' => ['nullable', 'string'],
            'occasion' => ['nullable', 'string', 'max:100'],
            'sms_consent' => ['nullable', 'boolean'],
        ];
    }
}
