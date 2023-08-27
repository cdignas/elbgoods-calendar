<?php

namespace App\Services;

use App\Exceptions\AppointmentNotFoundException;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use Illuminate\Support\Collection;

class AppointmentService
{
    protected AppointmentRepository $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAll(array $params): Collection
    {
        return $this->appointmentRepository->getAll($params);
    }

    public function store(array $params): ?Appointment
    {
        return $this->appointmentRepository->store($params);
    }

    public function show(int $id): ?Appointment
    {
        return $this->appointmentRepository->getById($id);
    }

    /**
     * @throws AppointmentNotFoundException
     */
    public function update(int $id, array $params): bool
    {
        return $this->appointmentRepository->getById($id)->update($params);
    }

    /**
     * @throws AppointmentNotFoundException
     */
    public function delete(int $id): bool
    {
        return $this->appointmentRepository->getById($id)->delete();
    }
}
