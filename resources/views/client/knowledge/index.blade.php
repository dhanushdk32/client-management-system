@extends('layouts.client')

@section('title', 'Help Center & Knowledge Base - Client Portal')
@section('page_title', 'Help Center & Knowledge Base')

@section('content')
<!-- Hero Search Banner -->
<div class="card bg-primary text-white border-0 shadow-sm p-4 p-md-5 rounded-4 mb-4 text-center">
    <div class="mx-auto" style="max-width: 650px;">
        <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 mb-2 font-monospace">SELF-SERVICE KNOWLEDGE HUB</span>
        <h3 class="fw-bold mb-2 text-white">How can we assist you today?</h3>
        <p class="text-white-50 small mb-4">Explore platform onboarding guides, project handover instructions, and security standards.</p>

        <form action="{{ route('client.knowledge.index') }}" method="GET">
            <div class="input-group input-group-lg bg-white rounded-pill shadow p-1">
                <span class="input-group-text bg-transparent border-0 ps-3 text-muted">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 ps-2" placeholder="Search guides, setup tutorials, milestone FAQs..." style="font-size: 15px;">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Category Filter Pills -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('client.knowledge.index') }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ !request('category') ? 'btn-primary' : 'btn-light border text-muted' }}">
            All Guides
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('client.knowledge.index', ['category' => $cat]) }}" class="btn btn-sm rounded-pill px-3 fw-semibold {{ request('category') == $cat ? 'btn-primary' : 'btn-light border text-muted' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>
    @if(request()->hasAny(['search', 'category']))
        <a href="{{ route('client.knowledge.index') }}" class="btn btn-sm btn-light border text-muted">Clear Filters</a>
    @endif
</div>

<!-- Knowledge Articles Grid -->
<div class="row g-4 mb-4">
    @forelse($articles as $art)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm p-4 rounded-4 transition-all hover-lift">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px; font-size: 18px;">
                        <i class="fa-solid {{ $art->icon ?: 'fa-book-open' }}"></i>
                    </div>
                    <div>
                        <span class="badge bg-light text-secondary border font-monospace" style="font-size: 10px;">{{ $art->category }}</span>
                        <div class="small text-muted" style="font-size: 11px;">
                            <i class="fa-regular fa-eye me-1"></i> {{ $art->views_count }} views
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-2">{{ $art->title }}</h6>
                <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.5;">
                    {{ $art->summary ?: Str::limit(strip_tags($art->content), 90) }}
                </p>

                <div class="border-top pt-3 mt-auto">
                    <a href="{{ route('client.knowledge.show', $art->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-semibold">
                        Read Guide <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="fa-solid fa-book-open fa-3x mb-3 text-light"></i>
            <h6 class="fw-bold text-dark">No matching knowledge articles found.</h6>
            <p class="small text-muted">Try a different search term or browse all available categories above.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $articles->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection
