<?php

<<<<<<< Updated upstream
=======
use App\Http\Middleware\EnforceAbsoluteSessionLifetime;
use App\Http\Middleware\RecordForbiddenAccess;
use App\Services\IncidentReporter;
use App\Services\SecurityAuditLogger;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
>>>>>>> Stashed changes
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< Updated upstream
        //
=======
        $middleware->alias([
            'session.absolute' => EnforceAbsoluteSessionLifetime::class,
        ]);
        $middleware->append(RecordForbiddenAccess::class);
>>>>>>> Stashed changes
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $statusCode = function (Throwable $exception): int {
            if ($exception instanceof ValidationException) {
                return $exception->status;
            }

            if ($exception instanceof AuthorizationException) {
                return 403;
            }

            if ($exception instanceof AuthenticationException) {
                return 401;
            }

            if ($exception instanceof ModelNotFoundException) {
                return 404;
            }

            if ($exception instanceof HttpResponseException) {
                return $exception->getResponse()->getStatusCode();
            }

            return $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;
        };

        $exceptions->report(function (Throwable $exception) use ($statusCode): ?bool {
            $status = $statusCode($exception);

            if ($status === 403 || $status >= 500) {
                if ($status >= 500) {
                    app(IncidentReporter::class)->report($exception, request());
                }

                return false;
            }

            return null;
        });

        $exceptions->render(function (Throwable $exception, Request $request) use ($statusCode) {
            $status = $statusCode($exception);

            if ($status !== 403 && $status < 500) {
                return null;
            }

            $incidentReporter = app(IncidentReporter::class);
            $incidentId = $incidentReporter->incidentId($request);

            if ($status === 403) {
                app(SecurityAuditLogger::class)->record(
                    $request,
                    'access_denied',
                    'denied',
                    metadata: [
                        'incident_id' => $incidentId,
                        'route' => $request->route()?->getName(),
                    ],
                );
            } else {
                $incidentReporter->report($exception, $request);
            }

            $message = $incidentReporter->message($incidentId, accessDenied: $status === 403);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'incident_id' => $incidentId,
                ], $status);
            }

            return response()->view('errors.generic', [
                'message' => $message,
                'incidentId' => $incidentId,
            ], $status);
        });
    })->create();
