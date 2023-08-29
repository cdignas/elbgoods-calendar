<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * Validation rule for no overlapping appointments rule.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class NoOverlappingAppointments implements ValidationRule
{
    /**
     * Maximum corresponding appointments.
     */
    protected const MAXIMUM_CORRESPONDING_APPOINTMENTS = 4;

    /**
     * Start date of appointment
     *
     * @var string|null
     */
    protected ?string $startDate;

    /**
     * End date of appointment
     *
     * @var string|null
     */
    protected ?string $endDate;

    /**
     * Appointment status
     *
     * @var string|null
     */
    protected ?string $status;

    /**
     * Appointment id
     *
     * @var int|null
     */
    protected ?int $id;

    public function __construct(
        ?string $startDate,
        ?string $endDate,
        ?string $status,
        ?int $id = null
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->id = $id;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->checkVariables()) {
            return;
        }

        $this->checkOverlappingBookedAppointments($fail);
        $this->checkMaxCorrespondingAppointments($fail);
    }

    /**
     * Check if there are any overlapping appointments.
     *
     * @param Closure $fail
     *
     * @return void
     */
    protected function checkOverlappingBookedAppointments(Closure $fail): void
    {
        $booked = Appointment::inDateRangeWithoutOverlapping($this->startDate, $this->endDate)
            ->where('status', 'Booked')
            ->when(
                $this->status === 'Booked' && isset($this->id),
                function($query) {
                    $query->whereNot('id', $this->id);
                }
            )
            ->count();

        if ($booked > 0) {
            $fail('There are a overlapping booked appointment.');
        }
    }

    /**
     * Check if maximum of corresponding appointments are available.
     *
     * @param Closure $fail
     *
     * @return void
     */
    protected function checkMaxCorrespondingAppointments(Closure $fail): void
    {
        $tentative = Appointment::inDateRangeWithoutOverlapping($this->startDate, $this->endDate)
            ->when(
                isset($this->id),
                function($query) {
                    $query->whereNot('id', $this->id);
                }
            )
            ->count();

        if ($tentative >= self::MAXIMUM_CORRESPONDING_APPOINTMENTS) {
            $fail(
                sprintf(
                    'There are more than %d overlapping appointments.',
                    self::MAXIMUM_CORRESPONDING_APPOINTMENTS
                )
            );
        }
    }

    /**
     * Check if all instance variables are set, otherwise get it from model, and return true.
     * If model not exists return false.
     *
     * @return bool
     */
    protected function checkVariables(): bool
    {
        if ($this->startDate && $this->endDate && $this->status) {
            return true;
        }

        $appointment = Appointment::find($this->id);
        if (!$appointment) {
            return false;
        }

        if (!$this->startDate) {
            $this->startDate = $appointment->start_date;
        }

        if (!$this->endDate) {
            $this->endDate = $appointment->end_date;
        }

        if (!$this->status) {
            $this->status = $appointment->status;
        }

        return true;
    }
}
