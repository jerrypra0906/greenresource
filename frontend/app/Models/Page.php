<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'status',
        'banner',
    ];

    protected $casts = [
        'banner' => 'array',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }
}

