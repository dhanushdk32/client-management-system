@extends('layouts.client')

@section('title', $article->title . ' - Knowledge Base')
@section('page_title', 'Knowledge Base')

@section('content')
<div class="mb-4">
    <a href="{{ route('client.knowledge.index') }}" class="btn btn-light border rounded-pill px-3 py-1 text-muted small fw-semibold">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Help Center
    </a>
</div>

<div class="row g-4">
    <!-- Main Article Body -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4 p-md-5 rounded-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace">
                    {{ $article->category }}
                </span>
                <span class="text-muted small">&bull;</span>
                <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i> Updated {{ $article->updated_at->format('M d, Y') }}</span>
                <span class="text-muted small">&bull;</span>
                <span class="text-muted small"><i class="fa-regular fa-eye me-1"></i> {{ $article->views_count }} views</span>
            </div>

            <h3 class="fw-bold text-dark mb-4">{{ $article->title }}</h3>

            @if($article->summary)
                <div class="p-3 bg-light border-start border-4 border-primary rounded-3 text-secondary small mb-4">
                    {{ $article->summary }}
                </div>
            @endif

            <div class="article-content text-dark" style="line-height: 1.8; font-size: 15px; white-space: pre-line;">
                {!! nl2br(e($article->content)) !!}
            </div>

            <hr class="my-4 text-muted opacity-25">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="small text-muted">
                    Was this guide helpful?
                </div>
                <div>
                    <a href="{{ route('client.tickets.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-headset me-1"></i> Contact Your Team Lead
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Guides Sidebar -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 p-4 rounded-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="fa-solid fa-book-bookmark me-1 text-primary"></i> Related Guides
            </h6>
            <div class="list-group list-group-flush">
                @forelse($relatedArticles as $rel)
                    <a href="{{ route('client.knowledge.show', $rel->slug) }}" class="list-group-item list-group-item-action px-0 py-2 border-0">
                        <div class="fw-semibold text-dark small mb-1">{{ $rel->title }}</div>
                        <small class="text-muted">{{ Str::limit($rel->summary, 60) }}</small>
                    </a>
                @empty
                    <div class="text-muted small">No other guides in this category.</div>
                @endforelse
            </div>
        </div>

        <!-- Need Help Card -->
        <div class="card bg-primary text-white border-0 shadow-sm p-4 rounded-4">
            <h6 class="fw-bold text-white mb-2"><i class="fa-solid fa-comments me-2"></i> Still Need Assistance?</h6>
            <p class="small text-white-50 mb-3">Your dedicated project team leader is ready to assist you directly with any technical questions.</p>
            <a href="{{ route('client.tickets.index') }}" class="btn btn-light text-primary fw-bold w-100 rounded-pill shadow-sm">
                Submit Support Request
            </a>
        </div>
    </div>
</div>
@endsection
