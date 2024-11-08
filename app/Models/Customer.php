<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model implements Auditable
{
    use HasFactory, AuditingAuditable;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'address',
    ];

    protected $appends = ['sales_count'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getSalesCountAttribute()
    {
        return $this->sales()->count();
    }
}
