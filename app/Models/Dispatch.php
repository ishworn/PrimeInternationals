<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'dispatch';

    // Fields that are mass assignable
    protected $fillable = [
        'sender_id',
        'status',
        'dispatch_by',
        'dispatched_at',
    ];

    /**
     * Get the sender associated with the dispatch.
     */
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }

    // Optionally, if you want to define relationships with other models (e.g., receiver, shipment), you can add those too.
}
