<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable =['expense_category_id', 'amount', 'note'];
    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
