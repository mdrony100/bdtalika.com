<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppAbandonedPaymentReminderService;
use Illuminate\Http\Request;

class WhatsAppAbandonedPaymentController extends Controller
{
    public function __invoke(Request $request, WhatsAppAbandonedPaymentReminderService $reminders)
    {
        $token = config('services.meta_whatsapp.cron_token');

        if (!$token || !hash_equals($token, (string) $request->query('token'))) {
            abort(403);
        }

        $limit = (int) $request->query('limit', config('services.meta_whatsapp.cron_limit', 25));
        $limit = max(1, min($limit, 100));

        return response()->json($reminders->run($limit));
    }
}
