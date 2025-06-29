<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TranslationService
{
    protected $translate;

    public function __construct()
    {
        $this->translate = new GoogleTranslate();
    }

    public function translateText($text, $targetLanguage, $sourceLanguage = null)
    {
        if (!$this->canTranslate($text)) {
            throw new \Exception('Translation quota exceeded');
        }

        try {
            if ($sourceLanguage) {
                $this->translate->setSource($sourceLanguage);
            } else {
                $this->translate->setSource('th');
            }
            
            $this->translate->setTarget($targetLanguage);
            $result = $this->translate->translate($text);

            $this->recordUsage($text);
            return $result;
        } catch (\Exception $e) {
            Log::error('Translation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function canTranslate($text)
    {
        if (!config('translate.enabled')) {
            return false;
        }

        $charCount = mb_strlen($text);
        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('Y-m');
        
        $dailyUsage = Cache::get("translate_daily_{$today}", 0);
        $monthlyUsage = Cache::get("translate_monthly_{$month}", 0);
        
        $dailyQuota = config('translate.daily_quota');
        $monthlyQuota = config('translate.monthly_quota');
        
        return ($dailyUsage + $charCount <= $dailyQuota) && 
               ($monthlyUsage + $charCount <= $monthlyQuota);
    }

    protected function recordUsage($text)
    {
        $charCount = mb_strlen($text);
        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('Y-m');
        
        $dailyUsage = Cache::get("translate_daily_{$today}", 0);
        Cache::put("translate_daily_{$today}", $dailyUsage + $charCount, Carbon::tomorrow());
        
        $monthlyUsage = Cache::get("translate_monthly_{$month}", 0);
        Cache::put("translate_monthly_{$month}", $monthlyUsage + $charCount, Carbon::now()->endOfMonth());
    }

    public function getUsageStats()
    {
        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('Y-m');
        
        return [
            'daily' => [
                'used' => Cache::get("translate_daily_{$today}", 0),
                'quota' => config('translate.daily_quota'),
                'remaining' => config('translate.daily_quota') - Cache::get("translate_daily_{$today}", 0)
            ],
            'monthly' => [
                'used' => Cache::get("translate_monthly_{$month}", 0),
                'quota' => config('translate.monthly_quota'),
                'remaining' => config('translate.monthly_quota') - Cache::get("translate_monthly_{$month}", 0)
            ]
        ];
    }
}