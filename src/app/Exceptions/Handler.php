<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render exceptions Messages and stack traces.
     *
     * @param                     $request
     * @param Exception|Throwable $exception
     *
     * @return JsonResponse|RedirectResponse|Response|FoundationResponse
     * @throws Throwable
     */
    public function render($request, Exception|Throwable $exception)
    : Response|JsonResponse|FoundationResponse|RedirectResponse {
        if ($request->wantsJson()) {
            return $this->renderExceptionForApi($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render Exception for API.
     *
     * @param Exception|Throwable $exception
     *
     * @return JsonResponse
     */
    protected function renderExceptionForApi(Exception|Throwable $exception): JsonResponse
    {
        $message = $exception->getMessage();
        $statusCode = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            // if exception has getter for status code, otherwise 500 (internal server error)
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof ValidationException) {
            // if validation exception then status code 422 (unprocessable entity error)
            $statusCode = FoundationResponse::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($exception instanceof ModelNotFoundException) {
            $message = 'Model Not Found';
        }

        if ($exception instanceof QueryException) {
            // if query exception then show only default message for security reasons
            $message = 'Internal Server Error';
        }

        return response()->json(
            ['message' => $message],
            $statusCode
        );
    }
}
