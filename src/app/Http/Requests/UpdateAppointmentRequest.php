<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'sometimes|string|max:255',
            'description'   => 'sometimes|nullable|string',
            'start_date'    => 'sometimes|before_or_equal:end_date|date_format:Y-m-d',
            'end_date'      => 'sometimes|after_or_equal:start_date|date_format:Y-m-d',
            'status'        => 'sometimes|in:Requested,Tentative,Booked'
        ];
    }
}
