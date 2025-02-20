@extends('layouts.app')

@section('title', 'Modifier la question')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Modifier la question</div>
            <div class="card-body">
                <form method="POST" action="{{ route('questions.update', $question) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $question->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenu détaillé</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $question->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location-search" class="form-label">Lieu</label>
                        <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="location-search" name="location_name" value="{{ old('location_name', $question->location_name) }}" required>
                        @error('location_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="map" class="map-container"></div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $question->latitude) }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $question->longitude) }}">
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialiser la carte avec l'emplacement actuel
    var map = L.map('map').setView([{{ $question->latitude }}, {{ $question->longitude }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([{{ $question->latitude }}, {{ $question->longitude }}]).addTo(map);

    // Fonction pour définir le marqueur
    function setMarker(lat, lng, title) {
        if (marker) {
            map.removeLayer(marker);
        }
        
        marker = L.marker([lat, lng]).addTo(map);
        if (title) {
            marker.bindPopup(title).openPopup();
        }
        
        // Mettre à jour les champs cachés
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Clic sur la carte
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        
        // Faire une requête reverse geocoding
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                var locationName = data.display_name;
                document.getElementById('location-search').value = locationName;
                setMarker(lat, lng, locationName);
            })
            .catch(error => {
                console.error('Erreur de géocodage inverse:', error);
                setMarker(lat, lng);
            });
    });

    // Recherche de lieu
    document.getElementById('location-search').addEventListener('blur', function() {
        var query = this.value;
        if (query.length > 3) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var result = data[0];
                        var lat = parseFloat(result.lat);
                        var lng = parseFloat(result.lon);
                        
                        setMarker(lat, lng, result.display_name);
                        map.setView([lat, lng], 13);
                    }
                })
                .catch(error => console.error('Erreur de géocodage:', error));
        }
    });
</script>
@endsection