<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipments extends Model
{
    use HasFactory;
    public $timestamps = false;
    // protected $guarded = []; 
    protected $table = 'shipment';
    protected $fillable = [

        
        'id',
        'shipment_number',
        'sender_id',
        'created_at',
        'updated_at',

    ];


    protected $casts = [
    'sender_id' => 'array',
];


   
}