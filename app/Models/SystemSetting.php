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
        $customPath = self::get('brand_logo_path');
        if ($customPath) {
            return asset('storage/' . $customPath);
        }

        return asset('images/roriri_logo.png');
    }
}
