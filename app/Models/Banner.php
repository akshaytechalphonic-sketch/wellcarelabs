<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'image', 'image_hash', 'is_active', 'url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // convenience accessor to return the public URL for the image
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
