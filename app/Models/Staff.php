<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staffs';
    protected $fillable = [
        'id',
        'staffName',
        'staffPhone',
        'staffEmail',
        'staff_Address',
        'staffSalary',
        'join_date',
        'department',


    ];
    protected $casts = [
        'staff_documents' => 'array',
    ];
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    
}
