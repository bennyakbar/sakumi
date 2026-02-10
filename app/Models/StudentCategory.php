<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_percentage',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'category_id');
    }
}
