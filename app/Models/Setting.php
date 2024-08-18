<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    const COMPANY_NAME = 'company_name';
    const COMPANY_ADDRESS = 'company_address';
    const COMPANY_PHONE = 'company_phone_number';
    const COMPANY_EMAIL = 'company_email';
    const COMPANY_RFC = 'company_rfc';

    protected $fillable = [
        'key',
        'value',
        'label'
    ];

    // Puedes agregar métodos de conveniencia para obtener y establecer configuraciones
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
