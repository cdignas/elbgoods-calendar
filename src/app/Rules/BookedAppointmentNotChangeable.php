<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Validation rule for booked appointments status is not changeable.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class BookedAppointmentNotChangeable implements ValidationRule
{
    /**
     * Appointment id
     *
     * @var int
     */
    protected int $id;

    /**
     * Appointment status
     *
     * @var string|null
     */
    protected ?string $status;

    /**
     * Constructor for appointment booked status not changeable rule.
     *
     * @param int         $id
     * @param string|null $status
     */
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
        if (!$appointment) {
            return;
        }

        if ($appointment->status === 'Booked' && $this->status !== 'Booked') {
            $fail('Booked appointment is not changeable.');
        }
    }
}
