{{-- resources/views/admin/strategic-plan/index.blade.php --}}
@extends('admin.layouts.admin-layout', ['activeMenu' => 'strategic-plan'])

@section('title', 'Kelola Strategic Plan')
@section('page-title', 'Strategic Plan')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn-primary {
        background: #ffd700;
        color: #0a2540;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #ffed4e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .plans-grid {
        display: grid;
        gap: 1.5rem;
    }

    .plan-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .plan-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .plan-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .plan-category {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .category-tata-kelola {
        background: #dbeafe;
        color: #1e40af;
    }

    .category-program-layanan {
        background: #fef3c7;
        color: #92400e;
    }

    .plan-description {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .plan-details {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .plan-details h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.75rem;
    }

    .detail-item {
        display: flex;
        gap: 0.75rem;
        padding: 0.5rem;
        background: white;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }

    .detail-item:last-child {
        margin-bottom: 0;
    }

    .detail-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .detail-icon img {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }

    .detail-content {
        flex: 1;
    }

    .detail-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.25rem;
    }

    .detail-text {
        font-size: 0.8125rem;
        color: #6b7280;
        line-height: 1.4;
    }

    .plan-meta {
        display: flex;
        gap: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .plan-actions {
        display: flex;
        gap: 0.5rem;
        align-items: start;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon svg {
        width: 18px;
        height: 18px;
        stroke: #6b7280;
        fill: none;
        stroke-width: 2;
    }

    .btn-icon:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .btn-icon.edit:hover {
        background: #dbeafe;
        border-color: #3b82f6;
    }

    .btn-icon.edit:hover svg {
        stroke: #3b82f6;
    }

    .btn-icon.delete:hover {
        background: #fee2e2;
        border-color: #ef4444;
    }

    .btn-icon.delete:hover svg {
        stroke: #ef4444;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e5e7eb;
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        stroke: #d1d5db;
        fill: none;
        stroke-width: 1.5;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0a2540;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .plan-header {
            flex-direction: column;
            gap: 1rem;
        }

        .plan-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #0a2540; margin-bottom: 0.5rem;">
            Strategic Plan
        </h1>
        <p style="color: #6b7280;">Kelola data strategic plan organisasi</p>
    </div>
    <a href="{{ route('admin.strategic-plan.create') }}" class="btn-primary">
        <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2;">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Tambah Strategic Plan
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 2;">
        <polyline points="20 6 9 17 4 12"></polyline>
    </svg>
    {{ session('success') }}
</div>
@endif

@if($plans->isEmpty())
<div class="empty-state">
    <svg viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
        <polyline points="14 2 14 8 20 8"></polyline>
    </svg>
    <h3>Belum ada Strategic Plan</h3>
    <p>Mulai tambahkan strategic plan untuk organisasi Anda</p>
    <a href="{{ route('admin.strategic-plan.create') }}" class="btn-primary">
        Tambah Strategic Plan
    </a>
</div>
@else
<div class="plans-grid">
    @foreach($plans as $plan)
    <div class="plan-card">
        <div class="plan-header">
            <div class="plan-info" style="flex: 1;">
                <div>
                    <span class="plan-category {{ $plan->category === 'tata_kelola' ? 'category-tata-kelola' : 'category-program-layanan' }}">
                        {{ $plan->category === 'tata_kelola' ? 'Tata Kelola' : 'Program & Layanan' }}
                    </span>
                    <span class="status-badge {{ $plan->is_active ? 'status-active' : 'status-inactive' }}">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                        {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h3>{{ $plan->title }}</h3>
                <p class="plan-description">{{ $plan->description }}</p>
            </div>
            <div class="plan-actions">
                <a href="{{ route('admin.strategic-plan.edit', $plan) }}" class="btn-icon edit">
                    <svg viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </a>
                <form action="{{ route('admin.strategic-plan.destroy', $plan) }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus strategic plan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon delete">
                        <svg viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        @if($plan->details && count($plan->details) > 0)
        <div class="plan-details">
            <h4>Detail ({{ count($plan->details) }} item)</h4>
            @foreach($plan->details as $detail)
            <div class="detail-item">
                <div class="detail-icon">
                    @if(isset($detail['icon']))
                    <img src="{{ Storage::url($detail['icon']) }}" alt="icon">
                    @else
                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px; stroke: #6b7280; fill: none; stroke-width: 2;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    @endif
                </div>
                <div class="detail-content">
                    <div class="detail-title">{{ $detail['title'] }}</div>
                    <div class="detail-text">{{ Str::limit($detail['content'], 100) }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="plan-meta">
            <span>Urutan: #{{ $plan->order }}</span>
            <span>•</span>
            <span>Dibuat: {{ $plan->created_at->diffForHumans() }}</span>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection