<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        //
    }

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof NotFoundHttpException) {
    //         // Redirect ke halaman atau rute yang diinginkan
    //         return view('layouts.error-404');
    //     }
    //     if ($exception instanceof HttpException && $exception->getStatusCode() == 500) {
    //         // Redirect atau tampilkan view khusus untuk error 500
    //         return view('layouts.error-500');
    //         // atau
    //         // return view('500_view');
    //     }
    //     return parent::render($request, $exception);
    // }
}
