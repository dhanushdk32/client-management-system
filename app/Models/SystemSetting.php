<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function set($key, $value)
    {
        try {
            return self::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAllSettings()
    {
        try {
            return self::pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get active brand logo URL
     */
    public static function getBrandLogoUrl()
    {
        $logoType = self::get('brand_logo_type', 'preset');
        if ($logoType === 'custom_upload') {
            $customPath = self::get('brand_logo_path');
            if ($customPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($customPath)) {
                return asset('storage/' . $customPath);
            }
        }

        $preset = self::get('brand_logo_preset', 'original');
        if ($preset === 'original' || empty($preset)) {
            return asset('images/roriri_logo.png');
        }

        return asset('images/presets/' . $preset . '.svg');
    }
}
