<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    use HasFactory;

    protected $fillable = [
        'expense_name', 'amount', 'date', 'category', 'other_category',
        'payment_method', 'receipt'
    ];
}
