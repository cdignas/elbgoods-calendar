<?php

namespace App\Http\Controllers;

use App\Exceptions\AppointmentNotFoundException;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\GetAppointmentsRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;

class AppointmentController extends \Illuminate\Routing\Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(GetAppointmentsRequest $request): AppointmentCollection
    {
        return new AppointmentCollection(
            $this->appointmentService->getAll($request->validated())
        );
    }

    public function store(CreateAppointmentRequest $request): AppointmentResource {
        return new AppointmentResource(
            $this->appointmentService->store($request->validated())
        );
    }

    public function show(int $id): AppointmentResource
    {
        return new AppointmentResource(
            $this->appointmentService->show($id)
        );
    }

    /**
     * @throws AppointmentNotFoundException
     */
    public function update(int $id, UpdateAppointmentRequest $request): AppointmentResource
    {
        return new AppointmentResource(
            $this->appointmentService->update($id, $request->validated())
        );
    }

    /**
     * @throws AppointmentNotFoundException
     */
    public function delete(int $id): void {
        $this->appointmentService->delete($id);
    }
}
