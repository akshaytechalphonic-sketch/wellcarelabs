@extends('maindesign')

@section('title', 'Blogs - Wellcare Labs')

@section('content')

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
/* ---------- VARIABLES ---------- */
:root {
    --bg-body: #f5f7fb;
    --card-bg: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #334155;
    --text-muted: #64748b;
    --border-light: #e9eef3;
    --accent: #0d6efd;
    --accent-hover: #0b5ed7;
    --radius-card: 24px;
    --shadow-sm: 0 4px 10px rgba(0,0,0,0.02), 0 2px 4px rgba(0,0,0,0.03);
    --shadow-md: 0 10px 25px -5px rgba(0,0,0,0.05);
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
    line-height: 1.5;
}

/* Container */
.container-blog {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Hero Section (compact) */
.blogs-hero {
    text-align: center;
    padding: 1.5rem 1rem 0.5rem;
}
.blogs-hero h1 {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -0.01em;
    color: var(--text-primary);
}
.blogs-hero h1 span {
    color: var(--accent);
}
.blogs-hero p {
    max-width: 600px;
    margin: 0.5rem auto 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Search Bar - at the top, prominent */
.search-bar-top {
    margin: 1rem 0 2rem;
}
.search-wrapper {
    background: var(--card-bg);
    border-radius: 60px;
    padding: 0.3rem;
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 0.3rem;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
}
.search-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    background: transparent;
    font-size: 0.95rem;
    outline: none;
    color: var(--text-primary);
}
.search-input::placeholder {
    color: var(--text-muted);
}
.search-btn {
    background: var(--accent);
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 40px;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: 0.2s;
    white-space: nowrap;
}
.search-btn:hover {
    background: var(--accent-hover);
}
.clear-search {
    background: transparent;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 1rem;
    padding: 0 0.5rem;
    display: none;
}
.clear-search.visible {
    display: block;
}

/* Featured Blog */
.featured-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    background: var(--card-bg);
    border-radius: var(--radius-card);
    overflow: hidden;
    margin: 1.5rem 0 2.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
}
.featured-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-height: 260px;
}
.featured-content {
    padding: 1.8rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.featured-badge {
    display: inline-block;
    background: var(--accent);
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.8rem;
    border-radius: 30px;
    width: fit-content;
    margin-bottom: 0.8rem;
}
.featured-content h2 {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
}
.featured-content p {
    color: var(--text-secondary);
    margin-bottom: 1.2rem;
    line-height: 1.5;
    font-size: 0.9rem;
}
.featured-link {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9rem;
}
.featured-link:hover {
    text-decoration: underline;
}

/* Blog Grid */
.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 1rem 0 1.2rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}
.blog-card {
    background: var(--card-bg);
    border-radius: var(--radius-card);
    overflow: hidden;
    border: 1px solid var(--border-light);
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 25px -10px rgba(0,0,0,0.08);
}
.card-img {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
}
.card-body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.card-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
    color: var(--text-primary);
    line-height: 1.4;
}
.card-excerpt {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 0.8rem;
    line-height: 1.45;
}
.card-meta {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.7rem;
    color: var(--text-muted);
    border-top: 1px solid var(--border-light);
    padding-top: 0.7rem;
}
.read-more {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
    font-size: 0.75rem;
}
.read-more i {
    font-size: 0.65rem;
    margin-left: 3px;
}

/* No results */
.no-results {
    text-align: center;
    padding: 2.5rem;
    color: var(--text-muted);
}
.no-results i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin: 2.5rem 0 2rem;
}
.page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--card-bg);
    border: 1px solid var(--border-light);
    color: var(--text-primary);
    text-decoration: none;
    transition: 0.2s;
    font-size: 0.9rem;
}
.page-link.active, .page-link:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}

/* Category Filter Pills */
.category-filter-wrapper {
    margin: 0 0 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}
.category-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    background: var(--card-bg);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.25s ease;
    box-shadow: var(--shadow-sm);
}
.category-pill:hover {
    color: var(--accent);
    border-color: var(--accent);
    transform: translateY(-1px);
    text-decoration: none;
}
.category-pill.active {
    background: var(--accent);
    color: #ffffff;
    border-color: var(--accent);
}
.category-count {
    font-size: 0.75rem;
    background: rgba(0,0,0,0.05);
    color: inherit;
    border-radius: 20px;
    padding: 2px 8px;
    margin-left: 6px;
}
.category-pill.active .category-count {
    background: rgba(255,255,255,0.2);
}

/* Category Badge on card */
.card-category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(13, 110, 253, 0.95);
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    z-index: 2;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.blog-card {
    position: relative;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .container-blog {
        padding: 0 0.8rem;
    }
    .blogs-hero h1 {
        font-size: 1.5rem;
    }
    .search-wrapper {
        border-radius: 30px;
    }
    .search-input {
        padding: 0.6rem 0.8rem;
        font-size: 0.85rem;
    }
    .search-btn {
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
    }
    .featured-grid {
        grid-template-columns: 1fr;
        gap: 0;
        margin: 1rem 0 2rem;
    }
    .featured-img {
        max-height: 200px;
        object-fit: cover;
    }
    .featured-content {
        padding: 1.2rem;
    }
    .featured-content h2 {
        font-size: 1.3rem;
    }
    .featured-content p {
        font-size: 0.85rem;
    }
    .section-title {
        font-size: 1.2rem;
        margin: 0.5rem 0 1rem;
    }
    .blog-grid {
        gap: 1rem;
    }
    .card-body {
        padding: 0.8rem;
    }
    .card-title {
        font-size: 0.9rem;
    }
    .card-excerpt {
        font-size: 0.75rem;
    }
    .pagination {
        margin: 1.5rem 0;
    }
    .page-link {
        width: 34px;
        height: 34px;
        font-size: 0.8rem;
    }
    .card-title,
    .featured-title,
    .featured-content p,
    .card-excerpt,
    .blogs-hero h1,
    .blogs-hero p,
    .category-pill,
    .container-blog {
        overflow-wrap: anywhere;
        word-break: break-word;
        word-wrap: break-word;
        max-width: 100%;
    }
}
</style>

<div class="blogs-shell">
    <div class="container-blog">
        <!-- Hero Section -->
        <div class="blogs-hero">
            <h1>Wellcare Health <span>Blogs & Insights</span></h1>
            <p>Evidence-based health articles, diagnostics insights, and wellness guidance.</p>
        </div>

        <!-- Search Bar at the Top -->
        <div class="search-bar-top">
            <div class="search-wrapper">
                <input type="text" id="searchInput" class="search-input" placeholder="Search articles by title...">
                <button id="clearSearchBtn" class="clear-search" aria-label="Clear search"><i class="fas fa-times-circle"></i></button>
                <button id="searchBtn" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </div>
        </div>

        <!-- Categories Filter Pills -->
        <div class="category-filter-wrapper">
            <a href="{{ route('blogs.index') }}" class="category-pill {{ !$selectedCategory ? 'active' : '' }}">
                All Articles
            </a>
            @foreach($categories as $cat)
                @if($cat->blogs_count > 0 || (isset($selectedCategory) && $selectedCategory->id == $cat->id))
                    <a href="{{ route('blogs.category', ['categorySlug' => $cat->slug]) }}" 
                       class="category-pill {{ (isset($selectedCategory) && $selectedCategory->id == $cat->id) ? 'active' : '' }}">
                        {{ $cat->name }}
                        <span class="category-count">{{ $cat->blogs_count }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Featured Blog (first item) -->
        @if($blogs->count())
        @php $featured = $blogs->first(); @endphp
        <div id="featuredBlog" class="featured-grid">
            <div style="position: relative;">
                @if($featured->category)
                    <span class="card-category-badge" style="top: 15px; left: 15px;">{{ $featured->category->name }}</span>
                @endif
                <img src="{{ asset('storage/'.$featured->featured_image) }}" alt="{{ $featured->title }}" class="featured-img" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="featured-content">
                <span class="featured-badge">Featured Article</span>
                <h2 class="featured-title">{{ $featured->title }}</h2>
                <p>{{ $featured->short_description ? \Illuminate\Support\Str::limit($featured->short_description, 130) : \Illuminate\Support\Str::limit(strip_tags($featured->content), 130) }}</p>
                <a href="{{ route('blogs.show', $featured->slug) }}" class="featured-link">Read full story <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        @endif

        <!-- Blog Grid Section -->
        <div class="section-title">
            <i class="fas fa-newspaper"></i> Latest Articles
        </div>
        <div id="blogGrid" class="blog-grid">
            @foreach($blogs->skip(1) as $blog)
            <div class="blog-card">
                @if($blog->category)
                    <span class="card-category-badge">{{ $blog->category->name }}</span>
                @endif
                <img src="{{ asset('storage/'.$blog->featured_image) }}" class="card-img" alt="{{ $blog->title }}">
                <div class="card-body">
                    <h3 class="card-title">{{ $blog->title }}</h3>
                    <p class="card-excerpt">{{ $blog->short_description ? \Illuminate\Support\Str::limit($blog->short_description, 90) : \Illuminate\Support\Str::limit(strip_tags($blog->content), 90) }}</p>
                    <div class="card-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('M d, Y') }}</span>
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="read-more">Read <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No results message -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <p>No articles found. Try a different search term.</p>
        </div>

        <!-- Pagination -->
        @if(method_exists($blogs, 'links') && $blogs->hasPages())
        <div class="pagination">
            {{ $blogs->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const clearBtn = document.getElementById('clearSearchBtn');
    const blogGrid = document.getElementById('blogGrid');
    const noResultsDiv = document.getElementById('noResults');
    const featuredBlog = document.getElementById('featuredBlog');
    
    // Store original order of cards (as an array of DOM nodes)
    let originalCards = [];
    const allCards = Array.from(document.querySelectorAll('.blog-card'));
    allCards.forEach(card => originalCards.push(card));
    
    function filterAndReorder() {
        const query = searchInput.value.toLowerCase().trim();
        
        if (query === '') {
            // Restore original order and show all cards
            allCards.forEach(card => card.style.display = '');
            allCards.forEach(card => blogGrid.appendChild(card));
            // Show featured blog
            if (featuredBlog) featuredBlog.style.display = '';
            noResultsDiv.style.display = 'none';
            clearBtn.classList.remove('visible');
            return;
        }
        
        // Check if featured blog matches
        let featuredMatches = false;
        if (featuredBlog) {
            const featuredTitle = featuredBlog.querySelector('.featured-title')?.innerText.toLowerCase() || '';
            featuredMatches = featuredTitle.includes(query);
            featuredBlog.style.display = featuredMatches ? '' : 'none';
        }
        
        // Find matching cards among grid
        const matchingCards = [];
        const nonMatchingCards = [];
        
        allCards.forEach(card => {
            const titleElem = card.querySelector('.card-title');
            const title = titleElem ? titleElem.innerText.toLowerCase() : '';
            if (title.includes(query)) {
                matchingCards.push(card);
            } else {
                nonMatchingCards.push(card);
            }
        });
        
        // Hide non‑matching cards
        nonMatchingCards.forEach(card => card.style.display = 'none');
        
        // Show matching cards
        matchingCards.forEach(card => card.style.display = '');
        
        // Reorder grid: matching cards first (preserve their relative order), then non‑matching (hidden)
        const reordered = [...matchingCards, ...nonMatchingCards];
        reordered.forEach(card => blogGrid.appendChild(card));
        
        // Show/hide clear button
        clearBtn.classList.add('visible');
        
        // No results message: if no featured match AND no grid matches
        if ((!featuredMatches || !featuredBlog) && matchingCards.length === 0) {
            noResultsDiv.style.display = 'block';
        } else {
            noResultsDiv.style.display = 'none';
        }
    }
    
    // Event listeners
    searchBtn.addEventListener('click', filterAndReorder);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') filterAndReorder();
        else filterAndReorder(); // live search on every keystroke
    });
    
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterAndReorder(); // this will restore original order
        searchInput.focus();
    });
    
    // Initial state
    filterAndReorder();
});
</script>

@endsection