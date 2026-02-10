<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FeeType;
use App\Models\SchoolClass;
use App\Models\StudentCategory;

class FeeMatrix extends Model
{
    use HasFactory;

    protected $table = 'fee_matrix';

    protected $fillable = [
        'fee_type_id',
        'class_id',
        'category_id',
        'amount',
        'effective_from',
        'effective_to',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function category()
    {
        return $this->belongsTo(StudentCategory::class, 'category_id');
    }
}
