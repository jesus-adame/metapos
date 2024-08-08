<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'branch_id', 'is_default'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function cashFlows()
    {
        return $this->hasMany(CashFlow::class);
    }

    public function cashCuts()
    {
        return $this->hasMany(CashCut::class);
    }
}
