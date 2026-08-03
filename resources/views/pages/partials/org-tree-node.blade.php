<li>
    <div class="org-node-container" style="display: inline-block; text-align: center; margin-bottom: 5px; background: transparent; padding: 0;">
        <div style="font-weight: 700; color: #022648; text-transform: uppercase; margin-bottom: 15px; font-size: 1.1rem;">
            {{ $node->nama_jabatan ?? $node->jabatan }}
        </div>

        @if(isset($node->members) && $node->members->count() > 0)
            @foreach($node->members as $member)
                <a href="{{ route('organisasi.show', $member->nama) }}" style="text-decoration: none;">
                    <div class="card" style="width: 250px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; gap: 15px; margin: 0 auto 10px auto;">
                        @if($member->foto)
                            <img src="{{ $member->foto_url ?? Storage::url($member->foto) }}" alt="{{ $member->nama }}" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 130px; height: 130px; border-radius: 50%; background-color: #0a2540; color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold;">
                                {{ strtoupper(substr($member->nama, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <h3 style="font-size: 1.1rem; color: #022648; margin: 0 0 5px 0;">{{ Str::words($member->nama, 2, '') }}</h3>
                            <p style="margin: 0; color: #6b7280; font-size: 0.85rem;">{{ $node->nama_jabatan ?? $member->jabatan }}</p>
                            @if($member->kabupaten || ($member->provinsi && $member->provinsi !== 'Nasional'))
                                <div style="margin-top: 4px; font-size: 0.8125rem; font-weight: 600; color: #b7830f;">
                                    {{ trim(str_replace(["\r\n", "\r", "\n", '\r', '\n', '📍'], '', $member->kabupaten ?? $member->provinsi)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        @elseif(isset($node->members))
            <div class="card" style="width: 250px; background: #f8fafc; padding: 25px; border-radius: 8px; border: 1.5px dashed #cbd5e1; display: flex; flex-direction: column; align-items: center; gap: 15px; margin: 0 auto; opacity: 0.75;">
                <div style="width: 130px; height: 130px; border-radius: 50%; background-color: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold;">
                    ?
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; color: #64748b; margin: 0 0 5px 0;">{{ $node->nama_jabatan }}</h3>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.85rem; font-weight: 600;">(Belum Terisi)</p>
                </div>
            </div>
        @else
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
                        @if($node->kabupaten || ($node->provinsi && $node->provinsi !== 'Nasional'))
                            <div style="margin-top: 4px; font-size: 0.8125rem; font-weight: 600; color: #b7830f;">
                                {{ trim(str_replace(["\r\n", "\r", "\n", '\r', '\n', '📍'], '', $node->kabupaten ?? $node->provinsi)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @endif
    </div>

    @if(isset($node->children) && $node->children->count() > 0)
        <ul>
            @foreach($node->children as $child)
                @include('pages.partials.org-tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
