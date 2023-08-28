<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BookedAppointmentNotChangeable implements ValidationRule
{
    protected int $id;

    protected ?string $status;

    public function __construct(int $id, ?string $status = null)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $appointment = Appointment::find($this->id);

        if ($appointment->status === 'Booked' && $this->status !== 'Booked') {
            $fail('Booked appointment is not changeable.');
        }
    }
}
