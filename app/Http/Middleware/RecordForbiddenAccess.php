<?php

namespace App\Http\Middleware;

use App\Services\IncidentReporter;
use App\Services\SecurityAuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordForbiddenAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() !== 403) {
            return $response;
        }

        $incidentId = app(IncidentReporter::class)->incidentId($request);

        app(SecurityAuditLogger::class)->record(
            $request,
            'access_denied',
            'denied',
            metadata: [
                'incident_id' => $incidentId,
                'route' => $request->route()?->getName(),
            ],
        );

        $message = app(IncidentReporter::class)->message($incidentId, accessDenied: true);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'incident_id' => $incidentId,
            ], 403);
        }

        return response()->view('errors.generic', [
            'message' => $message,
            'incidentId' => $incidentId,
        ], 403);
    }
}
