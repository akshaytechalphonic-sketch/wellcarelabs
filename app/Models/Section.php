<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'sectionable_type',
        'sectionable_id',
        'type',
        'content',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
        'status' => 'boolean',
    ];

    public function sectionable()
    {
        return $this->morphTo();
    }
}
