<li>
    <div style="position: relative; display: inline-block;">
        <div class="org-tree-node {{ $node->members->count() > 0 ? 'has-members' : 'empty-node' }}">
            <span class="org-node-urutan">{{ $node->urutan }}</span>

            @if($node->members->count() > 0)
                @foreach($node->members as $member)
                    <div class="org-member-mini-card">
                        @if($member->foto)
                            <img src="{{ Storage::url($member->foto) }}" alt="{{ $member->nama }}" class="org-avatar-mini">
                        @else
                            <div class="org-avatar-placeholder-mini">
                                {{ strtoupper(substr($member->nama, 0, 2)) }}
                            </div>
                        @endif
                        <h4 class="org-name-mini">{{ Str::words($member->nama, 2, '') }}</h4>
                        <p class="org-jabatan-mini">{{ $node->nama_jabatan }}</p>
                    </div>
                @endforeach
            @else
                <a href="{{ route('admin.organisasi.create', ['urutan' => $node->urutan, 'atasan_id' => $node->atasan_id, 'jabatan' => $node->nama_jabatan, 'periode_id' => request('periode_id', $selectedPeriode->id ?? '')]) }}" style="text-decoration: none; display: block; color: inherit; width: 100%; height: 100%;">
                    <div class="org-node-jabatan">{{ $node->nama_jabatan }}</div>
                    <p style="font-size: 0.7rem; color: #022648; margin: 0; font-weight: 700;">Kosong <span style="font-size: 0.65rem; color: #b7830f;">(+ Isi)</span></p>
                </a>
            @endif
        </div>

        {{-- Add Sibling Button (Right) --}}
        <a href="{{ route('admin.organisasi.create', ['atasan_id' => $node->atasan_id, 'jabatan' => $node->nama_jabatan, 'periode_id' => request('periode_id', $selectedPeriode->id ?? '')]) }}" class="org-add-btn sibling-btn" title="Tambah Anggota (Jabatan Sejajar)">+</a>
        
        {{-- Add Child Button (Bottom) --}}
        <a href="{{ route('admin.organisasi.create', ['atasan_id' => $node->id, 'periode_id' => request('periode_id', $selectedPeriode->id ?? '')]) }}" class="org-add-btn child-btn" title="Tambah Anggota (Jabatan Bawahan)">+</a>
    </div>

    @if($node->children->count() > 0)
        <ul>
            @foreach($node->children as $child)
                @include('admin.organisasi.partials.tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
