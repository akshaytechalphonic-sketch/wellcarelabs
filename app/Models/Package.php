<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
    'title',
    'description',
    'mrp',
    'discounted_price',
    'banner',
    'status',
    'slug',
    'content',
    'is_special',
    'special_label',
    'meta_tags',
    'package_code',
    'sample_type',
    'fasting',
    'parameters_count',
    'why_done',
    'who_should_test',
    'how_to_read',
    'what_to_ask',
    'meta_title',
    'meta_description',
    'image_alt',
    'faqs',
];


    protected $casts = [
        'mrp' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'faqs' => 'array',
    ];

    // Helper to output banner full URL (null-safe)
    public function getBannerUrlAttribute(): ?string
    {
        if (empty($this->banner)) return null;
        return asset('storage/' . ltrim($this->banner, '/'));
    }

    // optional convenience accessor for status normalized
    public function getIsPublishedAttribute(): bool
    {
        return strtolower($this->status) === 'Published';
    }

    /**
     * Get the associated tests for this package.
     */
    public function tests()
    {
        return $this->belongsToMany(LabTest::class, 'package_lab_test', 'package_id', 'lab_test_id');
    }
}
