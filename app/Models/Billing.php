<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $table = 'billing';

    protected $fillable = [
        'sender_id',
        'description',
        'quantity',
        'rate',
        'total',
    ];

    // Optional: Define relationship to Sender
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
}
