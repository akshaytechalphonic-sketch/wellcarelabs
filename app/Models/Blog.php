<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'featured_image',
        'status',
        'category_id',
    ];

    /**
     * Default values
     */
    protected $attributes = [
        'status' => 'Draft',
    ];

    /**
     * Model boot
     */
    protected static function booted()
    {
        static::creating(function ($blog) {
            // Auto-generate slug
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
        });
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }



    public function getContentWithLinksAttribute()
    {
        if (!$this->content) return '';

        // Convert plain URLs into clickable links
        $text = preg_replace(
            '/(https?:\/\/[^\s<]+|www\.[^\s<]+|[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $this->content
        );

        // If link doesn't start with http, add https://
        $text = preg_replace(
            '/href="(?!https?:\/\/)(www\.[^"]+)"/i',
            'href="https://$1"',
            $text
        );

        $text = preg_replace(
            '/href="(?!https?:\/\/)([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})"/i',
            'href="https://$1"',
            $text
        );

        return $text;
    }

    /**
     * Get the category that this blog belongs to.
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
