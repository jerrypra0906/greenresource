<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'target_url',
        'order',
        'visible',
        'parent_id',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }
}

