<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

class MathNumberHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'format_number.helper';
    }

    public static function getPercentage(float $number, float $percentage)
    {
        $percentageDecimal = $percentage / 100;

        return $number * $percentageDecimal;
    }
}
