<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencies extends Model
{
    use HasFactory;
    public $timestamps = false;
    // protected $guarded = []; 
    protected $table = 'agencies';
    protected $fillable = [

        
        'id',
        'name',
        'created_at'
     
       

    ];




   
}