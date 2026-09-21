<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'status',
        'slug',
        'banner',
        'meta_title',
        'meta_description',
        'meta_tags',
    ];
}
