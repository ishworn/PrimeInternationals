<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;

    // protected $guarded = []; 
    protected $table = 'senders';
    protected $fillable = [
        'id',
        'senderName',
        'senderPhone',
        'senderEmail',
        'senderAddress',
   
        'trackingId',

    ];

    public function boxes() {
        return $this->hasMany(Box::class);
    }
    public function receiver() {
        return $this->hasOne(Receiver::class);
    }
    public function shipment() {
        return $this->hasOne(Shipment::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $lastInvoice = static::max('invoiceId');
            $model->invoiceId = $lastInvoice ? $lastInvoice + 1 : 100;
        });
    }
}