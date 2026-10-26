<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting);
    }

    public static function castValue(self $setting): mixed
    {
        return match ($setting->type) {
            'integer' => (int) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function getLocalized(string $key, ?string $locale = null, mixed $default = null): mixed
    {
        $locale = $locale ?: app()->getLocale();
        $localizedKey = "{$key}_{$locale}";

        $setting = static::where('key', $localizedKey)->first();
        if ($setting && $setting->value !== null && $setting->value !== '') {
            return static::castValue($setting);
        }

        // Fall back to default English or base key
        $fallbackKey = "{$key}_en";
        $fallback = static::where('key', $fallbackKey)->first() ?? static::where('key', $key)->first();
        if ($fallback && $fallback->value !== null && $fallback->value !== '') {
            return static::castValue($fallback);
        }

        return $default;
    }

    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        $val = is_array($value) ? json_encode($value) : (string) $value;
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $val, 'type' => $type]
        );
    }

    public static function setLocalized(string $key, string $locale, mixed $value, string $type = 'string'): void
    {
        $localizedKey = "{$key}_{$locale}";
        static::set($localizedKey, $value, $type);

        // If setting English, also update base key as fallback
        if ($locale === 'en' || $locale === config('dearly.default_locale', 'en')) {
            static::set($key, $value, $type);
        }
    }

    public static function getAllLocalized(string $key): array
    {
        $supportedLocales = config('dearly.supported_locales', []);
        $results = [];

        foreach ($supportedLocales as $code => $info) {
            $localizedKey = "{$key}_{$code}";
            $setting = static::where('key', $localizedKey)->first();
            if ($setting) {
                $results[$code] = static::castValue($setting);
            } else {
                $results[$code] = ($code === 'en') ? static::get($key) : null;
            }
        }

        return $results;
    }
}
