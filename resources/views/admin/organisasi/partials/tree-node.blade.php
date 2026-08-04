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
                        @if($member->kabupaten || ($member->provinsi && $member->provinsi !== 'Nasional'))
                            <div style="font-size: 0.75rem; font-weight: 600; color: #b7830f; margin-top: 2px;">
                                {{ trim(str_replace(["\r\n", "\r", "\n", '\r', '\n', '📍'], '', $member->kabupaten ?? $member->provinsi)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <a href="{{ route('admin.organisasi.create', ['urutan' => $node->urutan, 'atasan_id' => $node->atasan_id, 'jabatan' => $node->nama_jabatan, 'provinsi' => request('provinsi', 'Nasional')]) }}" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; color: inherit; width: 100%; height: 100%; padding: 4px 0;">
                    <div class="org-node-jabatan" style="margin-top: 0.25rem;">{{ $node->nama_jabatan }}</div>
                    <div class="empty-badge-action" style="margin-top: 6px; padding: 4px 10px; border-radius: 20px; background: #e2e8f0; color: #022648; font-size: 0.725rem; font-weight: 700; transition: all 0.2s ease;">
                        <i class="fa fa-plus-circle" style="color: #b7830f; margin-right: 3px;"></i> Isi Anggota
                    </div>
                </a>
            @endif
        </div>

        {{-- Add Sibling Button (Right) --}}
        <a href="{{ route('admin.organisasi.create', ['atasan_id' => $node->atasan_id, 'jabatan' => $node->nama_jabatan, 'provinsi' => request('provinsi', 'Nasional')]) }}" class="org-add-btn sibling-btn" title="Tambah Anggota (Jabatan Sejajar)">+</a>
        
        {{-- Add Child Button (Bottom) --}}
        <a href="{{ route('admin.organisasi.create', ['atasan_id' => $node->id, 'provinsi' => request('provinsi', 'Nasional')]) }}" class="org-add-btn child-btn" title="Tambah Anggota (Jabatan Bawahan)">+</a>
    </div>

    @if($node->children->count() > 0)
        <ul>
            @foreach($node->children as $child)
                @include('admin.organisasi.partials.tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
