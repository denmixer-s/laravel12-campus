<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogSetting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'description', 'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Helper methods
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $type = 'string'): void
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = is_array($value) ? json_encode($value) : $value;
        $setting->type = $type;
        $setting->save();
    }
}
