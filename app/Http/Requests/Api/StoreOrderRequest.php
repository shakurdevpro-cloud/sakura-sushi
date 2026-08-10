<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isGuest = ! auth('sanctum')->check();

        return [
            'type' => ['nullable', 'in:delivery,pickup'],
            'delivery_address' => ['nullable', 'array'],
            'delivery_address.street' => ['required_with:delivery_address', 'string'],
            'delivery_address.city' => ['required_with:delivery_address', 'string'],
            'delivery_address.zip' => ['required_with:delivery_address', 'string'],
            'guest_email' => [Rule::requiredIf($isGuest), 'nullable', 'email', 'max:180'],
            'guest_phone' => [Rule::requiredIf($isGuest), 'nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string'],
        ];
    }
}