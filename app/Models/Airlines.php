<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airlines extends Model
{
    use HasFactory;
    public $timestamps = false;
    // protected $guarded = []; 
    protected $table = 'airlines';
    protected $fillable = [

        
        'id',
        'name'
      

    ];




   
}