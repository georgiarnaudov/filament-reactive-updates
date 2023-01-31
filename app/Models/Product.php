<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'type',
        'description',
        'short_description',
        'status',
        'price',
        'weight',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}