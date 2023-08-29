<?php

namespace App\Http\Controllers;

use App\Exceptions\AppointmentNotFoundException;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\GetAppointmentsRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;

/**
 * Controller for crud operations of appointments.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class AppointmentController extends \Illuminate\Routing\Controller
{
    /**
     * Appointment service
     *
     * @var AppointmentService
     */
    protected AppointmentService $appointmentService;

    /**
     * Create a new instance of AppointmentController.
     *
     * @param AppointmentService $appointmentService
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Get all appointments for a specific time range.
     *
     * @param GetAppointmentsRequest $request
     *
     * @return AppointmentCollection
     */
    public function index(GetAppointmentsRequest $request): AppointmentCollection
    {
        return new AppointmentCollection(
            $this->appointmentService->getAll($request->validated())
        );
    }

    /**
     * Store a new appointment.
     *
     * @param CreateAppointmentRequest $request
     *
     * @return AppointmentResource
     */
    public function store(CreateAppointmentRequest $request): AppointmentResource
    {
        return new AppointmentResource(
            $this->appointmentService->store($request->validated())
        );
    }

    /**
     * Show appointment of given id.
     *
     * @param int $id
     *
     * @return AppointmentResource
     *
     * @throws AppointmentNotFoundException
     */
    public function show(int $id): AppointmentResource
    {
        return new AppointmentResource(
            $this->appointmentService->show($id)
        );
    }

    /**
     * Update appointment of given id.
     *
     * @param int                      $id
     * @param UpdateAppointmentRequest $request
     *
     * @return AppointmentResource
     *
     * @throws AppointmentNotFoundException
     */
    public function update(int $id, UpdateAppointmentRequest $request): AppointmentResource
    {
        return new AppointmentResource(
            $this->appointmentService->update($id, $request->validated())
        );
    }

    /**
     * Delete appointment of given id.
     *
     * @param int $id
     *
     * @return void
     *
     * @throws AppointmentNotFoundException
     */
    public function delete(int $id): void {
        $this->appointmentService->delete($id);
    }
}
