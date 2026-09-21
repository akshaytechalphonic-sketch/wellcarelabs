@extends('maindesign')

@section('title', $blog->title . ' - Wellcare Labs')

@section('content')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
/* ---------- ROOT VARIABLES ---------- */
:root {
    --bg-body: #f5f7fb;
    --card-bg: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #334155;
    --text-muted: #64748b;
    --border-light: #e9eef3;
    --accent: #0d6efd;
    --accent-hover: #0b5ed7;
    --accent-soft: #eef2ff;
    --radius-card: 28px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg-body);
    color: var(--text-primary);
    line-height: 1.6;
}

/* Progress Bar */
.progress-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: rgba(0,0,0,0.05);
    z-index: 9999;
}
.progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #0d6efd, #00c6ff);
    transition: width 0.08s linear;
}

/* Main Layout */
.blog-wrapper {
    max-width: 1000px;
    margin: 1.5rem auto;
    padding: 0 1rem;
}

.blog-card {
    background: var(--card-bg);
    border-radius: var(--radius-card);
    overflow: hidden;
    box-shadow: 0 10px 30px -12px rgba(0,0,0,0.08);
    border: 1px solid var(--border-light);
}

/* ========== HERO IMAGE - FULL SIZE, NO CROP ========== */
.hero-section {
    width: 100%;
    background: var(--bg-body);
    text-align: left;
}
.hero-image {
    width: 100%;
    height: auto;
    display: block;
    margin: 0;
    object-fit: contain;
    background: var(--bg-body);
}

/* Title - Left aligned */
.title-wrapper {
    padding: 1.5rem 1.8rem 0.5rem;
    text-align: left;
}
.blog-title {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1.3;
    color: var(--text-primary);
    margin: 0;
}
@media (min-width: 768px) {
    .blog-title {
        font-size: 2.4rem;
    }
}

/* Content Area */
.content-area {
    padding: 0 1.5rem 2rem;
}
@media (min-width: 768px) {
    .content-area {
        padding: 0 2rem 2.5rem;
    }
}

/* Meta Bar - Left aligned */
.meta-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0 1.2rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-light);
}
.meta-left {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.8rem;
    color: var(--text-muted);
}
.meta-left i {
    margin-right: 4px;
}
.share-buttons {
    display: flex;
    gap: 0.6rem;
}
.share-buttons a, .share-buttons button {
    background: var(--accent-soft);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary);
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    transition: 0.2s;
}
.share-buttons a:hover, .share-buttons button:hover {
    background: var(--accent);
    color: white;
}

/* Short Description - Left aligned */
.short-desc {
    font-size: 1.1rem;
    color: var(--text-secondary);
    background: var(--accent-soft);
    padding: 0.8rem 1.2rem;
    border-radius: 18px;
    margin-bottom: 1.8rem;
    border-left: 4px solid var(--accent);
    text-align: left;
}

/* ========== CKEDITOR CONTENT ========== */
.ck-content {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--text-secondary);
    text-align: left;
}
.ck-content p {
    margin-bottom: 1.2rem;
}
.ck-content h2 {
    font-size: 1.6rem;
    font-weight: 700;
    margin: 1.8rem 0 0.8rem;
    color: var(--text-primary);
}
.ck-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 1.4rem 0 0.6rem;
    color: var(--text-primary);
}
.ck-content img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin: 1.2rem 0;
    display: block;
}
/* Aligned images from CKEditor */
.ck-content .image-style-align-left {
    float: left;
    margin-right: 1.2rem;
    margin-bottom: 0.8rem;
    max-width: 45%;
}
.ck-content .image-style-align-right {
    float: right;
    margin-left: 1.2rem;
    margin-bottom: 0.8rem;
    max-width: 45%;
}
@media (max-width: 640px) {
    .ck-content .image-style-align-left,
    .ck-content .image-style-align-right {
        float: none;
        margin: 1rem auto;
        max-width: 100%;
    }
}
.ck-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.2rem 0;
    border-radius: 14px;
    overflow-x: auto;
    display: block;
}
.ck-content th, .ck-content td {
    border: 1px solid var(--border-light);
    padding: 10px 14px;
    text-align: left;
}
.ck-content th {
    background: var(--accent-soft);
    font-weight: 600;
}
.ck-content blockquote {
    border-left: 4px solid var(--accent);
    background: var(--accent-soft);
    padding: 0.8rem 1.2rem;
    margin: 1.2rem 0;
    border-radius: 16px;
    font-style: italic;
}
.ck-content ul, .ck-content ol {
    margin: 0.8rem 0 0.8rem 1.5rem;
}
.ck-content li {
    margin: 0.3rem 0;
}

/* Author Box - Left aligned */
.author-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--accent-soft);
    padding: 1rem 1.2rem;
    border-radius: 24px;
    margin: 2rem 0 1.5rem;
    text-align: left;
}
.author-icon {
    width: 48px;
    height: 48px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.author-details h4 {
    font-weight: 700;
    margin-bottom: 0.2rem;
    font-size: 1rem;
}
.author-details p {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Related Blogs - Left aligned */
.related-section {
    margin-top: 2.5rem;
}
.related-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    text-align: left;
}
.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}
.related-item {
    background: var(--card-bg);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    overflow: hidden;
    text-decoration: none;
    transition: 0.2s;
    text-align: left;
}
.related-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}
.related-img {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
}
.related-info {
    padding: 0.8rem;
}
.related-info h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
}

/* Back to Top Button - LEFT SIDE at bottom */
.back-to-top {
    position: fixed;
    bottom: 1.2rem;
    left: 1rem;          /* changed from right to left */
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--card-bg);
    border: 1px solid var(--border-light);
    color: var(--accent);
    font-size: 1.2rem;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99;
}
.back-to-top:hover {
    background: var(--accent);
    color: white;
}

/* Mobile extra tweaks */
@media (max-width: 640px) {
    .blog-wrapper {
        margin: 1rem auto;
        padding: 0 0.8rem;
    }
    .title-wrapper {
        padding: 1rem 1rem 0.2rem;
    }
    .content-area {
        padding: 0 1rem 1.5rem;
    }
    .meta-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.6rem;
    }
    .meta-left {
        gap: 0.8rem;
    }
    .short-desc {
        font-size: 1rem;
        padding: 0.7rem 1rem;
    }
    .ck-content h2 {
        font-size: 1.4rem;
    }
    .related-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.8rem;
    }
    .author-box {
        padding: 0.8rem 1rem;
    }
    /* Ensure long blog URLs and text wrap properly without cropping or overflow */
    .blog-title,
    .ck-content,
    .ck-content p,
    .ck-content a,
    .ck-content code,
    .ck-content span,
    .short-desc,
    .meta-bar,
    .blog-wrapper {
        overflow-wrap: anywhere;
        word-break: break-word;
        word-wrap: break-word;
        max-width: 100%;
    }

    /* Adjust back-to-top on mobile */
    .back-to-top {
        bottom: 1rem;
        left: 0.8rem;
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>

<!-- Progress Bar -->
<div class="progress-container">
    <div class="progress-bar" id="progressBar"></div>
</div>

<div class="blog-wrapper">
    <div class="blog-card">
        <!-- Hero Image - Full size, no crop -->
        @if($blog->featured_image)
        <div class="hero-section">
            <img src="{{ asset('storage/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="hero-image">
        </div>
        @endif

        <!-- Title - Left aligned -->
        <div class="title-wrapper">
            <h1 class="blog-title">{{ $blog->title }}</h1>
        </div>

        <div class="content-area">
            <!-- Meta Bar -->
            <div class="meta-bar">
                <div class="meta-left">
                    <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at?->format('F d, Y') }}</span>
                    <span><i class="far fa-clock"></i> 
                        @php
                            $wordCount = str_word_count(strip_tags($blog->content));
                            $readTime = max(1, ceil($wordCount / 200));
                        @endphp
                        {{ $readTime }} min read
                    </span>
                    <span><i class="fas fa-tag"></i> Wellness</span>
                </div>
                <div class="share-buttons">
                    <a href="https://wa.me/?text={{ urlencode($blog->title.' '.url()->current()) }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <!-- Correct X (Twitter) logo -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ url()->current() }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <button id="copyLinkBtn"><i class="fas fa-link"></i></button>
                </div>
            </div>

            <!-- Short Description -->
            @if($blog->short_description)
            <div class="short-desc">
                {{ $blog->short_description }}
            </div>
            @endif

            <!-- CKEditor Content -->
            <div class="ck-content">
                {!! $blog->content !!}
            </div>

            <!-- Author Box -->
            <div class="author-box">
                <div class="author-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="author-details">
                    <h4>Wellcare Labs Editorial Team</h4>
                    <p>Evidence-based health insights for better living.</p>
                </div>
            </div>

            <!-- Related Blogs -->
            @if($relatedBlogs && count($relatedBlogs) > 0)
            <div class="related-section">
                <div class="related-title">
                    <i class="fas fa-book-open"></i> You may also like
                </div>
                <div class="related-grid">
                    @foreach($relatedBlogs as $item)
                    <a href="{{ route('blogs.show', $item->slug) }}" class="related-item">
                        @if($item->featured_image)
                            <img src="{{ asset('storage/'.$item->featured_image) }}" class="related-img" alt="{{ $item->title }}">
                        @else
                            <div class="related-img" style="background: var(--accent-soft); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 1.5rem; color: var(--accent);"></i>
                            </div>
                        @endif
                        <div class="related-info">
                            <h4>{{ \Illuminate\Support\Str::limit($item->title, 65) }}</h4>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Copy Link
const copyBtn = document.getElementById('copyLinkBtn');
if (copyBtn) {
    copyBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href);
        const originalIcon = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            copyBtn.innerHTML = originalIcon;
        }, 1500);
    });
}
</script>

@endsection