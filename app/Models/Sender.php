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
    public function shipments() {
        return $this->hasOne(Shipment::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $lastInvoice = static::max('invoiceId');
            $model->invoiceId = $lastInvoice ? $lastInvoice + 1 : 100;
        });
    }
    public function payments()
    {
        return $this->hasOne(Payment::class,);
    }
    public function dispatch()
    {
        return $this->hasOne(Dispatch::class,);
    }
    public function tracking()
    {
        return $this->hasOne(Tracking::class,);
    }
    public function billing()
    {
        return $this->hasMany(Billing::class,);
    }
}