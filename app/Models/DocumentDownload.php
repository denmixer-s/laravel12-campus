<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDownload extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
        'referer',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    // Relationships
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByDocument($query, $documentId)
    {
        return $query->where('document_id', $documentId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('user_type', 'anonymous');
    }

    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('downloaded_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('downloaded_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('downloaded_at', now()->month)
                    ->whereYear('downloaded_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('downloaded_at', now()->year);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('downloaded_at', [$startDate, $endDate]);
    }

    // Accessors
    public function getUserTypeLabel(): string
    {
        return match($this->user_type) {
            'staff' => 'เจ้าหน้าที่',
            'public' => 'บุคคลทั่วไป',
            'anonymous' => 'ผู้เยี่ยมชม',
            default => $this->user_type,
        };
    }

    public function getBrowserInfoAttribute(): array
    {
        if (!$this->user_agent) {
            return ['browser' => 'Unknown', 'platform' => 'Unknown'];
        }

        $browser = 'Unknown';
        $platform = 'Unknown';

        // Simple browser detection
        if (str_contains($this->user_agent, 'Chrome')) $browser = 'Chrome';
        elseif (str_contains($this->user_agent, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($this->user_agent, 'Safari')) $browser = 'Safari';
        elseif (str_contains($this->user_agent, 'Edge')) $browser = 'Edge';

        // Simple platform detection
        if (str_contains($this->user_agent, 'Windows')) $platform = 'Windows';
        elseif (str_contains($this->user_agent, 'Mac')) $platform = 'macOS';
        elseif (str_contains($this->user_agent, 'Linux')) $platform = 'Linux';
        elseif (str_contains($this->user_agent, 'Android')) $platform = 'Android';
        elseif (str_contains($this->user_agent, 'iPhone') || str_contains($this->user_agent, 'iPad')) $platform = 'iOS';

        return ['browser' => $browser, 'platform' => $platform];
    }

    public function getDownloadSourceAttribute(): string
    {
        if (!$this->referer) {
            return 'Direct';
        }

        $host = parse_url($this->referer, PHP_URL_HOST);
        return $host ?? 'Unknown';
    }

    // Static methods for statistics
    public static function getDownloadStats($documentId = null, $period = 'month')
    {
        $query = static::query();

        if ($documentId) {
            $query->where('document_id', $documentId);
        }

        return match($period) {
            'today' => $query->today()->count(),
            'week' => $query->thisWeek()->count(),
            'month' => $query->thisMonth()->count(),
            'year' => $query->thisYear()->count(),
            default => $query->count(),
        };
    }

    public static function getTopDocuments($limit = 10, $period = 'month')
    {
        $query = static::query();

        match($period) {
            'today' => $query->today(),
            'week' => $query->thisWeek(),
            'month' => $query->thisMonth(),
            'year' => $query->thisYear(),
        };

        return $query->selectRaw('document_id, COUNT(*) as download_count')
                    ->groupBy('document_id')
                    ->orderByDesc('download_count')
                    ->limit($limit)
                    ->with('document')
                    ->get();
    }

    public static function getUserTypeStats($period = 'month')
    {
        $query = static::query();

        match($period) {
            'today' => $query->today(),
            'week' => $query->thisWeek(),
            'month' => $query->thisMonth(),
            'year' => $query->thisYear(),
        };

        return $query->selectRaw('user_type, COUNT(*) as count')
                    ->groupBy('user_type')
                    ->get()
                    ->pluck('count', 'user_type')
                    ->toArray();
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($download) {
            $download->downloaded_at = now();

            // Set user type if not provided
            if (!$download->user_type) {
                if ($download->user_id) {
                    $user = User::find($download->user_id);
                    $download->user_type = $user->user_type ?? 'public';
                } else {
                    $download->user_type = 'anonymous';
                }
            }
        });
    }
}
