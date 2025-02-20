@extends('layouts.app')

@section('title', 'Questions Localisées - Liste des questions')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1>Questions localisées</h1>
    </div>
    <div class="col-md-4">
        <a href="{{ route('questions.create') }}" class="btn btn-primary float-end">Poser une question</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <form action="{{ route('questions.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par mot-clé ou lieu..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-primary">Rechercher</button>
        </form>
    </div>
</div>

<div c lass="row">
    <div class="col-md-8">
        @if($questions->count() > 0)
            @foreach($questions as $question)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a>
                        </h5>
                        <p class="text-muted mb-2">
                            <small>Posée par {{ $question->user->name }} | {{ $question->created_at->diffForHumans() }}</small>
                        </p>
                        <p class="card-text">{{ Str::limit($question->content, 200) }}</p>
                        <div class="d-flex justify-content-between">
                            <span>
                                <i class="bi bi-geo-alt"></i> {{ $question->location_name }}
                            </span>
                            <span>
                                <i class="bi bi-chat"></i> {{ $question->answers_count }} réponses
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $questions->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune question trouvée. Soyez le premier à poser une question!
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Statistiques
            </div>
            <div class="card-body">
                <p><strong>Total des questions:</strong> {{ App\Models\Question::count() }}</p>
                <p><strong>Total des réponses:</strong> {{ App\Models\Answer::count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection