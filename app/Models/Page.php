<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $table = 'dynamic_pages';

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'schema_markup',
        'meta_tags',
        'status',
        'banner_image',
        'image_alt'
    ];

    /**
     * Get all sections for the page.
     */
    public function sections()
    {
        return $this->morphMany(Section::class, 'sectionable')->orderBy('sort_order');
    }

    /**
     * Get associated packages for the page.
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'page_package', 'page_id', 'package_id');
    }

    /**
     * Get associated FAQs for the page.
     */
    public function faqs()
    {
        return $this->belongsToMany(Faq::class, 'page_faq', 'page_id', 'faq_id');
    }

    /**
     * Boot function to automatically generate a unique slug on saving.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($page) {
            if (empty($page->slug)) {
                $slug = Str::slug($page->title);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $page->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                $page->slug = $slug;
            }
        });
    }

    public static function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column === 'status') {
            $val = ($value === null) ? $operator : $value;
            $mapped = ($val === 'Published') ? 1 : 0;
            if ($value === null) {
                $operator = $mapped;
            } else {
                $value = $mapped;
            }
        }
        return (new static)->newQuery()->where($column, $operator, $value, $boolean);
    }

    public function getContentAttribute()
    {
        return $this->sections
            ->map(function($s) {
                if ($s->type === 'text') {
                    $t = $s->content['title'] ?? '';
                    $b = $s->content['body'] ?? '';
                    return ($t ? "<h3>{$t}</h3>" : "") . $b;
                } elseif ($s->type === 'quote') {
                    return "<blockquote>" . ($s->content['body'] ?? '') . "</blockquote>";
                } elseif ($s->type === 'biography' || $s->type === 'profile') {
                    return "<h3>" . ($s->content['title'] ?? '') . "</h3>" . ($s->content['body'] ?? '');
                }
                return "";
            })
            ->filter()
            ->implode("\n");
    }

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Published' : 'Draft';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value === 'Published' ? 1 : 0;
    }
}
