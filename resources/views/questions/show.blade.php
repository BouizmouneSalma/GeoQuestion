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