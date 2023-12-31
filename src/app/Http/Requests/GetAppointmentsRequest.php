<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for get appointments.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class GetAppointmentsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'end_date' => [
                'sometimes',
                'after_or_equal:today',
                'date_format:Y-m-d',
            ],
        ];
    }
}
