@extends ('layouts.app')

@section('title', 'Active Member ASITA')

@section('content')
    <section class="hero-abouts">
        <div class="hero-about" data-aos="fade-up">
            <h1>Active Member ASITA</h1>
            <p>List of Active Member</p>
        </div>
    </section>
    <section class="active-members">
        <div class="active-member" data-aos="fade-up">
            <div class="alphabet-filter">
                <a href="{{ route('active-member') }}" class="{{ request('letter') ? '' : 'active' }}">
                    All
                </a>

                @foreach(range('A', 'Z') as $char)
                    <a href="{{ route('active-member', ['letter' => $char]) }}"
                        class="{{ request('letter') == $char ? 'active' : '' }}">
                        {{ $char }}
                    </a>
                @endforeach
            </div>
            <div class="member-list">
                @forelse($katalogsletter as $letter => $items)
                    <div class="letter-group">
                        <div class="letter-title">{{ $letter }}</div>

                        <div class="names">
                            @foreach($items as $item)
                                <div class="name-item">
                                    <a href="{{ route('e-katalog.detail', $item->id) }}">{{ $item->company_name }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p>Tidak ada data.</p>
                @endforelse
            </div>

        </div>
    </section>
@endsection