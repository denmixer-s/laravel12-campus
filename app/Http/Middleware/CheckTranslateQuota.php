<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CheckTranslateQuota
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('translate.enabled')) {
            return response()->json([
                'error' => 'Translation service is currently disabled'
            ], 503);
        }

        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('Y-m');
        
        $dailyUsage = Cache::get("translate_daily_{$today}", 0);
        $monthlyUsage = Cache::get("translate_monthly_{$month}", 0);
        
        $dailyQuota = config('translate.daily_quota');
        $monthlyQuota = config('translate.monthly_quota');
        
        if ($dailyUsage >= $dailyQuota) {
            return response()->json([
                'error' => 'Daily translation quota exceeded',
                'quota' => $dailyQuota,
                'used' => $dailyUsage
            ], 429);
        }
        
        if ($monthlyUsage >= $monthlyQuota) {
            return response()->json([
                'error' => 'Monthly translation quota exceeded',
                'quota' => $monthlyQuota,
                'used' => $monthlyUsage
            ], 429);
        }
        
        return $next($request);
    }
}