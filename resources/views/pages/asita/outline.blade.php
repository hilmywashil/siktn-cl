@extends('layouts.app')

@section('title', 'Organisasi - ASITA Jawa Barat')

@section('content')
    <section class="hero-abouts">
        <div class="hero-about" data-aos="fade-up">
            <h1>Outline of ASITA</h1>
            <p>Outline of ASITA JABAR</p>
        </div>
    </section>

    <section class="outlines">
        <div class="howtojoin-cards" data-aos="fade-up">
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">1</div>
                    <img src="{{ asset('images/icons/list.png') }}" alt="">
                </div>
                <div class="howtojoin-card-text">
                    <h3>Headquarters</h3>
                    <ul class="list-outline">
                        <li><b>ASITA JAWA BARAT</b></li>
                        <li>Jl. Tamblong No. 8 Bandung 40112</li>
                        <li>Telp : 022 – 426 4334</li>
                        <li>Fax : 022 – 420 4256</li>
                    </ul>
                </div>

            </div>
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">2</div>
                    <img src="{{ asset('images/icons/writing.png') }}" alt="">
                </div>
                <div class="howtojoin-card-text">
                    <h3>Goals and Objectives</h3>
                    <p>An association that embodies the entrepreneur of Travel Services in Indonesia. It contributes to the
                        development of the travel and tourism industry through a variety of activities including
                        disseminating information, encouraging cooperation among members, and promoting the development of
                        businesses and legislation that will benefit the membership and the industry at large.</p>
                </div>

            </div>
            <div class="howtojoin-card">
                <div class="howtojoin-card-icons">
                    <div class="step-number">3</div>
                    <img src="{{ asset('images/icons/apply.png') }}" alt="">

                </div>
                <div class="howtojoin-card-text">
                    <h3>Members (as of March 05, 2018)</h3>
                    <ul class="list-outline">
                        <li><b>Full Members : {{ $fullmembers }}</b></li>
                    </ul>
                    <p>These are companies and representatives of travel companies which join ASITA to receive information
                        about travel market trends and other member services provided for our members.</p>
                </div>

            </div>
        </div>
    </section>
@endsection