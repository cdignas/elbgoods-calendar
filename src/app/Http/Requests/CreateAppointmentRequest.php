<?php

namespace App\Http\Requests;

use App\Rules\NoOverlappingAppointments;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_date' => [
                'required',
                'after_or_equal:today',
                'before_or_equal:end_date',
                'date_format:Y-m-d'
            ],
            'end_date' => [
                'required',
                'after_or_equal:today',
                'after_or_equal:start_date',
                'date_format:Y-m-d',
                new NoOverlappingAppointments(
                    $this->input('start_date'),
                    $this->input('end_date'),
                    $this->input('status'),
                )
            ],
            'status' => 'required|in:Requested,Tentative,Booked'
        ];
    }
}
