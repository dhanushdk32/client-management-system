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
            if ($setting && $setting->value !== null) {
                $val = $setting->value;
                if (is_string($val) && stripos($val, 'RORIRI') !== false) {
                    if ($key === 'brand_name' || $key === 'company_name') {
                        return 'Client Management System';
                    }
                    if ($key === 'brand_tagline') {
                        return '';
                    }
                    if ($key === 'company_email') {
                        return 'contact@clientmanagementsystem.com';
                    }
                }
                return $val;
            }
            return $default;
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
            $settings = self::pluck('value', 'key')->toArray();
            foreach ($settings as $key => $val) {
                if (is_string($val) && stripos($val, 'RORIRI') !== false) {
                    if ($key === 'brand_name' || $key === 'company_name') {
                        $settings[$key] = 'Client Management System';
                    } elseif ($key === 'brand_tagline') {
                        $settings[$key] = '';
                    } elseif ($key === 'company_email') {
                        $settings[$key] = 'contact@clientmanagementsystem.com';
                    }
                }
            }
            return $settings;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get active brand logo URL with automatic cache busting
     */
    public static function getBrandLogoUrl()
    {
        $customPath = self::get('brand_logo_path');
        if ($customPath && !str_contains(strtolower($customPath), 'roriri')) {
            $fullPath = storage_path('app/public/' . $customPath);
            if (file_exists($fullPath)) {
                return asset('storage/' . $customPath . '?v=' . filemtime($fullPath));
            }
        }

        $logoFile = public_path('images/logo.png');
        $version = file_exists($logoFile) ? filemtime($logoFile) : time();
        return asset('images/logo.png?v=' . $version);
    }
}
