<?php

namespace App\Http\Middleware;

use App\Services\BangladeshPhoneService;
use Closure;

class RequireWhatsappProfile
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (
            $user
            && $user->social_login === 'yes'
            && !BangladeshPhoneService::isValid($user->phone)
        ) {
            session()->put('url.intended', $request->fullUrl());

            return redirect()
                ->route('user.profile.index')
                ->with('warning', 'Please enter your WhatsApp number to continue.');
        }

        return $next($request);
    }
}
