<?php
// app/Http/Requests/Admin/UpdateReservationStatusRequest.php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:confirmed,cancelled,no_show'],
            'cancel_reason' => ['nullable', 'string', 'max:255', 'required_if:status,cancelled'],
        ];
    }
}
