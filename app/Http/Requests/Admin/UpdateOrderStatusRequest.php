<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:confirmed,preparing,ready,delivered,cancelled'],
            'cancel_reason' => ['nullable', 'string', 'max:255', 'required_if:status,cancelled'],
        ];
    }
}