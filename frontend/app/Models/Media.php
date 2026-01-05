<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'url',
        'mime_type',
        'file_size',
        'alt_text',
    ];

    public function getUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }
        return asset('storage/' . $this->file_path);
    }
}

