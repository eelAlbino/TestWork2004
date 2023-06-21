<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use Exception;

class Handler extends ExceptionHandler
{

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation'
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ApiException) {
            return $this->handleApiException($exception, $request);
        }
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($exception);
        }
        elseif ($exception instanceof AuthorizationException) {
            return $this->handleAuthorizationException($exception);
        }
        elseif ($exception instanceof AuthenticationException) {
            return $this->handleAuthorizationException($exception);
        }
        elseif ($exception instanceof Exception) {
            return $this->handleException($exception);
        }

        return parent::render($request, $exception);
    }

    protected function handleApiException(ApiException $exception, Request $request)
    {
        return $exception->render($request);
    }

    protected function handleValidationException(ValidationException $exception)
    {
        $errors = collect($exception->errors())->map(function ($messages, $field) {
            return [
                'code' => $field,
                'message' => $messages[0]
            ];
        });

        return response()->json([
            'success' => false,
            'errors' => $errors
        ], $exception->status);
    }

    protected function handleAuthorizationException($exception)
    {
        return response()->json([
            'success' => false,
            'errors' => [
                [
                    'code' => 'unauthorized',
                    'message' => $exception->getMessage()
                ]
            ]
        ], 401);
    }

    protected function handleException(Exception $exception)
    {
        return response()->json([
            'success' => false,
            'errors' => [
                [
                    'code' => 'unknow',
                    'message' => $exception->getMessage()
                ]
            ]
        ], 500);
    }
}
