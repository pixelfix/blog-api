<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof RouteNotFoundException) {
            return $this->handleRouteNotFoundException();
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->handleNotFoundHttpException();
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->handleMethodNotAllowedHttpException();
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException();
        }

        if ($e instanceof AuthorizationException) {
            return $this->handleAuthorizationException();
        }

        return parent::render($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json($this->formatResponse('You are not authenticated to view this content', 401), 401);
    }

    private function handleRouteNotFoundException()
    {
        return response()->json($this->formatResponse('Route endpoint not found', 404), 404);
    }

    private function handleNotFoundHttpException()
    {
        return response()->json($this->formatResponse('Not found', 404), 404);
    }

    private function handleMethodNotAllowedHttpException()
    {
        return response()->json($this->formatResponse('The method is not allowed', 405), 405);
    }

    private function handleModelNotFoundException()
    {
        return response()->json($this->formatResponse('Model not found', 404), 404);
    }

    private function handleAuthorizationException()
    {
        return response()->json($this->formatResponse('You are not authorised to view this content', 403), 403);
    }

    private function formatResponse($message, $code)
    {
        return [
            'data' => [
                'code' => $code,
                'message' => $message
            ]
        ];
    }
}
