<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'creator_id',
        'expense',
        'expense_date',
        'amount',
        'status',
        'description'
    ];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
