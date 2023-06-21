<?php
 
namespace App\Exceptions;
 
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
 
class ApiException extends Exception
{
    private ?string $errCode = '';

    public function report(): void
    {
        // ...
    }
 
    public function errorCode(string $code): self
    {
        $this->errCode = $code;
        return $this;
    }
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        $err = [
            'code' => $this->errCode ?? $this->code,
            'message' => $this->message
        ];
        if (App::environment('dev','local')) {
            $err['file'] = $this->file;
            $err['line'] = $this->line;
        }
        $returnData = [
            'success' => false,
            'errors' => [
                $err
            ]
        ];
        return \Response::json($returnData, 422);
    }
}