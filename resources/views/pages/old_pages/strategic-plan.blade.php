@extends('layouts.app')

@section('title', 'Strategic Plan - ASITA JABAR')

@section('content')
<section class="strategic-plan">
    <!-- Tata Kelola Organisasi Section -->
    <div class="strategic-plan-content">
        <div class="strategic-plan-image">
            <img src="{{ asset('images/strategic-plan1.png') }}" alt="Strategic Plan Image">
        </div>
        <div class="strategic-plan-wrapper">
            <div class="green-accent"></div>
            <h1>Strategic Plan ASITA JABAR</h1>
            <div class="strategic-plan-cards">
                @forelse($tataKelola as $plan)
                    <a href="{{ route('strategic-plan.detail', $plan->id) }}" class="strategic-plan-card">
                        <h2>{{ $plan->title }}</h2>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                @empty
                    <div class="no-data">
                        <p>Belum ada data Strategic Plan tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Program dan Layanan Section -->
    <div class="strategic-plan-content-reverse">
        <div class="strategic-plan-image-reverse">
            <img src="{{ asset('images/strategic-plan2.png') }}" alt="Program dan Layanan Image">
        </div>
        <div class="strategic-plan-wrapper">
            <div class="green-accent"></div>
            <h1>Program dan Layanan</h1>
            <div class="strategic-plan-cards">
                @forelse($programLayanan as $plan)
                    <a href="{{ route('strategic-plan.detail', $plan->id) }}" class="strategic-plan-card">
                        <h2>{{ $plan->title }}</h2>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                @empty
                    <div class="no-data">
                        <p>Belum ada data Program dan Layanan tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection