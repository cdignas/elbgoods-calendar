<?php

namespace App\Services;

use App\Exceptions\AppointmentNotFoundException;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Support\Collection;

/**
 * Service class for appointments, needed for API.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class AppointmentService
{
    /**
     * Appointment Repository
     *
     * @var AppointmentRepository
     */
    protected AppointmentRepository $appointmentRepository;

    /**
     * Constructor for appointment service.
     *
     * @param AppointmentRepository $appointmentRepository
     */
    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Get all appointments for given time range.
     *
     * @param array{end_date: string} $params
     *
     * @return Collection
     */
    public function getAll(array $params): Collection
    {
        return $this->appointmentRepository->getAll($params);
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
        return $this->appointmentRepository->store($params);
    }

    /**
     * Show appointment of given id.
     *
     * @throws AppointmentNotFoundException
     */
    public function show(int $id): Appointment
    {
        return $this->appointmentRepository->getById($id);
    }

    /**
     * Update appointment if given id
     *
     * @throws AppointmentNotFoundException
     */
    public function update(int $id, array $params): Appointment
    {
        $appointment = $this->appointmentRepository->getById($id);
        $appointment->update($params);

        return $appointment;
    }

    /**
     * Delete appointment for given id.
     *
     * @throws AppointmentNotFoundException
     */
    public function delete(int $id): bool
    {
        return $this->appointmentRepository->getById($id)->delete();
    }
}
