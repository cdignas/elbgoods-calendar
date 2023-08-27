<?php

namespace App\Repositories;

use App\Exceptions\AppointmentNotFoundException;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentRepository
{
    protected Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * @throws AppointmentNotFoundException
     */
    public function getById(int $id): ?Appointment
    {
        $appointment = $this->appointment->find($id);
        if (!$appointment) {
            throw new AppointmentNotFoundException();
        }

        return $appointment;
    }

    public function getAll(array $params): Collection
    {
        return $this->appointment->where(
            'start_date',
            '>=',
            Carbon::now('UTC')
                ->setTime(0, 0, 0)
                ->format('Y-m-d H:i:s')
        )
            ->where(
                'end_date',
                '<=',
                $params['end_date'] ?? Carbon::now('UTC')
                    ->addDays(30)
                    ->setTime(0, 0, 0)
                    ->format('Y-m-d H:i:s')
            )
            ->get();
    }

    public function store(array $params): ?Appointment
    {
        return $this->appointment->create($params);
    }
}
