<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id', 'type', 'filename', 'filepath'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
