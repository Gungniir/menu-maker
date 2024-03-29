<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function register(): void
    {
        $this->renderable(function (ReportableException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
                'route' => $request->decodedPath(),
            ], 404);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
