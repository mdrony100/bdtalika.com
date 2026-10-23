<?php

namespace App\Services;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserLogService
{
    public function log(
        string $action,
        ?string $description = null,
        array $data = [],
        ?int $userId = null,
        ?int $adminId = null,
        ?Request $request = null
    ): void {
        try {
            $request = $request ?: request();

            UserLog::create([
                'user_id' => $userId ?? (Auth::check() ? Auth::id() : null),
                'admin_id' => $adminId ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->id() : null),
                'action' => $action,
                'description' => $description,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'url' => $request?->fullUrl(),
                'method' => $request?->method(),
                'data' => !empty($data) ? $data : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('User log write failed', [
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
