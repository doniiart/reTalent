<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk pengaturan konfigurasi sistem (key-value)
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Helper untuk mengambil nilai konfigurasi berdasarkan key
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
