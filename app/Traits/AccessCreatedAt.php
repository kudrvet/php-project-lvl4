<?php

namespace App\Traits;

use Carbon\Carbon;

trait AccessCreatedAt
{
    protected $newDateFormat = 'd.m.Y';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format($this->newDateFormat);
    }
}
