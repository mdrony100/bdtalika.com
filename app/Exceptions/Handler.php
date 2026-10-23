<?php

namespace App\Exceptions;

use Throwable;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
            $this->logServerError($e);
        });
    }

    /**
     * Persist server (5xx) errors so admins can review what visitors faced.
     */
    protected function logServerError(Throwable $e): void
    {
        $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        // Only record genuine server errors, not 404/403/validation/etc.
        if ($status < 500) {
            return;
        }

        // Never let logging break the response.
        try {
            $request = request();

            ErrorLog::create([
                'status_code' => $status,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request?->fullUrl(),
                'method' => $request?->method(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'trace' => $e->getTraceAsString(),
            ]);
        } catch (\Throwable $ex) {
            Log::error('Error log write failed', ['error' => $ex->getMessage()]);
        }
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/admin/login');
        }
        if ($request->is('user') || $request->is('user/*')) {
            return redirect()->route('front.index')->with('warning','login first');
        }
        // return redirect()->guest(route('user.login'));
        return redirect()->guest(route('front.index'));
    }


}
