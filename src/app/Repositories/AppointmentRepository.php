<?php

namespace App\Repositories;

use App\Exceptions\AppointmentNotFoundException;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Persistence logic for operations with appointments.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class AppointmentRepository
{
    /**
     * Appointment model
     *
     * @var Appointment
     */
    protected Appointment $appointment;

    /**
     * Constructor for appointment repository.
     *
     * @param Appointment $appointment
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get appointment by id.
     *
     * @param int $id
     *
     * @return Appointment
     *
     * @throws AppointmentNotFoundException
     */
    public function getById(int $id): Appointment
    {
        $appointment = $this->appointment->find($id);
        if (!$appointment) {
            throw new AppointmentNotFoundException();
        }

        return $appointment;
    }

    /**
     * Get all appointments for given end date if isset or default 30 days in the future.
     *
     * @param array{end_date: string} $params
     *
     * @return Collection
     */
    public function getAll(array $params): Collection
    {
        return $this->appointment->where(
            'start_date',
            '>=',
            Carbon::now()->format('Y-m-d')
        )
            ->where(
                'end_date',
                '<=',
                $params['end_date'] ?? Carbon::now()
                    ->addDays(30)
                    ->format('Y-m-d')
            )
            ->get();
    }

    /**
     * Store appointment with given parameters.
     *
     * @param array{
     *     title:       string,
     *     description: string,
     *     start_date:  string,
     *     end_date:    string,
     *     status:      string
     * } $params
     *
     * @return Appointment
     */
    public function store(array $params): Appointment
    {
        return $this->appointment->create($params);
    }
}
