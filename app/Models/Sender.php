<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Sender extends Model
{
    use HasFactory, SoftDeletes;

    // protected $guarded = []; 
    protected $table = 'senders';
    protected $fillable = [
        'id',
        'senderName',
        'senderPhone',
        'senderEmail',
        'senderAddress',
        'trackingId',
        'status',
        'address1',
        'address2',
        'address3',
        'company_name',
        'vendor_id',

    ];

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
    public function receiver()
    {
        return $this->hasOne(Receiver::class);
    }
    public function shipments()
    {
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
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
