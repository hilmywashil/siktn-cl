@extends('layouts.app')

@section('title', $plan->title . ' - ASITA JABAR')

@section('content')

<section class="strategic-detail-page">
    
        <div class="back-button">
            <a href="{{ route('home') }}#strategic-plan" class="btn">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
    <div class="strategic-detail-container">
        <div class="strategic-detail-header">
            <h1>{{ $plan->title }}</h1>
            <p class="subtitle">Strategic Plan ASITA Jawa Barat</p>
        </div>

        <!-- Section Cards (dari details array) -->
        @if($plan->details && isset($plan->details['sections']))
        <div class="strategic-sections">
            @foreach($plan->details['sections'] as $section)
            <div class="section-card">
                <h3>{{ $section['title'] ?? '' }}</h3>
                @if(isset($section['description']))
                <p>{{ $section['description'] }}</p>
                @endif
                
                @if(isset($section['points']) && is_array($section['points']))
                <ul>
                    @foreach($section['points'] as $point)
                    <li>{{ $point }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Main Content -->
        <div class="strategic-detail-content">
            @if($plan->description)
            <p class="main-description">{{ $plan->description }}</p>
            @endif

            @if($plan->details && isset($plan->details['guidelines']))
            <div class="guidelines-section">
                <h3>Panduan Pelaksanaan :</h3>
                <ul>
                    @foreach($plan->details['guidelines'] as $guideline)
                    <li>{{ is_array($guideline) ? $guideline['text'] : $guideline }}</li>
                    @if(is_array($guideline) && isset($guideline['sub_points']))
                        <ul class="sub-list">
                            @foreach($guideline['sub_points'] as $sub)
                            <li>{{ $sub }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if($plan->details && isset($plan->details['kpi']))
            <div class="kpi-section">
                <h3>KPI :</h3>
                <ul>
                    @foreach($plan->details['kpi'] as $kpi)
                    <li>{{ $kpi }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

    </div>
</section>

<style>
.strategic-detail-page {
    padding: 100px 100px;
    background: #f9f9f9;
    min-height: 70vh;
}

.strategic-detail-container {
    max-width: 1400px;
    margin: 0 auto;
}

.strategic-detail-header {
    margin-bottom: 50px;
}

.strategic-detail-header h1 {
    font-size: 42px;
    font-weight: 700;
    color: #04293B;
    margin-bottom: 10px;
    line-height: 1.2;
}

.strategic-detail-header .subtitle {
    font-size: 18px;
    color: #666;
    font-weight: 400;
}

/* Section Cards - 3 Columns */
.strategic-sections {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 50px;
}

.section-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s;
}

.section-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.section-card h3 {
    font-size: 20px;
    font-weight: 600;
    color: #04293B;
    margin-bottom: 15px;
    line-height: 1.4;
}

.section-card p {
    font-size: 16px;
    line-height: 1.7;
    color: #555;
    margin-bottom: 15px;
}

.section-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.section-card ul li {
    font-size: 15px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.section-card ul li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #00A651;
    font-weight: bold;
}

/* Main Content */
.strategic-detail-content {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

.main-description {
    font-size: 17px;
    line-height: 1.9;
    color: #333;
    margin-bottom: 30px;
}

.guidelines-section,
.kpi-section {
    margin-top: 30px;
}

.guidelines-section h3,
.kpi-section h3 {
    font-size: 18px;
    font-weight: 600;
    color: #04293B;
    margin-bottom: 15px;
}

.guidelines-section ul,
.kpi-section ul {
    list-style: none;
    padding: 0;
    margin-left: 20px;
}

.guidelines-section ul li,
.kpi-section ul li {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    margin-bottom: 10px;
    padding-left: 20px;
    position: relative;
}

.guidelines-section ul li:before,
.kpi-section ul li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #04293B;
    font-weight: bold;
}

.sub-list {
    margin-top: 10px;
    margin-left: 20px;
}

.sub-list li {
    font-size: 15px;
}

.back-button {
    margin-top: 40px;
}

.back-button .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    padding: 0;
    background: #f5f5f5;
    color: #04293B;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s;
    font-size: 18px;
    border: 1px solid #e0e0e0;
}

.back-button .btn:hover {
    background: #e8e8e8;
    transform: translateX(-3px);
    border-color: #04293B;
}

@media (max-width: 1024px) {
    .strategic-detail-page {
        padding: 50px 30px;
    }

    .strategic-detail-header h1 {
        font-size: 32px;
    }

    .strategic-sections {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .section-card {
        padding: 25px;
    }

    .section-card h3 {
        font-size: 18px;
    }

    .strategic-detail-content {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .strategic-detail-page {
        padding: 30px 20px;
    }

    .strategic-detail-header h1 {
        font-size: 26px;
    }

    .strategic-detail-header .subtitle {
        font-size: 16px;
    }

    .strategic-sections {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .section-card {
        padding: 20px;
    }

    .section-card h3 {
        font-size: 17px;
    }

    .strategic-detail-content {
        padding: 25px 20px;
    }

    .main-description,
    .guidelines-section ul li,
    .kpi-section ul li {
        font-size: 15px;
    }
}
</style>
@endsection