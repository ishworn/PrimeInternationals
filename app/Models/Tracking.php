<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    // Make these fields mass assignable
    protected $fillable = ['tracking_number', 'receiver_name', 'location'];

    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
}