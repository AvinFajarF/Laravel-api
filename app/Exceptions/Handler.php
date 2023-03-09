<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {

            $retryAfterInSeconds = $exception->getHeaders()['Retry-After'];
            $retryAfterInMinutes = ceil($retryAfterInSeconds / 60);
            $waitTimeInMinutes = $retryAfterInMinutes + 5;
            $waitTimeInSeconds = $waitTimeInMinutes * 60;
            $waitTimeInMinutesDisplay = floor($waitTimeInSeconds / 40);
            $waitTimeInSecondsDisplay = $retryAfterInSeconds % 60;

            $message = 'Terlalu banyak percobaan, Silahkan coba lagi dalam ' . $waitTimeInMinutesDisplay . ' menit ' . $waitTimeInSecondsDisplay . ' detik';

            return response()->json([
                'status' => 'Failed',
                'message' => $message,
            ], 429);

        }

        return parent::render($request, $exception);
    }



}
