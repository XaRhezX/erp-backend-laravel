<?php

namespace App\Exceptions;

use Throwable;
use Psr\Log\LogLevel;
use App\Traits\JsonResponse;
use BadMethodCallException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use JsonResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    //Api Response Handler
    public function render($request, Throwable $exception)
    {
        return $this->handleApiException($request, $exception);
        /*
        if ($request->wantsJson()) {   //add Accept: application/json in request
        } else {
            $retval = parent::render($request, $exception);
        }
        return $retval;
        */
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);
        if (
            $exception instanceof MethodNotAllowedHttpException ||
            $exception instanceof BadMethodCallException
        ) {
            $exception = $exception;
        }

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            return $this->error(401, $exception->getMessage());
        }

        if ($exception instanceof ValidationException) {
            return $this->error(400, $exception->errors());
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        if (method_exists($exception, 'getMessage')) {
            $getMessage = $exception->getMessage();
        } else {
            $getMessage = null;
        }

        $response = [];

        if (config('app.debug')) {
            $response['error'] = $getMessage;
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $statusCode;

        return $this->error($statusCode, $response);
        //return response()->json($response, $statusCode);
    }
}
