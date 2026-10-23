<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckListingSteps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle($request, Closure $next)
    // {
    //     $type   = $request->route('type');
    //     if ($request->routeIs('user.listing.create.step2') && !$this->hasCompletedStepOne($type)) {
    //         return redirect()->route('user.listing.create', ['type' => $type]);
    //     }
    //     if ($request->routeIs('user.listing.create.step3') && !$this->hasCompletedStepTwo($type)) {
    //         return redirect()->route('user.listing.create.step2', ['type' => $type]);
    //     }
    //     if ($this->hasCompletedStepOne($type) && $request->routeIs('user.listing.create')) {
    //         return redirect()->route('user.listing.create.step2', ['type' => $type]);
    //     }
    //     if ($this->hasCompletedStepTwo($type) && $request->routeIs('user.listing.create.step2')) {
    //         return redirect()->route('user.listing.create.step3', ['type' => $type]);
    //     }
    //     return $next($request);
    // }


    public function handle($request, Closure $next)
    {
        if ($request->routeIs('user.listing.create.step2') && !$this->hasCompletedStepOne()) {
            return redirect()->route('user.listing.create');
        }
        if ($request->routeIs('user.listing.create.step3') && !$this->hasCompletedStepTwo()) {
            return redirect()->route('user.listing.create.step2');
        }
        if ($this->hasCompletedStepOne() && $request->routeIs('user.listing.create')) {
            return redirect()->route('user.listing.create.step2');
        }
        if ($this->hasCompletedStepTwo() && $request->routeIs('user.listing.create.step2')) {
            return redirect()->route('user.listing.create.step3');
        }
        return $next($request);
    }


    private function hasCompletedStepOne()
    {
        $stepOneCompleted = Session::get('stepOne', false);
        return $stepOneCompleted;
    }

    private function hasCompletedStepTwo()
    {
        $stepTwoCompleted = Session::get('stepTwo', false);
        return $stepTwoCompleted;
    }
}
