<?php

namespace App\Rules;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NoOverlappingAppointments implements ValidationRule
{
    protected ?string $startDate;

    protected ?string $endDate;

    protected ?string $status;

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
        $this->checkOverlappingBookedAppointments($fail);
        $this->checkMaxCorrespondingAppointments($fail);
    }

    protected function checkOverlappingBookedAppointments(Closure $fail): void {
        $booked = Appointment::where('status', 'Booked')
            ->when(
                $this->status === 'Booked' && isset($this->id),
                function($query) {
                    $query->whereNot('id', $this->id);
                }
            )
            ->where(function($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })
            ->count();

        if ($booked > 0) {
            $fail('There are a overlapping booked appointment.');
        }
    }

    protected function checkMaxCorrespondingAppointments(Closure $fail): void {
        $tentative = Appointment::whereBetween('start_date', [$this->startDate, $this->endDate])
            ->orWhereBetween('end_date', [$this->startDate, $this->endDate])
            ->when(
                isset($this->id),
                function($query) {
                    $query->whereNot('id', $this->id);
                }
            )
            ->count();

        if ($tentative >= 4) {
            $fail('There are more than 4 overlapping appointments.');
        }
    }
}
