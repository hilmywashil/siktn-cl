@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('hero-background', asset('assets-front/images/about_image.jpg'))
@section('page-title', 'STRUKTUR ORGANISASI SIKTN')
@section('page-description', 'Mengenal jajaran kepengurusan dan struktur organisasi SIKTN secara transparan dan profesional.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <style>
        .public-tree {
            text-align: center;
            display: block;
            margin: 0 auto;
            width: max-content;
            min-width: max-content;
            padding: 2rem 2rem 5rem 2rem;
            overflow-x: auto;
        }
        .public-tree ul {
            padding-top: 30px;
            position: relative;
            transition: all 0.5s;
            padding-left: 0;
            display: flex;
            justify-content: center;
            margin: 0;
        }
        .public-tree li {
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 30px 15px 0 15px;
            transition: all 0.5s;
        }
        /* Garis horizontal atas */
        .public-tree li::before, .public-tree li::after {
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 3px solid #022648;
            width: 50%; height: 30px;
        }
        .public-tree li::after {
            right: auto; left: 50%;
            border-left: 3px solid #022648;
        }
        .public-tree li:only-child::after, .public-tree li:only-child::before {
            display: none;
        }
        .public-tree li:only-child { padding-top: 0; }
        .public-tree li:first-child::before, .public-tree li:last-child::after {
            border: 0 none;
        }
        .public-tree li:last-child::before {
            border-right: 3px solid #022648;
            border-radius: 0 8px 0 0;
        }
        .public-tree li:first-child::after {
            border-radius: 8px 0 0 0;
        }
        /* Garis vertikal ke anak */
        .public-tree ul ul::before {
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 3px solid #022648;
            width: 0; height: 30px;
            transform: translateX(-50%);
        }
        .card { transition: all 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.1) !important; }
    </style>
    <section class="wrapper-white-1" style="overflow-x: auto;">
        <div class="organisasi-section" data-aos="fade-up" style="max-width: 100%;">
            @if(isset($organisasiTree) && $organisasiTree->count() > 0)
                <div class="public-tree">
                    <ul>
                        @foreach($organisasiTree as $root)
                            @include('pages.partials.org-tree-node', ['node' => $root])
                        @endforeach
                    </ul>
                </div>
            @else
                <div style="text-align: center; padding: 50px 0; color: #6b7280;">
                    <p>Belum ada data struktur organisasi yang aktif.</p>
                </div>
            @endif
        </div>
    </section>
@endsection