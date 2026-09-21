<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LabTest extends Model
{
    protected $table = 'lab_tests';

    protected $fillable = [
        'test_name',
        'slug',
        'test_code',
        'sample_type',
        'fasting',
        'parameters_count',
        'mrp',
        'b2b',
        'discounted_price',
        'description',
        'why_done',
        'who_should_test',
        'how_to_read',
        'what_to_ask',
        'status',
        'meta_title',
        'meta_description',
        'meta_tags',
        'page_id',
    ];

    /**
     * Get the associated custom dynamic page for this test.
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Get the associated packages for this test.
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_lab_test', 'lab_test_id', 'package_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($labTest) {
            if (empty($labTest->slug)) {
                $slug = Str::slug($labTest->test_name);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $labTest->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $labTest->slug = $slug;
            }
        });
    }
}
