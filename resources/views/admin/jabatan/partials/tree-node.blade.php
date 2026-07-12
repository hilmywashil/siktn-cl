<li>
    <div style="position: relative; display: inline-block;">
        <div class="tree-node">
            <span class="node-urutan">{{ $node->urutan }}</span>
            {{ $node->nama_jabatan }}
        </div>
        
        {{-- Add Sibling Button (Right) --}}
        <button type="button" class="org-add-btn sibling-btn" onclick="prefillJabatan('{{ $node->urutan }}', 'sibling')" title="Tambah Jabatan Sejajar">+</button>
        
        {{-- Add Child Button (Bottom) --}}
        <button type="button" class="org-add-btn child-btn" onclick="prefillJabatan('{{ $node->urutan }}', 'child')" title="Tambah Jabatan Bawahan">+</button>
    </div>
    @if(isset($node->children) && $node->children->count() > 0)
        <ul>
            @foreach($node->children as $child)
                @include('admin.jabatan.partials.tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
