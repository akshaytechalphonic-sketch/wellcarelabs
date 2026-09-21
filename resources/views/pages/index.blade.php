{{-- resources/views/pages/index.blade.php --}}
@extends('maindesign')

@section('title', 'Wellcare Labs Services – Diagnostic & Pathology Services in Pune')
@section('meta_description', 'Explore comprehensive diagnostic and pathology services at Wellcare Labs Pune, including home sample collection, full body checkups, and specialized clinical testing.')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }

    /* Hero Banner */
    .services-hero-wrapper {
        background: linear-gradient(135deg, #0a2540 0%, #4673e4 50%, #00c6ff 100%);
        color: #ffffff;
        padding: 75px 20px 65px;
        border-radius: 0 0 35px 35px;
        margin-bottom: 50px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 71, 255, 0.18);
    }
    .services-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 18px;
        border-radius: 50px;
        margin-bottom: 18px;
        backdrop-filter: blur(8px);
    }
    .services-hero-wrapper h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 14px;
        letter-spacing: -0.5px;
    }
    .services-hero-wrapper p {
        font-size: 1.15rem;
        max-width: 720px;
        margin: 0 auto 28px;
        color: #e0f2fe;
        line-height: 1.6;
    }

    /* Search Bar in Hero */
    .service-search-box {
        max-width: 620px;
        margin: 0 auto;
        position: relative;
    }
    .service-search-box .form-control {
        border-radius: 50px;
        padding: 16px 25px 16px 55px;
        font-size: 1rem;
        border: 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .service-search-box .search-icon {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        color: #4673e4;
        font-size: 1.2rem;
    }
    .service-search-box .btn-search {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 50px;
        padding: 10px 24px;
        background: #4673e4;
        color: #fff;
        font-weight: 700;
        border: none;
        transition: all 0.2s ease;
    }
    .service-search-box .btn-search:hover {
        background: #0030b3;
    }

    /* Service Card */
    .service-card-v2 {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    .service-card-v2:hover {
        transform: translateY(-7px);
        box-shadow: 0 20px 40px -10px rgba(0, 71, 255, 0.15);
        border-color: #0047ff;
    }
    .service-card-img-wrap {
        height: 200px;
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #e2e8f0;
    }
    .service-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .service-card-v2:hover .service-card-img {
        transform: scale(1.06);
    }
    .service-card-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.92);
        color: #0047ff;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 50px;
        backdrop-filter: blur(4px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .service-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .service-card-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        line-height: 1.35;
    }
    .service-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .service-card-title a:hover {
        color: #0047ff;
    }
    .service-card-desc {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.55;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }
    .btn-explore-service {
        background: #eff6ff;
        color: #0047ff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 10px 20px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        transition: all 0.2s ease;
        border: 1px solid #dbeafe;
    }
    .service-card-v2:hover .btn-explore-service {
        background: #0047ff;
        color: #ffffff;
        border-color: #0047ff;
    }

    @media(max-width: 768px) {
        .services-hero-wrapper h1 { font-size: 2.1rem; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Hero Section --}}
    <div class="services-hero-wrapper text-center">
        <div class="services-hero-badge">
            <i class="fa-solid fa-notes-medical"></i> Diagnostic & Healthcare Services
        </div>
        <h1>Wellcare Specialized Services</h1>
        <p>Explore our wide range of diagnostic services, specialized pathology testing, and health checkup solutions across Pune & PCMC.</p>
        
        <div class="service-search-box">
            <form action="{{ route('pages.index') }}" method="GET">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search service pages...">
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>
    </div>

    <div class="container pb-5">
        @if(!empty($q))
            <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fw-bold text-dark mb-0">Search Results for: <span class="text-primary">"{{ $q }}"</span></h4>
                <a href="{{ route('pages.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="fa-solid fa-xmark me-1"></i> Clear Search</a>
            </div>
        @endif

        @if(isset($pages) && $pages->count() > 0)
            <div class="row g-4">
                @foreach($pages as $p)
                    @php
                        $bannerImg = $p->banner_image 
                            ? asset('storage/' . ltrim($p->banner_image, '/')) 
                            : asset('Front_end/assets/img/blog/default-package.jpg');
                        $desc = $p->meta_description ?: $p->content;
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4 d-flex">
                        <div class="service-card-v2">
                            <div class="service-card-img-wrap">
                                <span class="service-card-badge"><i class="fa-solid fa-shield-halved me-1"></i> Service</span>
                                <a href="{{ route('pages.show', ['page' => $p->slug]) }}">
                                    <img src="{{ $bannerImg }}" alt="{{ $p->title }}" class="service-card-img" loading="lazy">
                                </a>
                            </div>
                            <div class="service-card-body">
                                <h3 class="service-card-title">
                                    <a href="{{ route('pages.show', ['page' => $p->slug]) }}">{{ $p->title }}</a>
                                </h3>
                                <p class="service-card-desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($desc), 110) }}
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ route('pages.show', ['page' => $p->slug]) }}" class="btn-explore-service">
                                        <span>Explore Service</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $pages->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-white border rounded-4 p-5 shadow-sm">
                <i class="fa-solid fa-folder-open fs-1 text-muted mb-3 d-block"></i>
                <h4 class="fw-bold text-dark">No Services Found</h4>
                <p class="text-muted">We couldn't find any service pages matching your criteria.</p>
                <a href="{{ route('pages.index') }}" class="btn btn-primary rounded-pill px-4">View All Services</a>
            </div>
        @endif
    </div>

</div>
@endsection
