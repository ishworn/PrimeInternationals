<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, Laravel will auto-detect it)
    protected $table = 'payments';

    // Define fillable columns for mass assignment (optional)
    protected $fillable = [
        
        'bill_amount',
        'payment_method',
        'cash_amount',
        'bank_amount',
        'total_paid',
        'debits',
        
        'status',
        'sender_id',
    ];

    // Define castings for specific columns (optional)
    protected $casts = [
        'amount' => 'decimal:2', // Cast the 'amount' column to a decimal with 2 decimal places
    ];

    public function sender()
    {
        return $this->belongsTo(Sender::class,);
    }
}