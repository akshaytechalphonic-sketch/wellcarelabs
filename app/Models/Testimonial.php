<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'designation',
        'rating',
        'review',
        'video_url',
        'image',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get user avatar URL or fallback letter placeholder.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->image)) {
            return asset('storage/' . ltrim($this->image, '/'));
        }

        // Return a UI Avatars API fallback based on the user's name
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=eff6ff&color=2563eb&bold=true&size=128';
    }

    /**
     * Parse YouTube link and convert to a clean embeddable iframe URL.
     */
    public function getEmbedVideoUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
        if (preg_match($pattern, $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $this->video_url;
    }
}
