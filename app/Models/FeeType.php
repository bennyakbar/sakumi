<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_monthly',
        'is_active',
    ];

    protected $casts = [
        'is_monthly' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function feeMatrix()
    {
        return $this->hasMany(FeeMatrix::class);
    }
}
