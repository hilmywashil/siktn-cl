@extends('layouts.app')

@section('title', 'About - ASITA JABAR')

@section('content')

    <section class="hero-visi-misis">
        <div class="hero-visi-misi" data-aos="fade-up">
            <h1>Vision & Mission</h1>
            <p>Association Of The Indonesian
                Tours & Travel Agencies</p>
        </div>
    </section>
    <section class="info-visi-misis">
        <div class="info-visi-misi" data-aos="fade-up">
            <div class="info-visi-misi-text">
                <div class="info-visi-misi-heading">
                    <img src="{{ asset('images/icons/binocular.png') }}" alt="">
                    <h2>Vision</h2>
                </div>
                <p>Increasing the role of members as one of the main players of national tourism, producing foreign exchange
                    and increasing revenue as well as developing the capacity of global competitive enterprises. Improving
                    the image of Indonesian Tourism by providing satisfaction, sense of security, the certainty of
                    protection and guarantee to the interests of service users and interested parties without sacrificing
                    the interests of fellow members.
                    <br>
                    Enhance the role of members by undertaking efforts to advance
                    capabilities that include professional, technical and financial capabilities so as to achieve
                    international standards.
                </p>
            </div>
            <div class="info-visi-misi-image">
                <img src="{{ asset('images/portugal.jpg') }}" alt="">
            </div>

        </div>
    </section>
    <section class="info-visi-misis-2">
        <div class="info-visi-misi" data-aos="fade-up">
            <div class="info-visi-misi-image">
                <img src="{{ asset('images/gunung.jpg') }}" alt="">
            </div>
            <div class="info-visi-misi-text">
                <div class="info-visi-misi-heading">
                    <img src="{{ asset('images/icons/mission.png') }}" alt="">
                    <h2>Mission</h2>
                </div>
                <p>Increasing the role of members as one of the main players of national tourism, producing foreign exchange
                    and increasing revenue as well as developing the capacity of global competitive enterprises. Improving
                    the image of Indonesian Tourism by providing satisfaction, sense of security, the certainty of
                    protection and guarantee to the interests of service users and interested parties without sacrificing
                    the interests of fellow members.
                    <br>
                    Enhance the role of members by undertaking efforts to advance
                    capabilities that include professional, technical and financial capabilities so as to achieve
                    international standards.
                </p>
            </div>
        </div>
    </section>

@endsection