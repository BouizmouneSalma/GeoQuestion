@extends('layouts.app')

@section('title', $question->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour aux questions
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="card-title">{{ $question->title }}</h1>
                <p class="text-muted">
                    Posée par {{ $question->user->name }} | {{ $question->created_at->format('d/m/Y à H:i') }}
                </p>
                <div id="map" class="map-container"></div>
                <div class="mb-4">
                    <span class="badge bg-info">
                        <i class="bi bi-geo-alt"></i> {{ $question->location_name }}
                    </span>
                </div>
                <div class="card-text">
                    {{ $question->content }}
                </div>
                
                @if(Auth::id() === $question->user_id)
                <div class="mt-4">
                    <a href="{{ route('questions.edit', $question) }}" class="btn btn-sm btn-outline-primary me-2">Modifier</a>
                    <form action="{{ route('questions.destroy', $question) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question?')">Supprimer</button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <h3>{{ $question->answers_count }} Réponse(s)</h3>
        
        @foreach($question->answers as $answer)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <strong>{{ $answer->user->name }}</strong>
                        <small class="text-muted"> | {{ $answer->created_at->diffForHumans() }}</small>
                    </div>
                    @if(Auth::id() === $answer->user_id)
                    <div>
                        <form action="{{ route('answers.destroy', $answer) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réponse?')">Supprimer</button>
                        </form>
                    </div>
                    @endif
                </div>
                <p class="card-text">{{ $answer->content }}</p>
            </div>
        </div>
        @endforeach

        @auth
        <div class="card mt-4">
            <div class="card-header">Votre réponse</div>
            <div class="card-body">
                <form method="POST" action="{{ route('questions.answers.store', $question) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="4" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-info mt-4">
            <a href="{{ route('login') }}">Connectez-vous</a> pour répondre à cette question.
        </div>
        @endauth
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">À propos de l'auteur</div>
            <div class="card-body">
                <h5>{{ $question->user->name }}</h5>
                <p>Membre depuis {{ $question->user->created_at->format('M Y') }}</p>
                <p>{{ $question->user->questions()->count() }} questions posées</p>
                <p>{{ $question->user->answers()->count() }} réponses données</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialiser la carte
    var map = L.map('map').setView([{{ $question->latitude }}, {{ $question->longitude }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([{{ $question->latitude }}, {{ $question->longitude }}])
        .addTo(map)
        .bindPopup('{{ $question->location_name }}')
        .openPopup();
</script>
@endsection