<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
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
     * @param Exception $exception
     *
     * @return JsonResponse
     */
    protected function renderExceptionForApi(Exception $exception): JsonResponse
    {
        $statusCode = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        if ($exception instanceof ValidationException) {
            $statusCode = FoundationResponse::HTTP_UNPROCESSABLE_ENTITY;
        }

        return response()->json(
            [
                'message' => $exception->getMessage()
            ],
            $statusCode
        );
    }
}
