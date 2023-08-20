<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $e
     *
     * @SuppressWarnings("unused")
     *
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if (env('APP_DEBUG')) {
            Log::debug($e);
        }

        return match (true) {
            $e instanceof HttpException => $this->httpException($e),
            $e instanceof CustomValidationException => $this->validationException($e),
            $e instanceof AuthorizationException => $this->errorException($e, Response::HTTP_FORBIDDEN),
            $e instanceof CustomException => $this->errorException($e, $e->getCode()),
            $e instanceof ModelNotFoundException => $this->errorException($e, Response::HTTP_NOT_FOUND),
            default => $this->errorException($e),
        };

    }
}
