<li>
    <div class="org-node-container" style="display: inline-block; text-align: center; margin-bottom: 5px; background: transparent; padding: 0;">
        <div style="font-weight: 700; color: #022648; text-transform: uppercase; margin-bottom: 15px; font-size: 1.1rem;">
            {{ $node->jabatan }}
        </div>
        <a href="{{ route('organisasi.show', $node->nama) }}" style="text-decoration: none;">
            <div class="card" style="width: 250px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; gap: 15px; margin: 0 auto;">
                @if($node->foto)
                    <img src="{{ $node->foto_url }}" alt="{{ $node->nama }}" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 130px; height: 130px; border-radius: 50%; background-color: #0a2540; color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold;">
                        {{ strtoupper(substr($node->nama, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <h3 style="font-size: 1.1rem; color: #022648; margin: 0 0 5px 0;">{{ Str::words($node->nama, 2, '') }}</h3>
                    <p style="margin: 0; color: #6b7280; font-size: 0.85rem;">{{ $node->jabatan }}</p>
                </div>
            </div>
        </a>
    </div>

    @if(isset($node->children) && $node->children->count() > 0)
        <ul>
            @foreach($node->children as $child)
                @include('pages.partials.org-tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
