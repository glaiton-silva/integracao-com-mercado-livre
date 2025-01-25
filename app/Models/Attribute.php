<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'category_id',
        'attribute_id',
        'name',
        'value',
        'value_type',
        'tags',
        'values',
    ];

    protected $casts = [
        'tags' => 'array',
        'values' => 'array',
    ];
}
