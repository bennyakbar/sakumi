<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'class_id',
        'category_id',
        'gender',
        'birth_date',
        'birth_place',
        'parent_name',
        'parent_phone',
        'parent_whatsapp',
        'address',
        'status',
        'enrollment_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function category()
    {
        return $this->belongsTo(StudentCategory::class, 'category_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function obligations()
    {
        return $this->hasMany(StudentObligation::class);
    }
}
