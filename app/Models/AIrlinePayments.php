<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlinePayments extends Model
{
    use HasFactory;
    public $timestamps = false;
    // protected $guarded = []; 
    protected $table = 'airline_PAYMENT';
    protected $fillable = [

        
        'flight_charge', 
        'total_paid',
        'CustomClearence_payment',
        'foreign_agencies' ,   
        'payment_date'    ,   
        'shipment_id' ,
        'created_at' 
        // Assuming you want to store the shipment ID as well
      

    ];




   
}