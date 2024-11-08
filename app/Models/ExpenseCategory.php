<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
}
