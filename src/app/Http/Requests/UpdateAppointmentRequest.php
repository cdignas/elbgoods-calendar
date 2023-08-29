<?php

namespace App\Http\Requests;

use App\Rules\BookedAppointmentNotChangeable;
use App\Rules\NoOverlappingAppointments;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for updating existing appointment.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_date' =>
                [
                    'sometimes',
                    'before_or_equal:end_date',
                    'date_format:Y-m-d',
                    new NoOverlappingAppointments(
                        $this->input('start_date'),
                        $this->input('end_date'),
                        $this->input('status'),
                        $this->id,
                    )
                ],
            'end_date' =>
                [
                    'sometimes',
                    'after_or_equal:start_date',
                    'date_format:Y-m-d',
                ],
            'status' => [
                'sometimes',
                'in:Requested,Tentative,Booked',
                new BookedAppointmentNotChangeable(
                    $this->id,
                    $this->input('status'),
                ),
            ]
        ];
    }
}
