<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    /**
     * Render the given exception.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($oRequest, Throwable $e)
    {
        $statusCode = $e->getCode() ?: 400;
        $errorCode = $e->getCode();

        $errorResponse = [
            'status' => 'failure',
            'error' => [
                'code' => $errorCode,
                'message' => $e->getMessage(),
            ],
        ];

        return response()->json($errorResponse, $statusCode);
    }

}
