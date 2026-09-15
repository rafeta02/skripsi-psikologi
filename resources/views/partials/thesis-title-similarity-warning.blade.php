@if(session('title_similarity_warning') && session('title_similar_matches'))
    @php
        $matches = collect(session('title_similar_matches'));
        $isExact = $matches->contains(fn ($match) => ($match['similarity'] ?? 0) >= 100);
    @endphp
    <div class="alert alert-{{ $isExact ? 'danger' : 'warning' }} mb-4" role="alert">
        <h5 class="alert-heading mb-2">
            <i class="fas fa-exclamation-triangle"></i>
            Judul mirip ditemukan di database skripsi
        </h5>
        <p class="mb-2">
            Judul yang Anda ajukan terlihat mirip dengan judul skripsi yang pernah ada.
            Pastikan judul Anda orisinal dan berbeda sebelum melanjutkan.
        </p>
        <ul class="mb-3 pl-3">
            @foreach($matches as $match)
                <li class="mb-1">
                    <strong>{{ $match['similarity'] ?? 0 }}%</strong>
                    — {{ $match['title'] ?? '-' }}
                    @if(!empty($match['title_en']))
                        <br><small class="text-muted ml-3">{{ $match['title_en'] }}</small>
                    @endif
                    <br>
                    <small class="text-muted">
                        {{ $match['nama'] ?? '-' }} ({{ $match['nim'] ?? '-' }})
                        @if(!empty($match['pembimbing']) && $match['pembimbing'] !== '-')
                            · Pembimbing: {{ $match['pembimbing'] }}
                        @endif
                        @if(!empty($match['reason']))
                            · {{ $match['reason'] }}
                        @endif
                    </small>
                </li>
            @endforeach
        </ul>
        <div class="form-check">
            <input
                type="checkbox"
                class="form-check-input"
                name="acknowledge_similar_title"
                id="acknowledge_similar_title"
                value="1"
                {{ old('acknowledge_similar_title') ? 'checked' : '' }}
            >
            <label class="form-check-label" for="acknowledge_similar_title">
                Saya sudah memeriksa dan yakin judul ini berbeda / layak diajukan
            </label>
        </div>
    </div>
@endif
