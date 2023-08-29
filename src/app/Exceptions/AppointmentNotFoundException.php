<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Appointment find found failed.
 *
 * @author Christian Dignas <christian.dignas@gmail.com>
 */
class AppointmentNotFoundException extends Exception implements HttpExceptionInterface
{
    /**
     * Message for exception
     *
     * @var string
     */
    protected $message = 'Appointment Not Found';

    /**
     * Get https status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    /**
     * Get http headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
    }
}
