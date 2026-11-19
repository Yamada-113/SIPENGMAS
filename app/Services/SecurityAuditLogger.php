<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityAuditLogger
{
    public function record(
        Request $request,
        string $action,
        string $result,
        ?string $npm = null,
        array $metadata = [],
    ): void {
        Log::notice('Security audit event', array_filter([
            'occurred_at' => now()->toIso8601String(),
            'npm' => $npm ?? $request->user()?->username ?? $request->input('username'),
            'ip' => $request->ip(),
            'action' => $action,
            'result' => $result,
            ...$metadata,
        ], fn ($value) => $value !== null));
    }
}
