<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class IncidentReporter
{
    public function incidentId(Request $request): string
    {
        $incidentId = $request->attributes->get('incident_id');

        if (! is_string($incidentId)) {
            $incidentId = 'ERR-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
            $request->attributes->set('incident_id', $incidentId);
        }

        return $incidentId;
    }

    public function report(Throwable $exception, Request $request, string $operation = 'request'): string
    {
        $incidentId = $this->incidentId($request);

        if ($request->attributes->get('incident_logged')) {
            return $incidentId;
        }

        $request->attributes->set('incident_logged', true);

        Log::error('Application request failed', [
            'incident_id' => $incidentId,
            'operation' => $operation,
            'exception_class' => $exception::class,
            'previous_exception_class' => $exception->getPrevious() ? $exception->getPrevious()::class : null,
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'npm' => $request->user()?->username ?? $request->input('username'),
            'ip' => $request->ip(),
        ]);

        return $incidentId;
    }

    public function message(string $incidentId, bool $accessDenied = false): string
    {
        $message = $accessDenied
            ? 'Akses ditolak.'
            : 'Terjadi kesalahan. Silakan coba lagi.';

        return $message.' ID kejadian: '.$incidentId;
    }
}
